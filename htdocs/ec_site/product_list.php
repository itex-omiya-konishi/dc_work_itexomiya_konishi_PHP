<?php

/**
 * 商品一覧ページ
 * - ログイン必須（未ログインなら index.php にリダイレクト）
 * - 公開中商品の一覧を表示
 * - 「カートに入れる」処理付き
 */

session_start();

require_once __DIR__ . '/../../include/config/const.php';
require_once __DIR__ . '/../../include/functions/common.php';
require_once __DIR__ . '/../../include/model/product_model.php';
require_once __DIR__ . '/../../include/model/cart_model.php';
require_once __DIR__ . '/../../include/view/product_list_view.php';

// ===============================
// ログインチェック
// ===============================
if (empty($_SESSION['user_id']) || empty($_SESSION['user_name'])) {
    header('Location: index.php');
    exit;
}

// ログインユーザー情報
$user_name = h($_SESSION['user_name']);

// ===============================
// DB接続
// ===============================
$dbh = db_connect();

// ===============================
// 商品一覧取得
// ===============================
$products = get_public_products($dbh);

$message = '';
$message_type = '';

// ===============================
// 「カートに入れる」ボタン押下時
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int) $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    if (add_to_cart($dbh, $user_id, $product_id)) {
        $message = '商品をカートに追加しました。';
        $message_type = 'success';
    } else {
        $message = 'カート追加に失敗しました。';
        $message_type = 'error';
    }

    // 再取得して最新状態を表示
    $products = get_public_products($dbh);
}

// ビュー表示
// ===============================
display_product_list($products, $message, $message_type, $user_name);
