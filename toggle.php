<?php
include_once 'config.php'; // Changed from include to include_once
session_start(); // Start session for user ID and todos

// Simulate user with session ID
$userId = md5(session_id());

// Hash for bucketing (0-99)
$hash = hexdec(substr($userId, 0, 8));
$bucket = $hash % 100;

// Feature toggle
$isCanary = ($bucket < CANARY_PERCENTAGE);

// Override for demo
if (isset($_GET['force'])) {
    $isCanary = ($_GET['force'] === 'new');
}

// Return toggle data
return [
    'isCanary' => $isCanary,
    'userId' => $userId,
    'bucket' => $bucket
];
?>