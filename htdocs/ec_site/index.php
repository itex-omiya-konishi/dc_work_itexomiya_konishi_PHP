<?php

/**
 * ログインページ（トップページ）
 * - 登録済みユーザーのログイン処理
 * - セッション開始、エラーメッセージ表示付き
 */

session_start();

require_once __DIR__ . '/../../include/config/const.php';
require_once __DIR__ . '/../../include/functions/common.php';
require_once __DIR__ . '/../../include/model/user_model.php';
require_once __DIR__ . '/../../include/view/user_view.php';

// DB接続
$dbh = db_connect();

$message = '';
$message_type = ''; // success / error

// --------------------------------------
// 登録完了メッセージ
// --------------------------------------
if (isset($_GET['register']) && $_GET['register'] === 'success') {
    $message = 'ユーザー登録が完了しました。ログインしてください。';
    $message_type = 'success';
}

// --------------------------------------
// フォーム送信時（ログイン処理）
// --------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = trim($_POST['user_id'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($user_id === '' || $password === '') {
        $message = 'ユーザーIDとパスワードを入力してください。';
        $message_type = 'error';
    } else {
        // check_user() が ユーザー情報 or false を返す前提
        $user = check_user($dbh, $user_id, $password);

        if (is_array($user)) {
            // ログイン成功
            $_SESSION['user_id']   = $user['user_id'];
            $_SESSION['user_name'] = $user['user_name'];

            // ---------------------------------------------------
            // 管理者アカウントの場合 → 商品管理ページへ遷移
            // ---------------------------------------------------
            if ($user['user_id'] === 'ec_admin') {
                header('Location: product_manage.php');
                exit;
            }

            // 一般ユーザー → 商品一覧ページへ遷移
            header('Location: product_list.php');
            exit;
        } else {
            $message = 'ユーザーIDまたはパスワードが違います。';
            $message_type = 'error';
        }
    }
}

// --------------------------------------
// ビュー表示
// --------------------------------------
display_login_form($message, $message_type);
