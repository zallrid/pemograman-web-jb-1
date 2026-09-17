<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Debug Session</title>
    <style>
        body { font-family: monospace; background: #222; color: #0f0; padding: 20px; }
        a { color: #fff; }
    </style>
</head>
<body>
    <h2>Data $_SESSION Saat Ini:</h2>
    <pre><?php print_r($_SESSION); ?></pre>
    <br>
    <a href="index.php">&larr; Kembali ke Beranda</a>
</body>
</html>