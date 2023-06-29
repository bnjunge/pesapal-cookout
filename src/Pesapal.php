<?php

namespace BNjunge\PesapalCookout;
use function BNjunge\PesapalCookout\_config;

class Pesapal
{
    protected static $pesapalBaseUrl = "https://pay.pesapal.com/v3";
    protected static $body;
    protected static $headers;
    protected static $response;
    protected static $options;
    protected static $manager;

    public static function config()
    {
        return _config();
    }

    public static function pesapalAuth()
    {
        $config = self::config();
        self::$options = $config;

        $url = self::$pesapalBaseUrl . "/api/Auth/RequestToken";
        $headers = array("Content-Type" => "application/json", 'accept' => 'application/json');
        $body = json_encode(array(
            'consumer_key' => $config->pesapalConsumerKey,
            'consumer_secret' => $config->pesapalConsumerSecret,
        ));

        $data = Curl::PostToken($url, $headers, $body);
        $data = json_decode(json_encode($data));

        // jsonResponse($data);
        return $data;
    }

    public static function pesapalRegisterIPN($url)
    {
        $token = self::pesapalAuth();

        if(!$token->success) {
            jsonResponse(['success' => false, 'message' => 'Failed to obtain Token', 'response' => $token]);
        }

        $url = self::$pesapalBaseUrl . "/api/URLSetup/RegisterIPN";
        $headers = array("Content-Type" => "application/json", 'accept' => 'application/json', 'Authorization' => 'Bearer ' . $token->message->token);
        // $url = $_SERVER['HTTP_HOST'];

        $body = json_encode(array(
            "url" => "https://{$url}/pesapal/callback",
            "ipn_notification_type" => 'POST',
        ));

        $data = Curl::Post($url, $headers, $body);
        $data = json_decode(json_encode($data));


        return $data;
    }

    public static function listIPNS()
    {
        $token = self::pesapalAuth();

        if(!$token->success) {
            jsonResponse(['success' => false, 'message' => 'Failed to obtain Token']);
        }

        $url = self::$pesapalBaseUrl . "/api/URLSetup/GetIpnList";
        $headers = array("Content-Type" => "application/json", 'accept' => 'application/json', 'Authorization' => 'Bearer ' . $token->message->token);

        $data = Curl::Get($url, $headers);
        $data = json_decode(json_encode($data));

        $data;
    }

    public static function orderProcess($amount, $phone, $callback, $updatePesapalIPNID)
    {
        $token = self::pesapalAuth();
        $supportedCurrencies = strtoupper(self::$options->businessCurrency);

        $payload = json_encode(array(
            'id' => rand(0, 9999999999),
            'currency' => 'KES',
            'amount' => $amount,
            'description' => 'testApi',
            'redirect_mode' => 'PARENT_WINDOW',
            'callback_url' => $callback,
            'notification_id' => $updatePesapalIPNID,
            'billing_address' => array(
                'phone_number' => $phone
            )
        ));

        if(!$token->success) {
            return ['success' => false, 'message' => 'Failed to obtain Token'];
        }

        $url = self::$pesapalBaseUrl . "/api/Transactions/SubmitOrderRequest";
        $headers = array("Content-Type" => "application/json", 'accept' => 'application/json', 'Authorization' => 'Bearer ' . $token->message->token);
        $data = Curl::Post($url, $headers, $payload);

        $data = json_decode(json_encode($data));

        return $data;
    }

    public static function transactionStatus()
    {
        $transId = $_GET['OrderTrackingId'];
        $merchant = $_GET['OrderMerchantReference'];

        if(!isset($transId) || empty($transId)) {
            jsonResponse(['success' => false, 'message' => 'Missing Transaction ID']);
        }

        $token = self::pesapalAuth();
        if(!$token->success) {
            jsonResponse(['success' => false, 'message' => 'Failed to obtain Token']);
        }

        $url = self::$pesapalBaseUrl . "/api/Transactions/GetTransactionStatus?orderTrackingId={$transId}";
        $headers = array("Content-Type" => "application/json", 'accept' => 'application/json', 'Authorization' => 'Bearer ' . $token->message->token);
        $data = Curl::Get($url, $headers);

        $data = json_decode(json_encode($data));

        return $data;
    }


}
