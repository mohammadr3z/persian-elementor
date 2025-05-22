<?php
namespace PersianElementor\Classes;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ZarinPal_Handler {
    // Constants for payment amounts and configuration
    private const RIAL_MULTIPLIER = 10; // Convert input amount to rials (1 unit = 1000 tomans = 10000 rials)
    
    /**
     * Check if CURL is available
     *
     * @return bool
     */
    private function curl_check() {
        return (function_exists('curl_version')) ? true : false;
    }
    
    /**
     * Check if SOAP is available
     *
     * @return bool
     */
    private function soap_check() {
        return (extension_loaded('soap')) ? true : false;
    }
    
    /**
     * Get error message for ZarinPal status code
     * 
     * @param int $status_code Status code
     * @param string $desc Description
     * @param string $cb Callback URL
     * @param bool $request Is request or verify
     * 
     * @return string Error message
     */
    public function get_error_message($status_code, $desc = "", $cb = "", $request = false) {
        if (empty($cb) && $request === true) {
            return "لینک بازگشت (CallbackURL) نباید خالی باشد";
        }

        if (empty($desc) && $request === true) {
            return "توضیحات تراکنش (Description) نباید خالی باشد";
        }

        $error = [
            "-1" => "اطلاعات ارسال شده ناقص است.",
            "-2" => "IP و يا مرچنت كد پذيرنده صحيح نيست",
            "-3" => "با توجه به محدوديت هاي شاپرك امكان پرداخت با رقم درخواست شده ميسر نمي باشد",
            "-4" => "سطح تاييد پذيرنده پايين تر از سطح نقره اي است.",
            "-11" => "درخواست مورد نظر يافت نشد.",
            "-12" => "امكان ويرايش درخواست ميسر نمي باشد.",
            "-21" => "هيچ نوع عمليات مالي براي اين تراكنش يافت نشد",
            "-22" => "تراكنش نا موفق ميباشد",
            "-33" => "رقم تراكنش با رقم پرداخت شده مطابقت ندارد",
            "-34" => "سقف تقسيم تراكنش از لحاظ تعداد يا رقم عبور نموده است",
            "-40" => "اجازه دسترسي به متد مربوطه وجود ندارد.",
            "-41" => "اطلاعات ارسال شده مربوط به AdditionalData غيرمعتبر ميباشد.",
            "-42" => "مدت زمان معتبر طول عمر شناسه پرداخت بايد بين 30 دقيه تا 45 روز مي باشد.",
            "-54" => "درخواست مورد نظر آرشيو شده است",
            "100" => "عمليات با موفقيت انجام گرديده است.",
            "101" => "عمليات پرداخت موفق بوده و قبلا PaymentVerification تراكنش انجام شده است."
        ];

        if (array_key_exists("{$status_code}", $error)) {
            return $error["{$status_code}"];
        } else {
            return "خطای نامشخص هنگام اتصال به درگاه زرین پال";
        }
    }

    /**
     * Redirect to a URL
     *
     * @param string $url
     */
    public function redirect($url) {
        @header('Location: '. $url);
        echo "<meta http-equiv='refresh' content='0; url={$url}' />";
        echo "<script>window.location.href = '{$url}';</script>";
        exit;
    }
    
