<?php

$uploads_dir = __DIR__ . '/uploads';
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0777, true);
}

// Map of source image paths to destination filenames
$images = [
    'C:\Users\MSI_\.gemini\antigravity\brain\1bfacc19-9aba-4159-9e14-c7aec0675dff\smartwatch_sport_x_1773206198998.png' => 'smartwatch_sport_x.png',
    'C:\Users\MSI_\.gemini\antigravity\brain\1bfacc19-9aba-4159-9e14-c7aec0675dff\black_oversize_tshirt_1773206218674.png' => 'black_oversize_tshirt.png',
    'C:\Users\MSI_\.gemini\antigravity\brain\1bfacc19-9aba-4159-9e14-c7aec0675dff\tumbler_30oz_1773206234143.png' => 'tumbler.png'
];

echo "Copying files...\n";
foreach ($images as $src => $dest) {
    if (file_exists($src)) {
        if (copy($src, $uploads_dir . '/' . $dest)) {
            echo "Copied: $dest\n";
        } else {
            echo "Failed to copy $dest\n";
        }
    } else {
        echo "Source file not found: $src\n";
    }
}

// Update Database
$host = '45.136.255.138';
$db   = 'shop_project';
$user = 'root';
$pass = 'Golf@2004';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    echo "\nConnecting to remote database...\n";
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connected successfully.\n";
    
    $updates = [
        "UPDATE products SET image = 'uploads/smartwatch_sport_x.png' WHERE name LIKE '%สมาร์ทวอทช์ Sport X%'",
        "UPDATE products SET image = 'uploads/black_oversize_tshirt.png' WHERE name LIKE '%เสื้อยืด Oversize สีดำ%'",
        "UPDATE products SET image = 'uploads/tumbler.png' WHERE name LIKE '%แก้วเก็บความเย็น 30oz%'"
    ];
    
    foreach ($updates as $sql) {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        echo "Updated rows: " . $stmt->rowCount() . " for query: $sql\n";
    }
    
    echo "\nDatabase update completed successfully.\n";
    
} catch (\PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}

?>
