<?php
require_once '../../include/config/const.php';
require_once '../../include/functions/common.php';
require_once '../../include/model/product_model.php';
require_once '../../include/model/cart_model.php';
require_once '../../include/view/product_list_view.php';
require_once '../../include/model/cart_model.php'; // これを追加


ensure_session_started();

// ログインチェック
if (empty($_SESSION['user_id']) || empty($_SESSION['user_name'])) {
    header('Location: index.php');
    exit;
}

$dbh = db_connect();
$user_id = $_SESSION['user_id'];
$user_name = h($_SESSION['user_name']);

$products = get_public_products($dbh);
$message = '';
$message_type = '';

// 「カートに入れる」ボタン押下時
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];

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
display_product_list($products, $message, $message_type, $user_name);