    /**
     * Select the best ZarinPal node (IR or DE)
     *
     * @return string Node code ('ir' or 'de')
     */
    private function zarinpal_node() {
        if ($this->curl_check() === true) {
            $ir_ch = curl_init("https://www.zarinpal.com/pg/services/WebGate/wsdl");
            curl_setopt($ir_ch, CURLOPT_TIMEOUT, 1);
            curl_setopt($ir_ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ir_ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ir_ch);
            $ir_info = curl_getinfo($ir_ch);
            curl_close($ir_ch);

            $de_ch = curl_init("https://de.zarinpal.com/pg/services/WebGate/wsdl");
            curl_setopt($de_ch, CURLOPT_TIMEOUT, 1);
            curl_setopt($de_ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($de_ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($de_ch);
            $de_info = curl_getinfo($de_ch);
            curl_close($de_ch);

            $ir_total_time = (isset($ir_info['total_time']) && $ir_info['total_time'] > 0) ? $ir_info['total_time'] : false;
            $de_total_time = (isset($de_info['total_time']) && $de_info['total_time'] > 0) ? $de_info['total_time'] : false;

            return ($ir_total_time === false || $ir_total_time > $de_total_time) ? "de" : "ir";
        } else {
            if (function_exists('fsockopen')) {
                $de_ping = $this->zarinpal_ping("de.zarinpal.com", 80, 1);
                $ir_ping = $this->zarinpal_ping("www.zarinpal.com", 80, 1);

                $ir_total_time = (isset($ir_ping) && $ir_ping > 0) ? $ir_ping : false;
                $de_total_time = (isset($de_ping) && $de_ping > 0) ? $de_ping : false;

                return ($ir_total_time === false || $ir_total_time > $de_total_time) ? "de" : "ir";
            } else {
                $webservice = "https://www.zarinpal.com/pg/services/WebGate/wsd";
                $headers = @get_headers($webservice);

                return (strpos($headers[0], '200') === false) ? "de" : "ir";
            }
        }
    }
    
    /**
     * Ping a server to check response time
     *
     * @param string $host
     * @param int $port
     * @param int $timeout
     * @return bool|float
     */
    private function zarinpal_ping($host, $port, $timeout) {
        $time_b = microtime(true);
        $fsockopen = @fsockopen($host, $port, $errno, $errstr, $timeout);

        if (!$fsockopen) {
            return false;
        } else {
            $time_a = microtime(true);
            return round((($time_a - $time_b) * 1000), 0);
        }
    }

    /**
     * Request payment from ZarinPal
     * 
     * @param string $merchant_id Merchant ID
     * @param int $amount Amount in Toman
     * @param string $description Transaction description
     * @param string $callback_url Callback URL
     * @param string $email Email address (optional)
     * @param string $mobile Mobile number (optional)
     * @param bool $sandbox Use sandbox mode
     * @param bool $zaringate Use ZarinGate (optional)
     * 
     * @return array Response with status and payment URL
     */
    public function request_payment($merchant_id, $amount, $description, $callback_url, $email = '', $mobile = '', $sandbox = false, $zaringate = false) {
        $zaringate = ($sandbox == true) ? false : $zaringate;
        
        // Convert input number directly to rials using the constant
        $amount = intval($amount) * self::RIAL_MULTIPLIER;
        
        $upay = ($sandbox == true) ? "sandbox" : "www";
        $node = ($sandbox == true) ? "sandbox" : $this->zarinpal_node();
        
        if ($this->soap_check() === true) {
            // Using SOAP method
            try {
                $client = new \SoapClient("https://{$node}.zarinpal.com/pg/services/WebGate/wsdl", ['encoding' => 'UTF-8']);

                $result = $client->PaymentRequest([
                    'MerchantID' => $merchant_id,
                    'Amount' => $amount, // Already converted to rials
                    'Description' => $description,
                    'Email' => $email,
                    'Mobile' => $mobile,
                    'CallbackURL' => $callback_url,
                ]);

                $Status = (isset($result->Status) && $result->Status != "") ? $result->Status : 0;
                $Authority = (isset($result->Authority) && $result->Authority != "") ? $result->Authority : "";
                $StartPay = (isset($result->Authority) && $result->Authority != "") ? "https://{$upay}.zarinpal.com/pg/StartPay/". $Authority : "";
                $StartPayUrl = ($zaringate == true) ? "{$StartPay}/ZarinGate" : $StartPay;

                return [
                    "success" => ($Status == 100),
                    "status" => $Status,
                    "message" => $this->get_error_message($Status, $description, $callback_url, true),
                    "payment_url" => $StartPayUrl,
                    "authority" => $Authority
                ];
            } catch (\Exception $e) {
                // Fall back to CURL if SOAP fails
                return $this->request_payment_curl($merchant_id, $amount, $description, $callback_url, $email, $mobile, $sandbox, $zaringate);
            }
        } else {
            // Using CURL method
            return $this->request_payment_curl($merchant_id, $amount, $description, $callback_url, $email, $mobile, $sandbox, $zaringate);
        }
    }
    
    /**
     * Request payment from ZarinPal using CURL
     */
    private function request_payment_curl($merchant_id, $amount, $description, $callback_url, $email = '', $mobile = '', $sandbox = false, $zaringate = false) {
        $upay = ($sandbox == true) ? "sandbox" : "www";
        
        // No need to convert amount here as it's already converted in the request_payment method
        
        $data = [
            'MerchantID' => $merchant_id,
            'Amount' => $amount,
            'Description' => $description,
            'CallbackURL' => $callback_url,
        ];
        
        // Add optional parameters
        if (!empty($email)) {
            $data['Email'] = $email;
        }
        
        if (!empty($mobile)) {
            $data['Mobile'] = $mobile;
        }
        
        $jsonData = json_encode($data);
        $ch = curl_init("https://{$upay}.zarinpal.com/pg/rest/WebGate/PaymentRequest.json");
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($jsonData)]);
        
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        
        if ($err) {
            return [
                "success" => false,
                "message" => "cURL Error #:" . $err,
            ];
        }
        
        $result = json_decode($result, true);
        
        if (!isset($result["Status"])) {
            return [
                "success" => false,
                "message" => "پاسخ نامعتبر از درگاه زرین‌پال",
            ];
        }
        
        $Status = $result["Status"];
        $Message = $this->get_error_message($Status, $description, $callback_url, true);
        $Authority = (isset($result["Authority"]) && $result["Authority"] != "") ? $result["Authority"] : "";
        $StartPay = (isset($result["Authority"]) && $result["Authority"] != "") ? "https://{$upay}.zarinpal.com/pg/StartPay/". $Authority : "";
        $StartPayUrl = ($zaringate && $sandbox == false) ? "{$StartPay}/ZarinGate" : $StartPay;
        
        return [
            "success" => ($Status == 100),
            "status" => $Status,
            "message" => $Message,
            "payment_url" => $StartPayUrl,
            "authority" => $Authority
        ];
    }

