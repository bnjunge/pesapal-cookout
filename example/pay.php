<?php

use BNjunge\PesapalCookout\Pesapal;

use function BNjunge\PesapalCookout\jsonResponse;

require_once '../vendor/autoload.php';

// $id = $_GET['Order_Tracking_Id'];

$verify = Pesapal::transactionStatus();

jsonResponse($verify);