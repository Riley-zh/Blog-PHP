<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Response;

abstract class ApiController extends Controller
{
    /**
     * Send a JSON response
     */
    protected function send(array $data, int $statusCode = 200): Response
    {
        return Response::json($data, $statusCode);
    }

    /**
     * Send a success response
     */
    protected function success(string $message = 'Success', array $data = [], int $statusCode = 200): Response
    {
        return $this->send([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Send an error response
     */
    protected function error(string $message = 'Error', array $errors = [], int $statusCode = 400): Response
    {
        return $this->send([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }

    /**
     * Send a not found response
     */
    protected function notFound(string $message = 'Resource not found'): Response
    {
        return $this->error($message, [], 404);
    }

    /**
     * Send a validation error response
     */
    protected function validationError(array $errors): Response
    {
        return $this->error('Validation failed', $errors, 422);
    }
}