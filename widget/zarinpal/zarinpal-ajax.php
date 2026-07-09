<?php
namespace PersianElementor;

use PersianElementor\Classes\ZarinPal_Handler;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ZarinPal_Ajax {

    private $zarinpal_handler;

    /** @var int Transaction expiry time in seconds (30 days) */
    private const TRANSACTION_EXPIRY = 30 * DAY_IN_SECONDS;

    /** @var string Cron hook name for transaction cleanup */
    private const CLEANUP_CRON_HOOK = 'persian_elementor_cleanup_transactions';

    public function __construct() {
        $this->zarinpal_handler = new ZarinPal_Handler();
        
        // Register AJAX handlers
        add_action('wp_ajax_zarinpal_payment_request', [$this, 'process_payment_request']);
        add_action('wp_ajax_nopriv_zarinpal_payment_request', [$this, 'process_payment_request']);
        
        // Handle verification requests with higher specificity to avoid conflicts
        add_action('template_redirect', [$this, 'handle_verification'], 5); // Lower priority

        // Schedule daily cleanup cron job
        add_action(self::CLEANUP_CRON_HOOK, [$this, 'cleanup_expired_transactions']);
        if ( ! wp_next_scheduled(self::CLEANUP_CRON_HOOK) ) {
            wp_schedule_event(time(), 'daily', self::CLEANUP_CRON_HOOK);
        }
    }
    
    /**
     * Process payment request via AJAX
     */
    public function process_payment_request() {
        // Verify nonce
        if (!isset($_POST['zarinpal_nonce']) || !wp_verify_nonce($_POST['zarinpal_nonce'], 'zarinpal_payment_request')) {
            wp_die('نشست منقضی شده است. لطفا صفحه را بارگذاری مجدد کنید.');
        }
        
        // Get form data
        $merchant_id = isset($_POST['merchant_id']) ? sanitize_text_field($_POST['merchant_id']) : '';
        $amount = isset($_POST['amount']) ? intval($_POST['amount'] / 10) : 0; // Divide by 10 to remove one zero
        $description = isset($_POST['description']) ? sanitize_text_field($_POST['description']) : '';
        $callback_url = isset($_POST['callback_url']) ? esc_url_raw($_POST['callback_url']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $mobile = isset($_POST['mobile']) ? sanitize_text_field($_POST['mobile']) : '';
        
        // Validate required fields for ZarinPal
        $errors = [];
        
        if (empty($merchant_id)) {
            $errors[] = 'شناسه مرچنت زرین‌پال وارد نشده است.';
        }
        
        if (empty($amount)) {
            $errors[] = 'مبلغ پرداخت وارد نشده است.';
        }
        
        if (empty($description)) {
            $errors[] = 'توضیحات پرداخت وارد نشده است.';
        }
        
        if (empty($callback_url)) {
            $errors[] = 'آدرس بازگشت وارد نشده است.';
        }
        
        if (!empty($errors)) {
            wp_die('همه فیلدهای ضروری را پر کنید: ' . implode(' ', $errors));
        }
        
        // Store transaction data for later verification with unique prefix
        $transaction_id = 'pe_' . wp_generate_uuid4(); // PE = Persian Elementor
        $transaction_data = [
            'merchant_id' => $merchant_id,
            'amount' => $amount,
            'description' => $description,
            'transaction_id' => $transaction_id,
            'created_at' => time(),
            'source' => 'persian_elementor', // Mark as our transaction
        ];
        
        set_transient('zarinpal_transaction_' . $transaction_id, $transaction_data, self::TRANSACTION_EXPIRY);
        
        // Always add transaction_id parameter to the callback URL
        $callback_url = add_query_arg(['transaction_id' => $transaction_id], $callback_url);
        
        // Request payment from ZarinPal
        $result = $this->zarinpal_handler->request_payment(
            $merchant_id, 
            $amount, 
            $description, 
            $callback_url,
            $email,
            $mobile
        );
        
        if ($result['success']) {
            // Update transaction with authority
            $transaction_data['authority'] = $result['authority'];
            set_transient('zarinpal_transaction_' . $transaction_id, $transaction_data, self::TRANSACTION_EXPIRY);
            
            // Redirect to payment gateway
            $this->zarinpal_handler->redirect($result['payment_url']);
        } else {
            wp_die('خطا در ایجاد تراکنش: ' . $result['message']);
        }
    }
    
    /**
     * Handle payment verification
     */
    public function handle_verification() {
        // More specific check to avoid conflicts with theme's ZarinPal system
        if (!isset($_GET['Authority']) || !isset($_GET['Status']) || !isset($_GET['transaction_id'])) {
            return; // Exit if not our specific Zarinpal callback
        }

        // Additional check: ensure this is our transaction by checking the transaction_id format
        $transaction_id = isset($_GET['transaction_id']) ? sanitize_text_field($_GET['transaction_id']) : '';
        if (empty($transaction_id) || !$this->is_our_transaction($transaction_id)) {
            return; // Not our transaction, let theme handle it
        }

        // Get data from Zarinpal callback
        $authority = isset($_GET['Authority']) ? sanitize_text_field($_GET['Authority']) : '';
        $status = isset($_GET['Status']) ? sanitize_text_field($_GET['Status']) : '';
        
        if (empty($authority) || empty($status)) {
            wp_die('اطلاعات بازگشتی از درگاه ناقص است.');
        }
        
        // Get transaction data
        $transaction_data = get_transient('zarinpal_transaction_' . $transaction_id);
        
        if (!$transaction_data) {
            wp_die('تراکنش یافت نشد یا نامعتبر است.');
        }

        // Store the message to show after the payment
        $zarinpal_message = '';
        $zarinpal_message_class = '';
        $verification_success = false; // Add this variable to track actual verification success
        
        // Check if payment was successful according to ZarinPal status
        if ($status === 'OK') {
            // Verify payment with ZarinPal API
            $result = $this->zarinpal_handler->verify_payment(
                $transaction_data['merchant_id'],
                $transaction_data['amount'],
                $authority,
                false // Assuming production mode
            );
            
            if ($result['success']) {
                // Payment verified successfully
                $transaction_data['status'] = 'completed';
                $transaction_data['ref_id'] = $result['ref_id'];
                set_transient('zarinpal_transaction_' . $transaction_id, $transaction_data, self::TRANSACTION_EXPIRY);
                
                // Run action hook for successful payment with namespace
                do_action('persian_elementor_zarinpal_payment_success', $transaction_data, $result);
                
                // Prepare the success message
                $zarinpal_message = sprintf('پرداخت با موفقیت انجام شد. کد پیگیری: %s', '<strong>' . esc_html($result['ref_id']) . '</strong>');
                $zarinpal_message_class = 'zarinpal-success-message';
                $verification_success = true; // Verification was successful
                
            } else {
                // Payment verification failed
                $transaction_data['status'] = 'failed';
                $transaction_data['error'] = $result['message'];
                set_transient('zarinpal_transaction_' . $transaction_id, $transaction_data, self::TRANSACTION_EXPIRY);
                
                // Prepare the error message
                $zarinpal_message = sprintf('تایید پرداخت ناموفق بود. پیام درگاه: %s (کد خطا: %s)', esc_html($result['message']), esc_html($result['status']));
                $zarinpal_message_class = 'zarinpal-error-message';
                $verification_success = false; // Verification failed
            }
        } else {
            // Payment canceled or failed (Status != OK)
            $transaction_data['status'] = 'canceled';
            set_transient('zarinpal_transaction_' . $transaction_id, $transaction_data, self::TRANSACTION_EXPIRY);
            
            // Prepare the cancel message
            $zarinpal_message = 'پرداخت توسط کاربر لغو شد یا در مرحله اولیه ناموفق بود.';
            $zarinpal_message_class = 'zarinpal-cancel-message';
        }
        
        // Add the message to display it in the right location
        add_action('wp_footer', function() use ($zarinpal_message, $zarinpal_message_class, $status, $verification_success) { // Pass verification_success
            if (empty($zarinpal_message)) return;

            // Determine notification type based on status AND verification result
            $notification_type = 'info'; // Default
            
            // First check for message class, which is more specific than status
            if ($zarinpal_message_class === 'zarinpal-success-message' && $verification_success === true) {
                $notification_type = 'success';
            } elseif ($zarinpal_message_class === 'zarinpal-error-message') {
                $notification_type = 'error';
            } elseif ($zarinpal_message_class === 'zarinpal-cancel-message') {
                $notification_type = 'warning'; // Use warning for cancel
            } else {
                $notification_type = 'info'; // Fallback
            }

            // Add CSS for notifications
            ?>
            <style>
                .zarinpal-notification-container {
                    position: fixed;
                    top: 40px; /* Increased top margin */
                    right: 20px; /* Positioned to the right */
                    left: auto; /* Reset left positioning */
                    transform: none; /* Remove horizontal centering */
                    z-index: 9999;
                    direction: rtl;
                    text-align: right; /* Align text to the right */
                    min-width: 300px;
                    max-width: 90%;
                }
                .zarinpal-notification {
                    padding: 15px 20px;
                    margin-bottom: 15px;
                    border-radius: 8px;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                    display: flex;
                    align-items: center;
                    justify-content: flex-start; /* Keep content aligned to start (right in RTL) */
                    animation: zarinpal-notification-fadein 0.5s forwards; /* Use forwards to keep final state */
                    opacity: 0;
                }
                .zarinpal-notification-success {
                    background-color: #e7f8f0;
                    color: #0a6245;
                    border: 1px solid #a3e2c7;
                }
                .zarinpal-notification-error {
                    background-color: #ffeeee;
                    color: #d92626;
                    border: 1px solid #ffbdbd;
                }
                .zarinpal-notification-warning {
                    background-color: #fff8e6;
                    color: #b7750f;
                    border: 1px solid #ffe0a3;
                }
                .zarinpal-notification-info {
                    background-color: #e6f3ff;
                    color: #0d5db6;
                    border: 1px solid #a8d1ff;
                }
                /* Inherit typography from button text */
                .zarinpal-notification span {
                    font-family: inherit;
                    font-size: inherit;
                    font-weight: inherit;
                    line-height: inherit;
                    letter-spacing: inherit;
                }
                .zarinpal-notification .close-btn {
                    margin-right: auto;
                    cursor: pointer;
                    font-size: 18px;
                    opacity: 0.5;
                    transition: opacity 0.3s;
                }
                .zarinpal-notification .close-btn:hover {
                    opacity: 1;
                }
                @keyframes zarinpal-notification-fadein {
                    from {opacity: 0; transform: translateY(-20px);}
                    to {opacity: 1; transform: translateY(0);}
                }
            </style>

            <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                // Create container for notifications if it doesn't exist
                var container = document.querySelector('.zarinpal-notification-container');
                if (!container) {
                    container = document.createElement('div');
                    container.className = 'zarinpal-notification-container';
                    document.body.appendChild(container);
                }

                // Create notification element
                var notification = document.createElement('div');
                notification.className = 'zarinpal-notification zarinpal-notification-<?php echo esc_js($notification_type); ?>';
                
                // Create close button
                var closeBtn = '<span class="close-btn">&times;</span>';

                // Find the ZarinPal button to copy its styles
                var zarinpalButton = document.querySelector('.elementor-zarinpal-button');
                
                // Set content (without icon)
                notification.innerHTML = '<span class="elementor-button-text"><?php echo wp_kses_post($zarinpal_message); ?></span>' + closeBtn;
                
                // Add the notification to the container
                container.appendChild(notification);
                
                // Copy typography styles from button to notification text if button exists
                if (zarinpalButton) {
                    var buttonTextElement = zarinpalButton.querySelector('.elementor-button-text');
                    if (buttonTextElement) {
                        var styles = window.getComputedStyle(buttonTextElement);
                        var textElement = notification.querySelector('span');
                        if (textElement) {
                            textElement.style.fontFamily = styles.fontFamily;
                            textElement.style.fontSize = styles.fontSize;
                            textElement.style.fontWeight = styles.fontWeight;
                            textElement.style.lineHeight = styles.lineHeight;
                            textElement.style.letterSpacing = styles.letterSpacing;
                        }
                    }
                }
                
                // Show the notification (trigger animation)
                setTimeout(function() {
                    notification.style.opacity = '1';
                }, 100);
                
                // Add click event to close button
                notification.querySelector('.close-btn').addEventListener('click', function() {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-20px)';
                    setTimeout(function() {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 500);
                });
                
                // The auto-remove functionality has been removed
                // Now the notification will only disappear when the close button is clicked
            });
            </script>
            <?php
        }, 99);
    }

    /**
     * Check if transaction belongs to our plugin
     * 
     * @param string $transaction_id
     * @return bool
     */
    private function is_our_transaction($transaction_id) {
        // Check if transaction ID has our prefix
        if (!str_starts_with($transaction_id, 'pe_')) {
            return false;
        }
        
        // Check if transaction exists in our options and has our source marker
        $transaction_data = get_transient('zarinpal_transaction_' . $transaction_id);
        if ($transaction_data === false) {
            return false;
        }
        
        // Verify it's marked as our transaction
        return isset($transaction_data['source']) && $transaction_data['source'] === 'persian_elementor';
    }

    public function cleanup_expired_transactions() {
        global $wpdb;

        // 1. Delete expired transients matching our pattern
        $wpdb->query(
            $wpdb->prepare(
                "DELETE a, b FROM {$wpdb->options} a
                LEFT JOIN {$wpdb->options} b ON b.option_name = CONCAT('_transient_timeout_', SUBSTRING(a.option_name, 12))
                WHERE a.option_name LIKE %s
                AND b.option_value < %d",
                $wpdb->esc_like('_transient_zarinpal_transaction_pe_') . '%',
                time()
            )
        );

        // 2. Clean up legacy regular options (saved before version 2.8.0 upgrade)
        $legacy_options = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s",
                $wpdb->esc_like('zarinpal_transaction_pe_') . '%'
            )
        );

        if ( ! empty( $legacy_options ) ) {
            $thirty_days_ago = time() - self::TRANSACTION_EXPIRY;
            foreach ( $legacy_options as $option ) {
                $data = maybe_unserialize( $option->option_value );
                // Delete if the data is corrupt, has no timestamp, or is older than 30 days
                if ( ! is_array( $data ) || ! isset( $data['created_at'] ) || (int) $data['created_at'] < $thirty_days_ago ) {
                    delete_option( $option->option_name );
                }
            }
        }
    }

    /**
     * Unschedule the cleanup cron job on plugin deactivation.
     * Should be called from the main plugin file's deactivation hook.
     */
    public static function deactivate() {
        wp_clear_scheduled_hook(self::CLEANUP_CRON_HOOK);
    }
}

// Initialize the class
new ZarinPal_Ajax();
