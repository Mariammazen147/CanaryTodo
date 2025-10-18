<?php
$toggle = include 'toggle.php'; // Get toggle data
$isCanary = $toggle['isCanary'];
$userId = $toggle['userId'];
$bucket = $toggle['bucket'];

// Todo list logic
if (!isset($_SESSION['todos'])) $_SESSION['todos'] = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['todo'])) {
        $_SESSION['todos'][] = $_POST['todo'];
    } elseif (isset($_POST['clear'])) {
        $_SESSION['todos'] = [];
    }
}

// UI decision
$stylesheet = $isCanary ? 'style-new.css' : 'style-old.css';
$featureText = $isCanary ? 'New Redesigned UI - Todo List' : 'Old Classic UI - Todo List';

// Log for monitoring
$logEntry = date('Y-m-d H:i:s') . " | User: $userId | Bucket: $bucket | UI: $featureText\n";
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
    <title>Feature Toggle Demo</title>
    <link rel="stylesheet" href="<?php echo $stylesheet; ?>">
</head>
<body>
    <div class="container">
        <h1><?php echo $featureText; ?></h1>
        <form method="POST">
            <input type="text" name="todo" placeholder="Add todo..." required>
            <button type="submit">Add</button>
        </form>
        
        <?php if ($isCanary): ?>
            <div class="search-container">
                <input type="text" id="search" placeholder="Search todos..." onkeyup="searchTodos()">
            </div>
        <?php endif; ?>
        
        <ul id="todoList">
            <?php foreach ($_SESSION['todos'] as $todo): ?>
                <li><?php echo htmlspecialchars($todo); ?></li>
            <?php endforeach; ?>
        </ul>
        
        <?php if ($isCanary): ?>
            <form method="POST">
                <button type="submit" name="clear">Clear All</button>
            </form>
            <hr>
            <p><a href="verify.php">Verify <?php echo CANARY_PERCENTAGE; ?>% Rollout</a></p>
            <a href="?force=new">Force New UI</a> | <a href="?force=old">Force Old UI</a> | <a href=".">Reset</a>
        <?php else: ?>
            <hr>
            <p><a href="verify.php">Verify <?php echo CANARY_PERCENTAGE; ?>% Rollout</a></p>
        <?php endif; ?>
    </div>

    <script>
        console.log('Debug: Your bucket is <?php echo $bucket; ?>. Canary if < <?php echo CANARY_PERCENTAGE; ?>.');
        console.log('User ID (hashed session): <?php echo substr($userId, 0, 8); ?>...');
        <?php if ($isCanary): ?>
            function searchTodos() {
                const input = document.getElementById('search').value.toLowerCase();
                const todos = document.querySelectorAll('#todoList li');
                todos.forEach(todo => {
                    const text = todo.textContent.toLowerCase();
                    todo.style.display = text.includes(input) ? '' : 'none';
                });
            }
        <?php endif; ?>
    </script>
</body>
</html>