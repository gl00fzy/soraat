<?php
require_once 'db.php';

echo "=== Products in DB (latest 5) ===\n";
$rows = $pdo->query('SELECT id, name, image FROM products ORDER BY id DESC LIMIT 5')->fetchAll();
foreach ($rows as $r) {
    echo "ID: " . $r['id'] . " | Name: " . $r['name'] . " | Image: [" . $r['image'] . "]\n";
    
    // Check if file exists
    if (!empty($r['image'])) {
        $full_path = __DIR__ . '/' . $r['image'];
        echo "  -> Full path: " . $full_path . "\n";
        echo "  -> File exists: " . (file_exists($full_path) ? 'YES' : 'NO') . "\n";
    } else {
        echo "  -> No image path stored\n";
    }
}

echo "\n=== Files in uploads/ directory ===\n";
$uploads_dir = __DIR__ . '/uploads';
if (is_dir($uploads_dir)) {
    $files = scandir($uploads_dir);
    $files = array_diff($files, ['.', '..']);
    if (empty($files)) {
        echo "Directory is EMPTY\n";
    } else {
        foreach ($files as $f) {
            echo "  " . $f . " (" . filesize($uploads_dir . '/' . $f) . " bytes)\n";
        }
    }
} else {
    echo "uploads/ directory does NOT exist!\n";
}

echo "\n=== Checking admin uploads path ===\n";
$admin_uploads = __DIR__ . '/admin/../uploads';
echo "admin/../uploads resolves to: " . realpath($admin_uploads) . "\n";
echo "Exists: " . (is_dir($admin_uploads) ? 'YES' : 'NO') . "\n";
?>
