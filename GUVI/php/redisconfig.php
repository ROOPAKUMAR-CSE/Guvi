
<?php
require 'vendor/autoload.php';

use Predis\Client;


// Include Redis session handling (replace with actual Redis session handling code)


// Replace these values with your actual Redis connection details
$redisUri = 'tcp://localhost:6379';
$redisPassword = null; // Set to null if Redis has no password

// Connect to Redis
$redisClient = new Client([
    'scheme' => 'tcp',
    'host'   => 'localhost',
    'port'   => 6379,
    'password' => $redisPassword,
]);

// Start Redis session
session_set_save_handler(
    new \SessionHandler\RedisSessionHandler($redisClient, [
        'gc_maxlifetime' => 60 * 60 * 24, // Session lifetime in seconds (e.g., 24 hours)
    ])
);

// Start or resume the session
session_start();
?>
