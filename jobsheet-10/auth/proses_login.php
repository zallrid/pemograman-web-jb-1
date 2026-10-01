<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';


if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}


if ($_SESSION['login_attempts'] >= 3) {
    $_SESSION['flash'] = [
        'type' => 'error', 
        'pesan' => 'Terlalu banyak percobaan login yang gagal. Akun diblokir sementara.'
    ];
    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']) ? true : false;

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    
    $_SESSION['login_attempts'] = 0;
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];
    
    
    if ($remember) {
        setcookie('remember_user', $username, time() + (86400 * 7), "/");
    }

    header('Location: ../index.php');
    exit;
}

$_SESSION['login_attempts']++;
$sisaKesempatan = 3 - $_SESSION['login_attempts'];

if ($sisaKesempatan > 0) {
    $_SESSION['flash'] = [
        'type' => 'error', 
        'pesan' => 'Username atau password salah. Percobaan ke-' . $_SESSION['login_attempts'] . ' (Sisa ' . $sisaKesempatan . ' kali lagi).'
    ];
} else {
    $_SESSION['flash'] = [
        'type' => 'error', 
        'pesan' => 'Anda telah gagal login 3 kali. Akses diblokir sementara.'
    ];
}

header('Location: login.php');
exit;