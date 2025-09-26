<?php

namespace App\Core;

class Controller
{
    protected Request $request;
    protected Response $response;
    protected Validator $validator;
    protected string $layout = 'layout';

    public function __construct()
    {
        $this->response = new Response();
        $this->validator = new Validator();
    }

    /**
     * Set the request object
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * Set the layout template
     */
    protected function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    /**
     * Render a view
     */
    protected function render(string $view, array $data = []): Response
    {
        $viewPath = dirname(__DIR__, 2) . '/resources/views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: {$viewPath}");
        }

        // Extract data variables
        extract($data);

        // Start output buffering
        ob_start();
        
        // Include the view file
        include $viewPath;
        
        // Get the view content
        $viewContent = ob_get_clean();
        
        // Check if layout is set in the view
        if ((isset($layout) && $layout !== false) || (!isset($layout) && $this->layout !== false)) {
            $layoutName = $layout ?? $this->layout;
            $layoutPath = dirname(__DIR__, 2) . '/resources/views/' . $layoutName . '.php';
            
            if (file_exists($layoutPath)) {
                // Set content variable for layout
                $content = $viewContent;
                
                // Start new output buffer for layout
                ob_start();
                include $layoutPath;
                $content = ob_get_clean();
                
                return $this->response->setContent($content);
            }
        }
        
        return $this->response->setContent($viewContent);
    }

    /**
     * Return JSON response
     */
    protected function json(array $data, int $statusCode = 200): Response
    {
        return Response::json($data, $statusCode);
    }

    /**
     * Redirect to a URL
     */
    protected function redirect(string $url): Response
    {
        return Response::redirect($url);
    }

    /**
     * Get request input
     */
    protected function input(string $key, $default = null)
    {
        $method = $this->request->getMethod();
        
        if ($method === 'POST') {
            return $this->request->post($key, $default);
        }
        
        return $this->request->get($key, $default);
    }

    /**
     * Validate request data
     */
    protected function validate(array $rules): bool
    {
        // Get all POST data
        $postData = [];
        foreach ($rules as $field => $rule) {
            $postData[$field] = $this->input($field);
        }
        
        $this->validator->setData($postData)->setRules($rules);
        return $this->validator->validate();
    }

    /**
     * Get validation errors
     */
    protected function errors(): array
    {
        return $this->validator->errors();
    }

    /**
     * Get the first error for a field
     */
    protected function firstError(string $field): ?string
    {
        return $this->validator->firstError($field);
    }
}