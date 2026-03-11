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

foreach ($images as $src => $dest) {
    if (file_exists($src)) {
        copy($src, $uploads_dir . '/' . $dest);
    }
}
?>
