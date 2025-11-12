<?php
require_once '../../include/config/const.php';
require_once '../../include/functions/common.php';
require_once '../../include/model/order_model.php';
require_once '../../include/view/order_view.php';

session_start();

check_login();
// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$dbh = db_connect();

// ✅ 削除ボタンが押されたとき
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order_id'])) {
    $order_id = (int)$_POST['delete_order_id'];

    if (delete_order($dbh, $order_id, $user_id)) {
        $message = '注文履歴を削除しました。';
        $message_type = 'success';
    } else {
        $message = '削除に失敗しました。';
        $message_type = 'error';
    }
}

// ✅ 再取得して表示
$order_history = get_order_history($dbh, $user_id);
display_order_history($order_history, $user_name, $message ?? '', $message_type ?? '');
