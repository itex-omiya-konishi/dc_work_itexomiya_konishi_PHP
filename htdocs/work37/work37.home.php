<?php
// Cookieの保存期間（1年）
define('EXPIRATION_PERIOD', 30);
$cookie_expiration = time() + EXPIRATION_PERIOD * 60 * 24 * 365;

// DB接続情報（ローカルの場合）
$dsn = 'mysql:host=localhost;dbname=xb513874_gnjy0;charset=utf8';
$db_user = 'xb513874_18q1d';
$db_pass = '2qtajdv62h';

try {
    $pdo = new PDO($dsn, $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続失敗: " . $e->getMessage());
}

// POST取得
$user_id = $_POST['user_id'] ?? '';
$password = $_POST['password'] ?? '';
$cookie_confirmation = $_POST['cookie_confirmation'] ?? '';

// Cookie保存または削除
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

// パスワード確認（プレーンテキスト）
if ($user && $user['password'] === $password) {
    $message = htmlspecialchars($user['username']) . "さん、ようこそ！";
} else {
    $message = "ログインに失敗しました";
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログイン結果</title>
</head>

<body>
    <p><?php echo $message; ?></p>
</body>

</html>
