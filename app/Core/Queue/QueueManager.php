<?php

namespace App\Core\Queue;

class QueueManager
{
    protected string $queuePath;
    protected string $defaultQueue;

    public function __construct(?string $queuePath = null, ?string $defaultQueue = null)
    {
        $this->queuePath = $queuePath ?? dirname(__DIR__, 2) . '/storage/queues';
        $this->defaultQueue = $defaultQueue ?? 'default';
        
        // Create queue directory if it doesn't exist
        if (!is_dir($this->queuePath)) {
            mkdir($this->queuePath, 0755, true);
        }
    }

    /**
     * Get a queue instance
     */
    public function getQueue(?string $name = null): FileQueue
    {
        $name = $name ?? $this->defaultQueue;
        return new FileQueue($this->queuePath, $name);
    }

    /**
     * Push a job to the queue
     */
    public function push(object $job, ?string $queue = null): bool
    {
        $queue = $queue ?? $this->defaultQueue;
        return $this->getQueue($queue)->push(serialize($job));
    }

    /**
     * Pop a job from the queue
     */
    public function pop(?string $queue = null): ?object
    {
        $queue = $queue ?? $this->defaultQueue;
        $jobData = $this->getQueue($queue)->pop();
        
        if ($jobData) {
            return unserialize($jobData['job']);
        }
        
        return null;
    }

    /**
     * Mark a job as completed
     */
    public function complete(object $job, ?string $queue = null): bool
    {
        $queue = $queue ?? $this->defaultQueue;
        // In a real implementation, you would track job IDs
        // For now, we'll just return true
        return true;
    }

    /**
     * Mark a job as failed
     */
    public function fail(object $job, ?string $queue = null): bool
    {
        $queue = $queue ?? $this->defaultQueue;
        // In a real implementation, you would track job IDs
        // For now, we'll just return true
        return true;
    }

    /**
     * Get the number of jobs in the queue
     */
    public function size(?string $queue = null): int
    {
        $queue = $queue ?? $this->defaultQueue;
        return $this->getQueue($queue)->size();
    }

    /**
     * Clear all jobs from the queue
     */
    public function clear(?string $queue = null): bool
    {
        $queue = $queue ?? $this->defaultQueue;
        return $this->getQueue($queue)->clear();
    }
}