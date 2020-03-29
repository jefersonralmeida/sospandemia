<?php

if (!function_exists('uiRoute')) {
    function uiRoute(?string $route = null): string
    {
        $protocol = $_SERVER['REQUEST_SCHEME'];
        $host = preg_replace('/:[0-9]+$/', '', $_SERVER['HTTP_HOST']);
        $port = env('APP_UI_PORT') ?? $_SERVER['SERVER_PORT'] ?? '80';
        return "$protocol://$host:$port" . ($route !== null ? "/$route" : "");
    }
}
