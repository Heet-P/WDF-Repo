<?php
// Simple validation script to check for PHP syntax errors
$files = [
    'config.php',
    'login.php', 
    'register.php',
    'dashboard.php',
    'logout.php',
    'index.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $output = shell_exec("php -l $file 2>&1");
        echo "Checking $file: ";
        if (strpos($output, 'No syntax errors') !== false) {
            echo "✅ OK\n";
        } else {
            echo "❌ ERROR\n";
            echo $output . "\n";
        }
    } else {
        echo "❌ File $file not found\n";
    }
}

echo "\nValidation complete!\n";
?>
