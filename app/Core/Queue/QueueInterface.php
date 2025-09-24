<?php

namespace App\Core\Queue;

interface QueueInterface
{
    /**
     * Push a job onto the queue
     */
    public function push(string $job, array $data = []): bool;

    /**
     * Pop a job from the queue
     */
    public function pop(): ?array;

    /**
     * Mark a job as completed
     */
    public function complete(string $jobId): bool;

    /**
     * Mark a job as failed
     */
    public function fail(string $jobId): bool;

    /**
     * Get the number of jobs in the queue
     */
    public function size(): int;

    /**
     * Clear all jobs from the queue
     */
    public function clear(): bool;
}