<?php
$toggle = include 'toggle.php'; // Get toggle data
$isCanary = $toggle['isCanary'];
$userId = $toggle['userId'];
$bucket = $toggle['bucket'];

// UI decision
$stylesheet = $isCanary ? 'style-new.css' : 'style-old.css';
$featureText = $isCanary ? 'New Redesigned UI - Verification' : 'Old Classic UI - Verification';

// Simulate 100 users
$numUsers = 100;
$canaryCount = 0;
$userBuckets = [];

for ($i = 0; $i < $numUsers; $i++) {
    $fakeSessionId = md5(uniqid(rand(), true));
    $hash = hexdec(substr($fakeSessionId, 0, 8));
    $bucket = $hash % 100;
    $userBuckets[] = [
        'userId' => $fakeSessionId,
        'bucket' => $bucket,
        'isCanary' => $bucket < CANARY_PERCENTAGE
    ];
    if ($bucket < CANARY_PERCENTAGE) {
        $canaryCount++;
    }
}

$percentage = ($canaryCount / $numUsers) * 100;

// Log simulation
$logEntry = date('Y-m-d H:i:s') . " | User: $userId | Bucket: $bucket | UI: $featureText | Simulated $numUsers users | Canary: $canaryCount ($percentage%)\n";
file_put_contents('access.log', $logEntry, FILE_APPEND);

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canary Rollout Verification</title>
    <link rel="stylesheet" href="<?php echo $stylesheet; ?>">
</head>
<body>
    <div class="container">
        <h1><?php echo $featureText; ?></h1>
        <p>Simulated <?php echo $numUsers; ?> users. <?php echo $canaryCount; ?> (<?php echo number_format($percentage, 1); ?>%) got the new UI.</p>
        <table>
            <tr>
                <th>User ID (Hashed)</th>
                <th>Bucket</th>
                <th>UI Assigned</th>
            </tr>
            <?php foreach ($userBuckets as $user): ?>
                <tr class="<?php echo $user['isCanary'] ? 'canary' : ''; ?>">
                    <td><?php echo substr($user['userId'], 0, 8); ?>...</td>
                    <td><?php echo $user['bucket']; ?></td>
                    <td><?php echo $user['isCanary'] ? 'New UI' : 'Old UI'; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php if ($isCanary): ?>
            <p><a href="index.php">Back to Todo List</a></p>
            <a href="?force=new">Force New UI</a> | <a href="?force=old">Force Old UI</a> | <a href="verify.php">Reset</a>
        <?php else: ?>
            <p><a href="index.php">Back to Todo List</a></p>
        <?php endif; ?>
    </div>
    <script>
        console.log('Debug: Your bucket is <?php echo $bucket; ?>. Canary if < <?php echo CANARY_PERCENTAGE; ?>.');
        console.log('User ID (hashed session): <?php echo substr($userId, 0, 8); ?>...');
    </script>
</body>
</html>