    /**
     * Verify payment from ZarinPal
     * 
     * @param string $merchant_id Merchant ID
     * @param int $amount Amount in Toman
     * @param string $authority Authority code (optional, will use $_GET if not provided)
     * @param bool $sandbox Use sandbox mode
     * 
     * @return array Response with status and payment info
     */
    public function verify_payment($merchant_id, $amount, $authority = null, $sandbox = false) {
        $au = $authority ?? (isset($_GET['Authority']) && $_GET['Authority'] != "" ? $_GET['Authority'] : "");
        if (empty($au)) {
            return [
                "success" => false,
                "message" => "Authority parameter is missing",
            ];
        }
        
        // Use the same constant for consistent amount conversion
        $amount = intval($amount) * self::RIAL_MULTIPLIER;
        
        if ($this->soap_check() === true) {
            // Using SOAP method
            $node = ($sandbox == true) ? "sandbox" : $this->zarinpal_node();
            
            try {
                $client = new \SoapClient("https://{$node}.zarinpal.com/pg/services/WebGate/wsdl", ['encoding' => 'UTF-8']);
                
                // No need to convert amount here as it's already converted above

                $result = $client->PaymentVerification([
                    'MerchantID' => $merchant_id,
                    'Authority' => $au,
                    'Amount' => $amount,
                ]);

                $Status = (isset($result->Status) && $result->Status != "") ? $result->Status : 0;
                $RefID = (isset($result->RefID) && $result->RefID != "") ? $result->RefID : "";
                $Message = $this->get_error_message($Status);
                $is_success = ($Status == 100 || $Status == 101);

                // Always return the response
                $response = [
                    "success" => $is_success,
                    "status" => $Status,
                    "message" => $Message,
                    "ref_id" => $RefID,
                    "authority" => $au
                ];
                return $response;
                
            } catch (\Exception $e) {
                // Fall back to CURL if SOAP fails
                return $this->verify_payment_curl($merchant_id, $amount, $au, $sandbox);
            }
        } else {
            // Using CURL method
            return $this->verify_payment_curl($merchant_id, $amount, $au, $sandbox);
        }
    }
    
    /**
     * Verify payment from ZarinPal using CURL
     */
    private function verify_payment_curl($merchant_id, $amount, $authority, $sandbox = false) {
        $upay = ($sandbox == true) ? "sandbox" : "www";
        
        // No need to convert amount here as it's already converted in the verify_payment method
        
        $data = [
            'MerchantID' => $merchant_id, 
            'Authority' => $authority, 
            'Amount' => $amount
        ];
        
        $jsonData = json_encode($data);
        $ch = curl_init("https://{$upay}.zarinpal.com/pg/rest/WebGate/PaymentVerification.json");
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($jsonData)]);

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return [
                "success" => false,
                "message" => "cURL Error #:" . $err,
            ];
        }

        $result = json_decode($result, true);
        
        if (!isset($result["Status"])) {
            return [
                "success" => false,
                "message" => "پاسخ نامعتبر از درگاه زرین‌پال",
            ];
        }

        // Process the payment and prepare response
        $Status = $result["Status"];
        $RefID = (isset($result['RefID']) && $result['RefID'] != "") ? $result['RefID'] : "";
        $Message = $this->get_error_message($Status);
        $is_success = ($Status == 100 || $Status == 101);

        // Always return the response
        $response = [
            "success" => $is_success,
            "status" => $Status,
            "message" => $Message,
            "ref_id" => $RefID,
            "authority" => $authority
        ];
        return $response;
    }
}
