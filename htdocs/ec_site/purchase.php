<?php

/**
 * 購入完了ページ
 * - カート内容をDBから取得
 * - 注文テーブル・注文明細テーブルに登録
 * - 在庫を減算し、カートを空にする
 * - 購入完了メッセージを表示
 */
require_once '../../include/config/const.php';
require_once '../../include/functions/common.php';
require_once '../../include/model/cart_model.php';
require_once '../../include/model/purchase_model.php';
require_once '../../include/view/purchase_view.php';

ensure_session_started();
check_login();

$dbh = db_connect();
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? '';

$message = '';
$message_type = '';

// -----------------------------
// カートの中身を取得
// -----------------------------
$cart_items = get_cart_list($dbh, $user_id);

if (empty($cart_items)) {
    header('Location: product_list.php');
    exit;
}

// -----------------------------
// 購入処理（orders・order_details登録、在庫更新、カート削除）
// -----------------------------
$result = complete_purchase($dbh, $user_id, $cart_items);

if ($result['success']) {
    $message = 'ご購入ありがとうございました！';
    $message_type = 'success';
} else {
    $message = '購入処理中にエラーが発生しました。';
    $message_type = 'error';
}

// -----------------------------
// ビュー表示
// -----------------------------
display_purchase_complete($cart_items, $message, $message_type, $user_name);
