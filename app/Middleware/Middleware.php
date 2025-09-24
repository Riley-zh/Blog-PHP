<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

abstract class Middleware implements MiddlewareInterface
{
    /**
     * Process the request and return a response or null to continue
     */
    abstract public function handle(Request $request): ?Response;
}