<?php

if (!function_exists('getBearer')){

    function getBearer(): string
    {
        $bearer = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        return str_replace("Bearer ", "", $bearer);
    }
}