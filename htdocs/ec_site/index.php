<?php
session_start();

require_once __DIR__ . '/../../include/config/const.php';
require_once __DIR__ . '/../../include/functions/common.php';
require_once __DIR__ . '/../../include/model/user_model.php';
require_once __DIR__ . '/../../include/view/user_view.php';

// DB接続
$dbh = db_connect();

$message = '';
$message_type = ''; // success / error

// 登録完了メッセージ
if (isset($_GET['register']) && $_GET['register'] === 'success') {
    $message = 'ユーザー登録が完了しました。ログインしてください。';
    $message_type = 'success';
}

// フォーム送信時
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = trim($_POST['user_id'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($user_id === '' || $password === '') {
        $message = 'ユーザーIDとパスワードを入力してください。';
        $message_type = 'error';
    } elseif (check_user($dbh, $user_id, $password)) {
        // ログイン成功
        $_SESSION['user_id'] = $user_id;
        header('Location: product_list.php'); // 商品一覧ページへ遷移
        exit;
    } else {
        $message = 'ユーザーIDまたはパスワードが違います。';
        $message_type = 'error';
    }
}

// ビュー表示
display_login_form($message, $message_type);
