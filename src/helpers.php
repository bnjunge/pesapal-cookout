<?php

namespace BNjunge\PesapalCookout;

function jsonResponse() {
    header('content-type: application/json');
    echo json_encode(func_get_arg(0));
    exit;
}