<?php

namespace BNjunge\PesapalCookout;

use stdClass;

function jsonResponse()
{
    // header('content-type: application/json');
    // echo json_encode(func_get_arg(0));
    return func_get_arg(0);
    // exit;
}

function config()
{
    $config = new stdClass();
    $config->pesapalConsumerKey = '';
    $config->pesapalConsumerSecret = '';

    return $config;
}
