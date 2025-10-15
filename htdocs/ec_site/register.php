<?php
require_once '../../include/model/ec_site_model.php';
require_once '../../include/view/ec_site_view.php';

$dbh = db_connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // バリデーション
    if ($user_id === '' || $password === '') {
        $message = 'ユーザーIDとパスワードを入力してください。';
    } else {
        // ハッシュ化して登録
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (user_id, username, password) VALUES (?, ?, ?)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$user_id, $username, $hash]);

        header('Location: index.php');
        exit;
    }
} else {
    $message = '';
}

// ビュー呼び出し
//display_register_form($message);
