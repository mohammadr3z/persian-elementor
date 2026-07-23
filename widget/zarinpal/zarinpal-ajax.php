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
        $nonce = isset($_POST['zarinpal_nonce']) ? sanitize_text_field(wp_unslash($_POST['zarinpal_nonce'])) : '';
        if (!$nonce || !wp_verify_nonce($nonce, 'zarinpal_payment_request')) {
            wp_die('نشست منقضی شده است. لطفا صفحه را بارگذاری مجدد کنید.');
        }
        
        // Get form data
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $widget_id = isset($_POST['widget_id']) ? sanitize_text_field($_POST['widget_id']) : '';
        $callback_url = isset($_POST['callback_url']) ? esc_url_raw($_POST['callback_url']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $mobile = isset($_POST['mobile']) ? sanitize_text_field($_POST['mobile']) : '';
        
        // Validate required fields
        if (empty($post_id) || empty($widget_id)) {
            wp_die('اطلاعات ابزارک یا صفحه نامعتبر است.');
        }

        if (empty($callback_url)) {
            wp_die('آدرس بازگشت وارد نشده است.');
        }

        // Validate callback URL host to prevent open redirect vulnerabilities
        $home_host = wp_parse_url(home_url(), PHP_URL_HOST);
        $callback_host = wp_parse_url($callback_url, PHP_URL_HOST);
        if ($callback_host !== $home_host) {
            $callback_url = get_permalink($post_id);
        }

        // Load settings from Elementor database securely
        if (!class_exists('\Elementor\Plugin') || !isset(\Elementor\Plugin::$instance->documents)) {
            wp_die('افزونه المنتور فعال نیست.');
        }

        $document = \Elementor\Plugin::$instance->documents->get($post_id);
        if (!$document) {
            wp_die('سند المنتور یافت نشد.');
        }

        $elements_data = $document->get_elements_data();
        if (empty($elements_data)) {
            wp_die('داده‌های المنتور یافت نشد.');
        }

        $widget = $this->find_element_by_id($elements_data, $widget_id);
        if (!$widget || !isset($widget['elType']) || $widget['elType'] !== 'widget' || !isset($widget['widgetType']) || $widget['widgetType'] !== 'zarinpal-button') {
            wp_die('ابزارک زرین‌پال یافت نشد.');
        }

        // Extract settings
        $settings = [];
        $widget_instance = \Elementor\Plugin::$instance->elements_manager->create_element_instance($widget);
        if ($widget_instance) {
            $settings = $widget_instance->get_settings_for_display();
        } else {
            $settings = isset($widget['settings']) ? $widget['settings'] : [];
        }

        $merchant_id = isset($settings['merchant_id']) ? sanitize_text_field($settings['merchant_id']) : '';
        $product_name = isset($settings['product_name']) ? sanitize_text_field($settings['product_name']) : 'محصول';
        $unit_price = isset($settings['product_price']) ? intval($settings['product_price']) : 10;
        $quantity = isset($settings['product_quantity']) ? max(1, intval($settings['product_quantity'])) : 1;

        if (empty($merchant_id)) {
            wp_die('شناسه مرچنت زرین‌پال وارد نشده است.');
        }

        // Calculate amount (convert thousands unit to absolute, then divide by 10 to keep internal zarinpal-ajax units)
        $total_price_toman = $unit_price * $quantity * 1000;
        $amount = intval($total_price_toman / 10);

        if (empty($amount)) {
            wp_die('مبلغ پرداخت وارد نشده است.');
        }

        // Construct description
        $description = $product_name . ' (' . $quantity . ' ' . __('عدد', 'persian-elementor') . ')';
        
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
        $verification_success = false;

        /*
         * Serialize verification per transaction with MySQL/MariaDB's advisory lock.
         * GET_LOCK is connection-scoped, atomic and automatically released when the
         * request's database connection closes. This prevents two simultaneous
         * callbacks (including ZarinPal 100/101 responses) from firing the success
         * hook more than once.
         */
        global $wpdb;
        $lock_name = 'pe_zp_' . md5($transaction_id);
        $lock_acquired = (int) $wpdb->get_var(
            $wpdb->prepare('SELECT GET_LOCK(%s, %d)', $lock_name, 10)
        ) === 1;

        if (!$lock_acquired) {
            wp_die('درخواست دیگری در حال پردازش این تراکنش است. لطفا چند لحظه دیگر صفحه را بارگذاری مجدد کنید.');
        }

        try {
            // Always use the latest state after acquiring the lock.
            $transaction_data = get_transient('zarinpal_transaction_' . $transaction_id);
            if (!$transaction_data) {
                wp_die('تراکنش یافت نشد یا منقضی شده است.');
            }

            /*
             * Bind the gateway callback to the Authority received when this exact
             * transaction was created. A valid Authority for a different payment
             * must never complete this transaction.
             */
            $stored_authority = isset($transaction_data['authority']) ? (string) $transaction_data['authority'] : '';
            if ($stored_authority === '' || !hash_equals($stored_authority, (string) $authority)) {
                wp_die('شناسه پرداخت با تراکنش ثبت‌شده مطابقت ندارد.');
            }

            // A replay is informative only: never verify again and never fire the hook.
            if (($transaction_data['status'] ?? '') === 'completed') {
                $ref_id = isset($transaction_data['ref_id']) ? $transaction_data['ref_id'] : '';
                $zarinpal_message = sprintf('این پرداخت قبلاً با موفقیت انجام شده است. کد پیگیری: %s', '<strong>' . esc_html($ref_id) . '</strong>');
                $zarinpal_message_class = 'zarinpal-success-message';
                $verification_success = true;
            } elseif ($status === 'OK') {
                $result = $this->zarinpal_handler->verify_payment(
                    $transaction_data['merchant_id'],
                    $transaction_data['amount'],
                    $authority,
                    false
                );

                if (!empty($result['success'])) {
                    // Persist the terminal state before notifying listeners.
                    $transaction_data['status'] = 'completed';
                    $transaction_data['ref_id'] = $result['ref_id'] ?? '';
                    set_transient('zarinpal_transaction_' . $transaction_id, $transaction_data, self::TRANSACTION_EXPIRY);

                    // This runs exactly once per transaction because the lock is held.
                    do_action('persian_elementor_zarinpal_payment_success', $transaction_data, $result);

                    $zarinpal_message = sprintf('پرداخت با موفقیت انجام شد. کد پیگیری: %s', '<strong>' . esc_html($transaction_data['ref_id']) . '</strong>');
                    $zarinpal_message_class = 'zarinpal-success-message';
                    $verification_success = true;
                } else {
                    $transaction_data['status'] = 'failed';
                    $transaction_data['error'] = $result['message'] ?? 'خطای نامشخص';
                    set_transient('zarinpal_transaction_' . $transaction_id, $transaction_data, self::TRANSACTION_EXPIRY);

                    $zarinpal_message = sprintf(
                        'تایید پرداخت ناموفق بود. پیام درگاه: %s (کد خطا: %s)',
                        esc_html($transaction_data['error']),
                        esc_html($result['status'] ?? '')
                    );
                    $zarinpal_message_class = 'zarinpal-error-message';
                }
            } else {
                $transaction_data['status'] = 'canceled';
                set_transient('zarinpal_transaction_' . $transaction_id, $transaction_data, self::TRANSACTION_EXPIRY);
                $zarinpal_message = 'پرداخت توسط کاربر لغو شد یا در مرحله اولیه ناموفق بود.';
                $zarinpal_message_class = 'zarinpal-cancel-message';
            }
        } finally {
            // Release only this request's advisory lock.
            $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $lock_name));
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

    /**
     * Recursively find an Elementor element by ID in elements data tree.
     *
     * @param array  $elements Elementor elements data.
     * @param string $id       Widget instance ID.
     * @return array|null Widget element array or null.
     */
    private function find_element_by_id(array $elements, string $id) {
        foreach ($elements as $element) {
            if (isset($element['id']) && $element['id'] === $id) {
                return $element;
            }
            if (!empty($element['elements'])) {
                $found = $this->find_element_by_id($element['elements'], $id);
                if ($found) {
                    return $found;
                }
            }
        }
        return null;
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
