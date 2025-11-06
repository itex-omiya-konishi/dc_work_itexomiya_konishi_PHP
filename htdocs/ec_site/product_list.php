<?php
require_once '../../include/config/const.php';
require_once '../../include/functions/common.php';
require_once '../../include/model/product_model.php';
require_once '../../include/model/cart_model.php';
require_once '../../include/view/product_list_view.php';
require_once '../../include/view/cart_view.php';

ensure_session_started();

// ログインチェック
if (empty($_SESSION['user_id']) || empty($_SESSION['user_name'])) {
    header('Location: index.php');
    exit;
}

$dbh = db_connect();
$user_id = $_SESSION['user_id'];
echo '<pre style="color:blue;">現在のuser_id: ' . htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8') . '</pre>';
//echo '<pre style="color:red;">DEBUG: user_id = ' . htmlspecialchars($_SESSION['user_id'] ?? '未設定', ENT_QUOTES, 'UTF-8') . '</pre>';
$user_name = h($_SESSION['user_name']);


$products = get_public_products($dbh);
$message = '';
$message_type = '';

// 「カートに入れる」ボタン押下時
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = isset($_POST['product_qty']) ? (int)$_POST['product_qty'] : 1;

    // デバッグ用ログ設定（このファイルと同じディレクトリに作成）
    ini_set('log_errors', 'On');
    ini_set('error_log', __DIR__ . '/my_error.log');

    error_log("[DEBUG] add_to_cart start - user_id: $user_id, product_id: $product_id, quantity: $quantity");

    $result = add_to_cart($dbh, $user_id, $product_id, $quantity);

    if ($result) {
        $message = '商品をカートに追加しました。';
        $message_type = 'success';
        error_log("[DEBUG] add_to_cart success");
    } else {
        $message = 'カート追加に失敗しました。';
        $message_type = 'error';
        error_log("[ERROR] add_to_cart failed - user_id: $user_id, product_id: $product_id");
    }

    // 再取得して最新状態を表示
    $products = get_public_products($dbh);
}

// ビュー表示
display_product_list($products, $message, $message_type, $user_name);
