<?php
session_start();

// Cookieの保存期間（30日）
define('EXPIRATION_PERIOD', 30);
$cookie_expiration = time() + EXPIRATION_PERIOD * 60 * 60 * 24;

// DB接続情報（ローカル）
$dsn = 'mysql:host=localhost;dbname=xb513874_gnjy0;charset=utf8';
$db_user = 'xb513874_18q1d';
$db_pass = '2qtajdv62h';

try {
    $pdo = new PDO($dsn, $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続失敗: " . $e->getMessage());
}

// POSTデータ取得
$user_id = $_POST['user_id'] ?? '';
$password = $_POST['password'] ?? '';
$cookie_confirmation = $_POST['cookie_confirmation'] ?? '';

// Cookieの保存または削除
if ($cookie_confirmation === 'checked') {
    setcookie('cookie_confirmation', $cookie_confirmation, $cookie_expiration);
    setcookie('user_id', $user_id, $cookie_expiration);
} else {
    setcookie('cookie_confirmation', '', time() - 3600);
    setcookie('user_id', '', time() - 3600);
}

// ユーザー認証処理
$sql = "SELECT username, password FROM user WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $user['password'] === $password) {
    // 認証成功 → セッションに保存して home へ
    $_SESSION['login_id'] = $user_id;
    $_SESSION['user_name'] = $user['username'];
    header('Location: work38.home.php');
    exit();
} else {
    // 認証失敗 → エラーフラグをセットしてログイン画面へ
    $_SESSION['err_flg'] = true;
    header('Location: work38.php');
    exit();
}
