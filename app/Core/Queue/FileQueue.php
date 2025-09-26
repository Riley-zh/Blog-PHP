<?php

namespace App\Core\Queue;

class FileQueue implements QueueInterface
{
    private string $queueDir;
    private string $queueName;

    public function __construct(?string $queueDir = null, string $queueName = 'default')
    {
        $this->queueDir = $queueDir ?? dirname(__DIR__, 3) . '/storage/queues';
        $this->queueName = $queueName;
        
        // Ensure queue directory exists
        $queuePath = $this->queueDir . '/' . $this->queueName;
        if (!is_dir($queuePath)) {
            mkdir($queuePath, 0755, true);
        }
    }

    /**
     * Push a job onto the queue
     */
    public function push(string $job, array $data = []): bool
    {
        $jobId = uniqid($this->queueName . '_', true);
        $createdAt = time();
        
        $jobData = [
            'id' => $jobId,
            'job' => $job,
            'data' => $data,
            'created_at' => $createdAt,
            'attempts' => 0
        ];
        
        $filePath = $this->getJobFilePath($jobId);
        
        return file_put_contents($filePath, serialize($jobData)) !== false;
    }

    /**
     * Pop a job from the queue
     */
    public function pop(): ?array
    {
        $queuePath = $this->queueDir . '/' . $this->queueName;
        
        if (!is_dir($queuePath)) {
            return null;
        }
        
        // Get all job files
        $files = glob($queuePath . '/*.job');
        
        if (empty($files)) {
            return null;
        }
        
        // Sort by creation time (oldest first)
        usort($files, function($a, $b) {
            return filemtime($a) <=> filemtime($b);
        });
        
        // Get the oldest job
        $jobFile = $files[0];
        $jobData = unserialize(file_get_contents($jobFile));
        
        // Increment attempts
        $jobData['attempts']++;
        
        // Update the job file
        file_put_contents($jobFile, serialize($jobData));
        
        return $jobData;
    }

    /**
     * Mark a job as completed
     */
    public function complete(string $jobId): bool
    {
        $filePath = $this->getJobFilePath($jobId);
        
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        
        return false;
    }

    /**
     * Mark a job as failed
     */
    public function fail(string $jobId): bool
    {
        $filePath = $this->getJobFilePath($jobId);
        $failedPath = $this->queueDir . '/' . $this->queueName . '/failed/' . basename($filePath);
        
        // Ensure failed directory exists
        $failedDir = dirname($failedPath);
        if (!is_dir($failedDir)) {
            mkdir($failedDir, 0755, true);
        }
        
        if (file_exists($filePath)) {
            return rename($filePath, $failedPath);
        }
        
        return false;
    }

    /**
     * Get the number of jobs in the queue
     */
    public function size(): int
    {
        $queuePath = $this->queueDir . '/' . $this->queueName;
        
        if (!is_dir($queuePath)) {
            return 0;
        }
        
        $files = glob($queuePath . '/*.job');
        return count($files);
    }

    /**
     * Clear all jobs from the queue
     */
    public function clear(): bool
    {
        $queuePath = $this->queueDir . '/' . $this->queueName;
        
        if (!is_dir($queuePath)) {
            return true;
        }
        
        $files = glob($queuePath . '/*.job');
        
        foreach ($files as $file) {
            unlink($file);
        }
        
        return true;
    }

    /**
     * Get the file path for a job
     */
    private function getJobFilePath(string $jobId): string
    {
        return $this->queueDir . '/' . $this->queueName . '/' . $jobId . '.job';
    }
}