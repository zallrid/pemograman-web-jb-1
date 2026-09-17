<?php
session_start();

session_destroy();


session_start();
$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Semua data session berhasil dikosongkan.'];

header("Location: index.php");
exit;