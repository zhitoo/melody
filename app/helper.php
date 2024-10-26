<?php

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        $public_path = PUBLIC_PATH;
        return str_replace(DIRECTORY_SEPARATOR . 'public', '', $public_path) . DIRECTORY_SEPARATOR . $path;
    }
}

if (!function_exists('public_path')) {
    function public_path(string $path = ''): string
    {
        return PUBLIC_PATH;
    }
}

if (!function_exists('config')) {
    function config(string $key = 'app', $default = null): mixed
    {
        $key_parts = explode('.', $key);
        $config_file = base_path('configs' . DIRECTORY_SEPARATOR . $key_parts[0] . '.php');
        if (!file_exists($config_file)) {
            return null ?? $default;
        }
        $configs = include $config_file;
        unset($key_parts[0]);
        $value = null;
        foreach ($key_parts as $key_part) {
            $value = isset($configs[$key_part]) ? $configs[$key_part] : null;
        }

        return $value ?? $default;
    }

}

if (!function_exists('abort')) {
    function abort(int $status_code=500,string $message=null): void{
        http_response_code($status_code);
        die($message);
    }
}
