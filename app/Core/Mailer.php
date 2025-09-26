<?php

namespace App\Core;

class Mailer
{
    protected array $config;
    protected array $to = [];
    protected array $cc = [];
    protected array $bcc = [];
    protected string $subject = '';
    protected string $body = '';
    protected string $altBody = '';
    protected array $attachments = [];
    protected array $headers = [];
    protected string $from = '';
    protected string $fromName = '';

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->from = $config['from'] ?? '';
        $this->fromName = $config['from_name'] ?? '';
    }

    /**
     * Set the sender
     */
    public function from(string $email, string $name = ''): self
    {
        $this->from = $email;
        $this->fromName = $name;
        return $this;
    }

    /**
     * Add a recipient
     */
    public function to(string $email, string $name = ''): self
    {
        $this->to[] = ['email' => $email, 'name' => $name];
        return $this;
    }

    /**
     * Add a CC recipient
     */
    public function cc(string $email, string $name = ''): self
    {
        $this->cc[] = ['email' => $email, 'name' => $name];
        return $this;
    }

    /**
     * Add a BCC recipient
     */
    public function bcc(string $email, string $name = ''): self
    {
        $this->bcc[] = ['email' => $email, 'name' => $name];
        return $this;
    }

    /**
     * Set the subject
     */
    public function subject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Set the HTML body
     */
    public function body(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Set the plain text body
     */
    public function altBody(string $altBody): self
    {
        $this->altBody = $altBody;
        return $this;
    }

    /**
     * Add an attachment
     */
    public function attach(string $path, string $name = ''): self
    {
        if (file_exists($path)) {
            $this->attachments[] = ['path' => $path, 'name' => $name ?: basename($path)];
        }
        return $this;
    }

    /**
     * Add a custom header
     */
    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Send the email
     */
    public function send(): bool
    {
        // Validate required fields
        if (empty($this->to) || empty($this->subject) || (empty($this->body) && empty($this->altBody))) {
            return false;
        }

        // Use PHP's mail() function as fallback
        return $this->sendWithMail();
    }

    /**
     * Send email using PHP's mail() function
     */
    protected function sendWithMail(): bool
    {
        // Prepare headers
        $headers = [];
        
        // From
        if (!empty($this->from)) {
            $from = !empty($this->fromName) ? "\"{$this->fromName}\" <{$this->from}>" : $this->from;
            $headers[] = "From: {$from}";
        }
        
        // To
        $to = $this->formatAddresses($this->to);
        
        // CC
        if (!empty($this->cc)) {
            $headers[] = "CC: " . $this->formatAddresses($this->cc);
        }
        
        // BCC
        if (!empty($this->bcc)) {
            $headers[] = "BCC: " . $this->formatAddresses($this->bcc);
        }
        
        // Content type
        $headers[] = "MIME-Version: 1.0";
        
        // Handle attachments
        if (!empty($this->attachments)) {
            return $this->sendWithAttachments($to, $headers);
        } else {
            // Simple email without attachments
            $headers[] = "Content-Type: text/html; charset=UTF-8";
            
            return mail($to, $this->subject, $this->body, implode("\r\n", $headers));
        }
    }

    /**
     * Send email with attachments
     */
    protected function sendWithAttachments(string $to, array $headers): bool
    {
    // Generate boundary
    $boundary = md5((string) time());
        
        // Update content type header
        $headers[] = "Content-Type: multipart/mixed; boundary=\"{$boundary}\"";
        
        // Build message body
        $message = "--{$boundary}\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\r\n";
        $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $message .= $this->body . "\r\n";
        
        // Add attachments
        foreach ($this->attachments as $attachment) {
            $message .= "--{$boundary}\r\n";
            $message .= "Content-Type: application/octet-stream; name=\"{$attachment['name']}\"\r\n";
            $message .= "Content-Transfer-Encoding: base64\r\n";
            $message .= "Content-Disposition: attachment; filename=\"{$attachment['name']}\"\r\n\r\n";
            $message .= chunk_split(base64_encode(file_get_contents($attachment['path']))) . "\r\n";
        }
        
        $message .= "--{$boundary}--";
        
        return mail($to, $this->subject, $message, implode("\r\n", $headers));
    }

    /**
     * Format email addresses
     */
    protected function formatAddresses(array $addresses): string
    {
        $formatted = [];
        
        foreach ($addresses as $address) {
            if (!empty($address['name'])) {
                $formatted[] = "\"{$address['name']}\" <{$address['email']}>";
            } else {
                $formatted[] = $address['email'];
            }
        }
        
        return implode(', ', $formatted);
    }

    /**
     * Send email using SMTP (placeholder for future implementation)
     */
    public function sendWithSMTP(): bool
    {
        // This is a placeholder for SMTP implementation
        // In a real application, you would use a library like PHPMailer or SwiftMailer
        throw new \Exception('SMTP sending not implemented. Use mail() function or implement SMTP.');
    }

    /**
     * Send email asynchronously using the queue system
     */
    public function queue(): bool
    {
        // This would integrate with the queue system
        // For now, we'll just simulate queuing
        global $app;
        
        if (isset($app)) {
            $queue = $app->getService('queue');
            return $queue->push('send_email', [
                'to' => $this->to,
                'cc' => $this->cc,
                'bcc' => $this->bcc,
                'subject' => $this->subject,
                'body' => $this->body,
                'altBody' => $this->altBody,
                'from' => $this->from,
                'fromName' => $this->fromName,
                'attachments' => $this->attachments,
                'headers' => $this->headers
            ]);
        }
        
        return false;
    }

    /**
     * Render a view as the email body
     */
    public function view(string $view, array $data = []): self
    {
        // This is a simplified implementation
        // In a real application, you would use the view rendering system
        $viewPath = dirname(__DIR__, 2) . '/resources/views/emails/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            // Extract data variables
            extract($data);
            
            // Start output buffering
            ob_start();
            include $viewPath;
            $content = ob_get_clean();
            
            $this->body = $content;
        }
        
        return $this;
    }
}