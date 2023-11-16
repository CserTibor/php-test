<?php

if (!function_exists('jsonResponse')) {
    function jsonResponse($data, $status = 200, $headers = []): int
    {
        header('Content-Type: application/json; charset=utf-8');

        foreach ($headers as $header) {
            header($header);
        }
        http_response_code($status);
        echo json_encode($data);

        return 1;
    }
}

if (!function_exists('getBearer')){

    function getBearer(): string
    {
        $bearer = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        return str_replace("Bearer ", "", $bearer);
    }
}