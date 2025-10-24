<?php
session_start();

require_once __DIR__ . '/../../include/config/const.php';
require_once __DIR__ . '/../../include/functions/common.php';
require_once __DIR__ . '/../../include/model/product_model.php';
require_once __DIR__ . '/../../include/model/cart_model.php';
require_once __DIR__ . '/../../include/view/product_list_view.php';
require_once __DIR__ . '/../../include/model/cart_model.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$dbh = db_connect();
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['username'] ?? '';

$message = '';
$message_type = '';

// カート追加処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    if (add_to_cart($dbh, $user_id, $product_id)) {
        $message = 'カートに追加しました。';
        $message_type = 'success';
    } else {
        $message = 'カートへの追加に失敗しました。';
        $message_type = 'error';
    }
}

// 公開商品一覧取得
$products = get_public_products($dbh);

// ビュー表示
display_product_list($products, $user_name, $message, $message_type);
