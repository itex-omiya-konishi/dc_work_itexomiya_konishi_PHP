<?php

/**
 * ショッピングカートページ
 * - ログイン制御
 * - カート内商品の表示、数量変更、削除
 * - 合計金額表示
 * - 購入ボタンで購入完了ページへ遷移
 */

require_once '../../include/config/const.php';
require_once '../../include/functions/common.php';
require_once '../../include/model/cart_model.php';
require_once '../../include/view/cart_view.php';

ensure_session_started();
check_login();

$dbh = db_connect();
$user_id = $_SESSION['user_id'] ?? null;
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        // ------------------------------
        // 数量変更
        // ------------------------------
        case 'update_qty':
            $cart_id = $_POST['cart_id'] ?? '';
            $new_qty = $_POST['product_qty'] ?? '';
            $result = update_cart_quantity($dbh, $cart_id, $new_qty);
            $message = $result['message'] ?? '';
            $message_type = $result['success'] ? 'success' : 'error';
            break;

        // ------------------------------
        // カートから削除
        // ------------------------------
        case 'delete_item':
            $cart_id = $_POST['cart_id'] ?? '';
            if (delete_cart_item($dbh, $cart_id)) {
                $message = '商品をカートから削除しました。';
                $message_type = 'success';
            } else {
                $message = '削除に失敗しました。';
                $message_type = 'error';
            }
            break;

        // ------------------------------
        // 購入（購入完了ページへ）
        // ------------------------------
        case 'purchase':
            // 実際の在庫チェック・購入処理は purchase.php 側で行う
            header('Location: purchase.php');
            break;
    }
}

// カート一覧取得
$cart_items = get_cart_list($dbh, $user_id);

// 合計金額計算
$total_price = calculate_cart_total($cart_items);

// ビュー呼び出し
display_cart($cart_items, $total_price, $message, $message_type, $_SESSION['user_name'] ?? '');
