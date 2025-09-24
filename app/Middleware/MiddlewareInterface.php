<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

interface MiddlewareInterface
{
    /**
     * Process the request and return a response or null to continue
     */
    public function handle(Request $request): ?Response;
}