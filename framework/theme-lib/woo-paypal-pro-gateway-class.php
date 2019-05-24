<?php

class WC_PP_PRO_Gateway {

    protected $PAYPAL_NVP_SIG_SANDBOX = "https://api-3t.sandbox.paypal.com/nvp";
    protected $PAYPAL_NVP_SIG_LIVE = "https://api-3t.paypal.com/nvp";
    protected $PAYPAL_NVP_PAYMENTACTION = "Sale";
    protected $PAYPAL_NVP_METHOD = "DoDirectPayment";
    protected $PAYPAL_NVP_API_VERSION = "84.0";
    protected $order = null;
    protected $transactionId = null;
    protected $transactionErrorMessage = null;
    protected $usesandboxapi = true;
    protected $securitycodehint = true;
    protected $apiusername = '';
    protected $apipassword = '';
    protected $apisigniture = '';

    public function __construct() {
        $this->id = 'paypalpro'; //ID needs to be ALL lowercase or it doens't work
        $this->GATEWAYNAME = 'PayPal-Pro';
        $this->method_title = 'PayPal-Pro';
        $this->has_fields = true;

        $this->description = '';
        //$this->usesandboxapi = strcmp($this->settings['debug'], 'yes') == 0;
        $this->usesandboxapi = (get_option('_paypal_mode') == '1') ? TRUE : FALSE;
        $this->securitycodehint = strcmp($this->settings['securitycodehint'], 'yes') == 0;
        //If the field is populated, it will grab the value from there and will not be translated.  If it is empty, it will use the default and translate that value
        //$this->title = strlen($this->settings['title']) > 0 ? $this->settings['title'] : __('Credit Card Payment', 'woocommerce');
//        $this->apiusername = $this->settings['paypalapiusername'];
//        $this->apipassword = $this->settings['paypalapipassword'];
//        $this->apisigniture = $this->settings['paypalapisigniture'];        
        $this->apiusername = get_option('_paypal_api_username');
        $this->apipassword = get_option('_paypal_api_password');
        $this->apisigniture = get_option('_paypal_api_signature');
    }

    public function process_payment($paymentData) {
        $gatewayRequestData = $this->create_paypal_request($paymentData);
        
        if ($gatewayRequestData) {
            $responseData = $this->verify_paypal_payment($gatewayRequestData);
            return $responseData;
        } else {
            $this->mark_as_failed_payment();
        }
    }

    /*
     * Set the HTTP version for the remote posts
     * https://developer.wordpress.org/reference/hooks/http_request_version/
     */

    public function use_http_1_1($httpversion) {
        return '1.1';
    }

    protected function mark_as_failed_payment() {
        return sprintf("Paypal Credit Card Payment Failed with message: '%s'", $this->transactionErrorMessage);
    }

    protected function verify_paypal_payment($gatewayRequestData) {
        $erroMessage = "";
        $api_url = $this->usesandboxapi ? $this->PAYPAL_NVP_SIG_SANDBOX : $this->PAYPAL_NVP_SIG_LIVE;

        $request = array(
            'method' => 'POST',
            'timeout' => 45,
            'blocking' => true,
            'sslverify' => $this->usesandboxapi ? false : true,
            'body' => $gatewayRequestData
        );

        $response = wp_remote_post($api_url, $request);
        if (!is_wp_error($response)) {
            $parsedResponse = $this->parse_paypal_response($response);
            
            if (array_key_exists('ACK', $parsedResponse)) {
                switch ($parsedResponse['ACK']) {
                    case 'Success':
                        $this->transactionId = $parsedResponse['TRANSACTIONID'];
                        $responseVal['msg'] = 'success';
                        $responseVal['transaction_id'] = $parsedResponse['TRANSACTIONID'];
                        return $responseVal;
                        break;
                    case 'SuccessWithWarning':
                        $this->transactionId = $parsedResponse['TRANSACTIONID'];
                        $responseVal['msg'] = 'success-warning';
                        $responseVal['transaction_id'] = $parsedResponse['TRANSACTIONID'];
                        return $responseVal;
                        break;
                    default:
                        $this->transactionErrorMessage = $erroMessage = $parsedResponse['L_LONGMESSAGE0'];
                        $responseVal['msg'] = $erroMessage;
                        $responseVal['transaction_id'] = '';
                        return $responseVal;
                        break;
                }
            }
        } else {
            // Uncomment to view the http error
            //$erroMessage = print_r($response->errors, true);
            $erroMessage = 'Something went wrong while performing your request. Please contact website administrator to report this problem.';
            $responseVal['msg'] = $erroMessage;
            $responseVal['transaction_id'] = '';
            return $responseVal;
        }
    }

    protected function parse_paypal_response($response) {
        $result = array();
        $enteries = explode('&', $response['body']);

        foreach ($enteries as $nvp) {
            $pair = explode('=', $nvp);
            if (count($pair) > 1)
                $result[urldecode($pair[0])] = urldecode($pair[1]);
        }

        return $result;
    }

    protected function create_paypal_request($paymentData) {

        return array(
            'PAYMENTACTION' => $paymentData['PAYMENTACTION'],
            'VERSION' => $paymentData['VERSION'],
            'METHOD' => $paymentData['METHOD'],
            'PWD' => $this->apipassword,
            'USER' => $this->apiusername,
            'SIGNATURE' => $this->apisigniture,
            'AMT' => $paymentData['AMT'],
            'FIRSTNAME' => $paymentData['FIRSTNAME'],
            'LASTNAME' => $paymentData['LASTNAME'],
            'CITY' => $paymentData['CITY'],
            'STATE' => $paymentData['STATE'],
            'ZIP' => $paymentData['ZIP'],
            'COUNTRYCODE' => $paymentData['COUNTRYCODE'],
            'IPADDRESS' => $paymentData['IPADDRESS'],
            'CREDITCARDTYPE' => $paymentData['CREDITCARDTYPE'],
            'ACCT' => $paymentData['ACCT'],
            'CVV2' => $paymentData['CVV2'],
            'EXPDATE' => $paymentData['EXPDATE'],
            'STREET' => $paymentData['STREET'],
            'CURRENCYCODE' => $paymentData['CURRENCYCODE'],
            'BUTTONSOURCE' => 'TipsandTricks_SP',
        );
    }

}

//End of class