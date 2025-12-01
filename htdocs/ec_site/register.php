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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = trim($_POST['user_id'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    // バリデーション
    if ($user_id === '' || $username === '' || $password === '') {
        $message = 'ユーザーID ・ ユーザー名 ・ パスワードを入力してください。';
        $message_type = 'error';
    } elseif (mb_strlen($username) < 1 || mb_strlen($username) > 20) {
        $message = 'ユーザー名は1〜20文字で入力してください。';
        $message_type = 'error';
    } elseif (!preg_match('/^[a-zA-Z0-9_]{5,}$/', $user_id)) {
        $message = 'ユーザーIDは5文字以上で半角英数字とアンダースコアのみ使用可能です。';
        $message_type = 'error';
    } elseif (!preg_match('/^[a-zA-Z0-9_]{8,}$/', $password)) {
        $message = 'パスワードは8文字以上で半角英数字とアンダースコアのみ使用可能です。';
        $message_type = 'error';
    } elseif (check_user_exists($dbh, $user_id)) {
        $message = 'このユーザーIDは既に登録されています。別のIDを入力してください。';
        $message_type = 'error';
    } else {
        // 登録処理
        if (register_user($dbh, $user_id, $username, $password)) {
            // 登録成功 → ログインページへリダイレクト
            header('Location: index.php?register=success');
            exit;
        } else {
            $message = '登録に失敗しました。もう一度お試しください。';
            $message_type = 'error';
        }
    }
}
// ビュー呼び出し（登録失敗時のみ表示）
if ($message !== '') {
    display_register_form($message, $message_type);
} else {
    display_register_form(); // 初期表示
}
