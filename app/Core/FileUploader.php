<?php
namespace App\Core;

class FileUploader
{
    protected string $uploadPath;
    protected array $allowedTypes = [];
    protected int $maxFileSize = 0;
    protected bool $overwrite = false;
    protected array $errors = [];

    public function __construct(string $uploadPath = null)
    {
        $this->uploadPath = $uploadPath ?? dirname(__DIR__, 2) . '/storage/uploads';
        
        // Ensure upload directory exists
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
    }

    /**
     * Set allowed file types
     */
    public function setAllowedTypes(array $types): self
    {
        $this->allowedTypes = $types;
        return $this;
    }

    /**
     * Set maximum file size (in bytes)
     */
    public function setMaxFileSize(int $size): self
    {
        $this->maxFileSize = $size;
        return $this;
    }

    /**
     * Set whether to overwrite existing files
     */
    public function setOverwrite(bool $overwrite): self
    {
        $this->overwrite = $overwrite;
        return $this;
    }

    /**
     * Upload a file
     */
    public function upload(array $file, string $name = null): ?string
    {
        $this->errors = [];
        
        // Validate file
        if (!$this->validateFile($file)) {
            return null;
        }
        
        // Generate filename
        $filename = $name ?? $this->generateFilename($file['name']);
        
        // Full path
        $destination = $this->uploadPath . '/' . $filename;
        
        // Check if file already exists
        if (file_exists($destination) && !$this->overwrite) {
            $filename = $this->generateUniqueFilename($filename);
            $destination = $this->uploadPath . '/' . $filename;
        }
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $filename;
        }
        
        $this->errors[] = 'Failed to move uploaded file';
        return null;
    }

    /**
     * Upload multiple files
     */
    public function uploadMultiple(array $files, array $names = null): array
    {
        $uploadedFiles = [];
        
        foreach ($files as $index => $file) {
            $name = $names[$index] ?? null;
            $filename = $this->upload($file, $name);
            
            if ($filename) {
                $uploadedFiles[] = $filename;
            }
        }
        
        return $uploadedFiles;
    }

    /**
     * Validate file
     */
    protected function validateFile(array $file): bool
    {
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->getUploadErrorMessage($file['error']);
            return false;
        }
        
        // Check file size
        if ($this->maxFileSize > 0 && $file['size'] > $this->maxFileSize) {
            $this->errors[] = 'File size exceeds maximum allowed size';
            return false;
        }
        
        // Check file type
        if (!empty($this->allowedTypes)) {
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $this->allowedTypes)) {
                $this->errors[] = 'File type not allowed';
                return false;
            }
        }
        
        return true;
    }

    /**
     * Generate filename
     */
    protected function generateFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $basename = pathinfo($originalName, PATHINFO_FILENAME);
        
        // Sanitize basename
        $basename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $basename);
        
        return $basename . '.' . $extension;
    }

    /**
     * Generate unique filename
     */
    protected function generateUniqueFilename(string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $basename = pathinfo($filename, PATHINFO_FILENAME);
        
        $counter = 1;
        $newFilename = $filename;
        
        while (file_exists($this->uploadPath . '/' . $newFilename)) {
            $newFilename = $basename . '_' . $counter . '.' . $extension;
            $counter++;
        }
        
        return $newFilename;
    }

    /**
     * Get upload error message
     */
    protected function getUploadErrorMessage(int $errorCode): string
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
            default:
                return 'Unknown upload error';
        }
    }

    /**
     * Get errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Check if there are errors
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get upload path
     */
    public function getUploadPath(): string
    {
        return $this->uploadPath;
    }

    /**
     * Create a directory for uploaded files
     */
    public function createDirectory(string $directory): bool
    {
        $path = $this->uploadPath . '/' . $directory;
        if (!is_dir($path)) {
            return mkdir($path, 0755, true);
        }
        return true;
    }

    /**
     * Delete an uploaded file
     */
    public function delete(string $filename): bool
    {
        $filepath = $this->uploadPath . '/' . $filename;
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }

    /**
     * Get file information
     */
    public function getFileInfo(string $filename): ?array
    {
        $filepath = $this->uploadPath . '/' . $filename;
        if (!file_exists($filepath)) {
            return null;
        }
        
        return [
            'name' => $filename,
            'size' => filesize($filepath),
            'type' => mime_content_type($filepath),
            'modified' => filemtime($filepath),
            'path' => $filepath
        ];
    }
}