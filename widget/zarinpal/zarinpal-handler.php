<?php
namespace PersianElementor\Classes;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * ZarinPal Payment Handler — REST API v4
 *
 * Handles payment requests and verification using ZarinPal's REST API v4.
 * Uses WordPress wp_remote_post() for HTTP requests (no SOAP, no raw cURL, no vendor dependencies).
 *
 * @since 3.0.0
 */
class ZarinPal_Handler {

    /** @var string API v4 base URL for production */
    private const API_URL = 'https://api.zarinpal.com/pg/v4/payment';

    /** @var string API base URL for sandbox */
    private const SANDBOX_URL = 'https://sandbox.zarinpal.com/pg/v4/payment';

    /** @var string Payment redirect URL */
    private const PAYMENT_URL = 'https://payment.zarinpal.com/pg/StartPay';

    /** @var string Sandbox payment redirect URL */
    private const SANDBOX_PAYMENT_URL = 'https://sandbox.zarinpal.com/pg/StartPay';

    /**
     * Multiplier to convert the internal amount back to Toman.
     *
     * zarinpal-ajax.php divides the original Toman amount by 10 before passing it here.
     * We multiply by 10 to restore the original Toman value before sending to ZarinPal.
     */
    private const AMOUNT_MULTIPLIER = 10;

    /**
     * Send a POST request to ZarinPal API v4
     *
     * @param string $endpoint API endpoint path (e.g., 'request.json' or 'verify.json')
     * @param array  $data     Request payload
     * @param bool   $sandbox  Whether to use sandbox mode
     *
     * @return array Decoded JSON response or error array
     */
    private function api_call(string $endpoint, array $data, bool $sandbox = false): array {
        $base_url = $sandbox ? self::SANDBOX_URL : self::API_URL;
        $url = "{$base_url}/{$endpoint}";

        $response = wp_remote_post($url, [
            'timeout'     => 30,
            'httpversion' => '1.1',
            'headers'     => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ],
            'body' => wp_json_encode($data),
        ]);

        // Handle WordPress HTTP API errors (network issues, timeouts, etc.)
        if (is_wp_error($response)) {
            return [
                'success' => false,
                'message' => 'خطا در اتصال به درگاه زرین‌پال: ' . $response->get_error_message(),
            ];
        }

        $http_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $result = json_decode($body, true);

        if (!is_array($result)) {
            return [
                'success' => false,
                'message' => sprintf('پاسخ نامعتبر از درگاه زرین‌پال (HTTP %d)', $http_code),
            ];
        }

