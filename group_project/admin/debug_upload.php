<?php
require_once '../db.php';

// Check admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { 
    die("Not admin"); 
}

echo "<h2>Image Upload Debug</h2>";

// Show PHP upload settings
echo "<h3>PHP Upload Settings:</h3>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "file_uploads: " . ini_get('file_uploads') . "<br>";
echo "upload_tmp_dir: " . ini_get('upload_tmp_dir') . "<br>";

// Check uploads directory
$target_dir = "../uploads/";
echo "<h3>Uploads Directory:</h3>";
echo "Path: " . realpath($target_dir) . "<br>";
echo "Exists: " . (is_dir($target_dir) ? 'YES' : 'NO') . "<br>";
echo "Writable: " . (is_writable($target_dir) ? 'YES' : 'NO') . "<br>";

// List files in uploads
echo "<h3>Files in uploads/:</h3>";
if (is_dir($target_dir)) {
    $files = array_diff(scandir($target_dir), ['.', '..']);
    if (empty($files)) {
        echo "EMPTY - No files found!<br>";
    } else {
        foreach ($files as $f) {
            echo "- " . $f . " (" . filesize($target_dir . $f) . " bytes)<br>";
        }
    }
}

// Show product images from DB
echo "<h3>Products in DB (image column):</h3>";
$rows = $pdo->query('SELECT id, name, image FROM products ORDER BY id DESC LIMIT 10')->fetchAll();
foreach ($rows as $r) {
    $img_val = $r['image'] ?: '(empty)';
    $file_path = "../" . $r['image'];
    $exists = (!empty($r['image']) && file_exists($file_path)) ? 'YES' : 'NO';
    echo "ID: {$r['id']} | {$r['name']} | image: [{$img_val}] | file exists: {$exists}<br>";
}
?>
