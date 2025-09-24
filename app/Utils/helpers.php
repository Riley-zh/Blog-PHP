<?php

if (!function_exists('csrf_field')) {
    /**
     * Generate a CSRF token form field
     */
    function csrf_field(): string
    {
        $token = \App\Middleware\CsrfMiddleware::generateToken();
        return '<input type="hidden" name="_token" value="' . $token . '">';
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get the current CSRF token
     */
    function csrf_token(): string
    {
        return \App\Middleware\CsrfMiddleware::generateToken();
    }
}