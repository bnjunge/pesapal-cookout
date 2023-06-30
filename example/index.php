<?php

use BNjunge\PesapalCookout\Pesapal;

use function BNjunge\PesapalCookout\jsonResponse;

require_once '../vendor/autoload.php';

# Get token
// $token = Pesapal::pesapalAuth();
// jsonResponse($token);

# Register IPN
// $url = "https://6e0c-196-98-165-213.ngrok-free.app";
// $registerIPN = Pesapal::pesapalRegisterIPN($url);
// jsonResponse($registerIPN);


# List IPN
// $ipns = Pesapal::listIPNS();
// jsonResponse($ipns);

# Initiate Payment Process
$amount = 2;
$phone = '';
$validation_callback = '';
$ipnId = '';

$payRequest = Pesapal::orderProcess($amount, $phone, $validation_callback, $ipnId);
jsonResponse($payRequest);

# Validate Payment
// $ipnId = "09181b33-aca7-4781-99ef-de7cfabf79f5";

# Notes