        return $result;
    }

    /**
     * Get error message for ZarinPal status code
     *
     * @param int    $status_code Status code from ZarinPal
     * @param string $desc        Transaction description (used for validation)
     * @param string $cb          Callback URL (used for validation)
     * @param bool   $request     Whether this is a payment request (for validation messages)
     *
     * @return string Localized error message
     */
    public function get_error_message($status_code, $desc = "", $cb = "", $request = false) {
        if (empty($cb) && $request === true) {
            return "لینک بازگشت (CallbackURL) نباید خالی باشد";
        }

        if (empty($desc) && $request === true) {
            return "توضیحات تراکنش (Description) نباید خالی باشد";
        }

        $error = [
            "-1"  => "اطلاعات ارسال شده ناقص است.",
            "-2"  => "IP و یا مرچنت کد پذیرنده صحیح نیست.",
            "-3"  => "با توجه به محدودیت‌های شاپرک امکان پرداخت با رقم درخواست شده میسر نمی‌باشد.",
            "-4"  => "سطح تایید پذیرنده پایین‌تر از سطح نقره‌ای است.",
            "-11" => "درخواست مورد نظر یافت نشد.",
            "-12" => "امکان ویرایش درخواست میسر نمی‌باشد.",
            "-21" => "هیچ نوع عملیات مالی برای این تراکنش یافت نشد.",
            "-22" => "تراکنش ناموفق می‌باشد.",
            "-33" => "رقم تراکنش با رقم پرداخت شده مطابقت ندارد.",
            "-34" => "سقف تقسیم تراکنش از لحاظ تعداد یا رقم عبور نموده است.",
            "-40" => "اجازه دسترسی به متد مربوطه وجود ندارد.",
            "-41" => "اطلاعات ارسال شده مربوط به AdditionalData غیرمعتبر می‌باشد.",
            "-42" => "مدت زمان معتبر طول عمر شناسه پرداخت باید بین 30 دقیقه تا 45 روز باشد.",
            "-54" => "درخواست مورد نظر آرشیو شده است.",
            "100" => "عملیات با موفقیت انجام گردیده است.",
            "101" => "عملیات پرداخت موفق بوده و قبلاً تأیید تراکنش انجام شده است.",
        ];

        if (array_key_exists("{$status_code}", $error)) {
            return $error["{$status_code}"];
        }

        return "خطای نامشخص هنگام اتصال به درگاه زرین‌پال (کد: {$status_code})";
    }

    /**
     * Redirect to a URL
     *
     * @param string $url Target URL
     */
    public function redirect($url) {
        $safe_url = esc_url($url);
        @header('Location: ' . $safe_url);
        echo '<meta http-equiv="refresh" content="0; url=' . esc_attr($safe_url) . '" />';
        echo '<script>window.location.href = ' . wp_json_encode($safe_url) . ';</script>';
        exit;
    }

    /**
     * Request payment from ZarinPal
     *
     * @param string $merchant_id Merchant ID (36-character UUID)
     * @param int    $amount      Amount in internal units (will be multiplied by AMOUNT_MULTIPLIER to get Toman)
     * @param string $description Transaction description
     * @param string $callback_url Callback URL after payment
     * @param string $email       Payer email address (optional)
     * @param string $mobile      Payer mobile number (optional)
     * @param bool   $sandbox     Use sandbox mode (default: false)
     * @param bool   $zaringate   Use ZarinGate redirect (optional, default: false)
     *
     * @return array {
     *     @type bool   $success     Whether the request was successful
     *     @type int    $status      ZarinPal status code
     *     @type string $message     Human-readable message
     *     @type string $payment_url URL to redirect user to for payment
     *     @type string $authority   Authority code for this transaction
     * }
     */
    public function request_payment($merchant_id, $amount, $description, $callback_url, $email = '', $mobile = '', $sandbox = false, $zaringate = false) {
        $zaringate = ($sandbox === true) ? false : $zaringate;

        // Convert internal amount to Toman
        $amount_toman = intval($amount) * self::AMOUNT_MULTIPLIER;

        $data = [
            'merchant_id'  => $merchant_id,
            'amount'       => $amount_toman,
            'currency'     => 'IRT',
            'description'  => $description,
            'callback_url' => $callback_url,
        ];

        // Add optional metadata (mobile and email must be inside metadata in REST API v4)
        $metadata = [];
        if (!empty($mobile)) {
            $metadata['mobile'] = $mobile;
        }
        if (!empty($email)) {
            $metadata['email'] = $email;
        }
        if (!empty($metadata)) {
            $data['metadata'] = $metadata;
        }

        $result = $this->api_call('request.json', $data, $sandbox);

        // Check for connection/parsing errors from api_call()
        if (isset($result['success']) && $result['success'] === false) {
            return $result;
        }

        // Parse v4 success response: { "data": { "code": 100, "authority": "..." } }
        if (!empty($result['data']) && isset($result['data']['code']) && (int) $result['data']['code'] === 100) {
            $authority = $result['data']['authority'];
            $payment_base = $sandbox ? self::SANDBOX_PAYMENT_URL : self::PAYMENT_URL;
            $start_pay = "{$payment_base}/{$authority}";

            if ($zaringate) {
                $start_pay .= '/ZarinGate';
            }

            return [
                'success'     => true,
                'status'      => 100,
                'message'     => $this->get_error_message(100, $description, $callback_url, true),
                'payment_url' => $start_pay,
                'authority'   => $authority,
            ];
        }

        // Parse v4 error response: { "errors": { "code": -X, "message": "..." } }
        $error_code = 0;
        $error_message = 'خطای نامشخص هنگام اتصال به درگاه زرین‌پال';

        if (!empty($result['errors']) && isset($result['errors']['code'])) {
            $error_code = (int) $result['errors']['code'];
            $error_message = $this->get_error_message($error_code, $description, $callback_url, true);

            if (!empty($result['errors']['message'])) {
                $error_message .= ' (' . $result['errors']['message'] . ')';
            }
        }

        return [
            'success' => false,
            'status'  => $error_code,
            'message' => $error_message,
        ];
    }

    /**
     * Verify payment from ZarinPal
     *
     * @param string      $merchant_id Merchant ID (36-character UUID)
     * @param int         $amount      Amount in internal units (will be multiplied by AMOUNT_MULTIPLIER to get Toman)
     * @param string|null $authority   Authority code (optional, reads from $_GET['Authority'] if not provided)
     * @param bool        $sandbox     Use sandbox mode (default: false)
     *
     * @return array {
     *     @type bool   $success   Whether the verification was successful
     *     @type int    $status    ZarinPal status code
     *     @type string $message   Human-readable message
     *     @type string $ref_id    Reference ID (on success)
     *     @type string $authority Authority code used
     *     @type string $card_pan  Masked card number (on success)
     *     @type int    $fee       Transaction fee (on success)
     * }
     */
    public function verify_payment($merchant_id, $amount, $authority = null, $sandbox = false) {
        // Get authority from parameter or query string
        $au = $authority ?? (
            isset($_GET['Authority']) && $_GET['Authority'] !== ''
                ? sanitize_text_field(wp_unslash($_GET['Authority']))
                : ''
        );

        if (empty($au)) {
            return [
                'success' => false,
                'message' => 'Authority parameter is missing',
            ];
        }

        // Convert internal amount to Toman
        $amount_toman = intval($amount) * self::AMOUNT_MULTIPLIER;

        $data = [
            'merchant_id' => $merchant_id,
            'authority'   => $au,
            'amount'      => $amount_toman,
            'currency'    => 'IRT',
        ];

        $result = $this->api_call('verify.json', $data, $sandbox);

        // Check for connection/parsing errors from api_call()
        if (isset($result['success']) && $result['success'] === false) {
            return $result;
        }

        // Parse v4 success response: { "data": { "code": 100, "ref_id": ..., "card_pan": ..., "fee": ... } }
        if (!empty($result['data']) && isset($result['data']['code'])) {
            $code = (int) $result['data']['code'];
            $ref_id = $result['data']['ref_id'] ?? '';
            $is_success = ($code === 100 || $code === 101);

            return [
                'success'   => $is_success,
                'status'    => $code,
                'message'   => $this->get_error_message($code),
                'ref_id'    => (string) $ref_id,
                'authority' => $au,
                'card_pan'  => $result['data']['card_pan'] ?? '',
                'fee'       => $result['data']['fee'] ?? 0,
            ];
        }

        // Parse v4 error response: { "errors": { "code": -X, "message": "..." } }
        $error_code = 0;
        $error_message = 'خطای نامشخص هنگام تأیید تراکنش';

        if (!empty($result['errors']) && isset($result['errors']['code'])) {
            $error_code = (int) $result['errors']['code'];
            $error_message = $this->get_error_message($error_code);

            if (!empty($result['errors']['message'])) {
                $error_message .= ' (' . $result['errors']['message'] . ')';
            }
        }

        return [
            'success'   => false,
            'status'    => $error_code,
            'message'   => $error_message,
            'ref_id'    => '',
            'authority' => $au,
        ];
    }
}
