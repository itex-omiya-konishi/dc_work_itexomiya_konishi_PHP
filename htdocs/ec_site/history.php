<?php

/**
 * 購入履歴ページ
 * 
 * このファイルでは以下の外部関数を使用します：
 * @uses get_history() from include/model/history_model.php
 * @uses delete_history() from include/model/history_model.php
 * @uses display_history() from include/view/history_view.php
 */

require_once '../../include/config/const.php';
require_once '../../include/functions/common.php';
require_once '../../include/model/history_model.php';
require_once '../../include/view/history_view.php';
ensure_session_started();
check_login();

$dbh = db_connect();
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? '';

$message = '';
$message_type = '';

// ✅ 削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order_id'])) {
    $order_id = (int)$_POST['delete_order_id'];

    if (delete_history($dbh, $order_id, $user_id)) {
        $message = '購入履歴を削除しました。';
        $message_type = 'success';
    } else {
        $message = '削除に失敗しました。';
        $message_type = 'error';
    }
}

// ✅ 履歴データ取得
$order_history = get_history($dbh, $user_id);

// ✅ 表示
display_history($order_history, $user_name, $message, $message_type);
