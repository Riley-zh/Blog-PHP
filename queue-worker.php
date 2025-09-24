<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\App;
use App\Core\Queue\QueueManager;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Initialize the application
$app = new App();

// Get the queue manager
/** @var QueueManager $queueManager */
$queueManager = $app->getService('queue');

echo "Queue worker started. Press Ctrl+C to exit.\n";

while (true) {
    // Pop a job from the queue
    $job = $queueManager->pop();
    
    if ($job) {
        echo "Processing job: {$job['job']}\n";
        
        try {
            // Process the job
            processJob($job);
            
            // Mark job as completed
            $queueManager->complete($job['id']);
            
            echo "Job completed: {$job['job']}\n";
        } catch (Exception $e) {
            echo "Job failed: {$job['job']} - " . $e->getMessage() . "\n";
            
            // Mark job as failed
            $queueManager->fail($job['id']);
        }
    } else {
        // No jobs available, sleep for a bit
        sleep(5);
    }
}

function processJob(array $job)
{
    // This is where you would implement your job processing logic
    // For now, we'll just simulate some work
    switch ($job['job']) {
        case 'send_email':
            // Simulate sending an email
            echo "Sending email to: {$job['data']['to']}\n";
            sleep(2);
            break;
            
        case 'process_image':
            // Simulate image processing
            echo "Processing image: {$job['data']['image']}\n";
            sleep(3);
            break;
            
        default:
            echo "Unknown job type: {$job['job']}\n";
            break;
    }
}