<?php

namespace App\Views;

use JetBrains\PhpStorm\NoReturn;

class JsonResponse
{
    public static function response($data, $status = 200, $headers = [])
    {
        header('Content-Type: application/json; charset=utf-8');

        foreach ($headers as $header) {
            header($header);
        }
        http_response_code($status);
        echo json_encode($data);

        exit($status);
    }
}