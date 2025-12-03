<?php

/**
 * 商品管理ページ（管理者用）
 */
require_once '../../include/config/const.php';
require_once '../../include/functions/common.php';
require_once '../../include/view/product_manage_view.php';
require_once '../../include/model/product_model.php';

ensure_session_started();
check_login();

$dbh = db_connect();
$message = '';
$message_type = '';
$user_name = $_SESSION['user_name'] ?? '';

// --------------------------------
// POST処理
// --------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {

        // ==============================
        // 商品追加
        // ==============================
        case 'insert_product':
            $product_name = trim($_POST['product_name'] ?? '');
            $price = $_POST['price'] ?? '';
            $stock_qty = $_POST['stock_qty'] ?? '';
            $public_flg = $_POST['public_flg'] ?? '0';
            $image = $_FILES['image'] ?? null;

            // バリデーション
            if ($product_name === '' || $price === '' || $stock_qty === '') {
                $message = 'すべての項目を入力してください。';
                $message_type = 'error';
                break;
            }
            if (!ctype_digit($price) || (int)$price < 0) {
                $message = '価格は0以上の整数で入力してください。';
                $message_type = 'error';
                break;
            }
            if (!ctype_digit($stock_qty) || (int)$stock_qty < 0) {
                $message = '在庫数は0以上の整数で入力してください。';
                $message_type = 'error';
                break;
            }

            // 画像保存
            $image_name = NO_IMAGE;
            if (!empty($image['name'])) {
                try {
                    $image_name = save_uploaded_image($image);
                } catch (Exception $e) {
                    $message = $e->getMessage();
                    $message_type = 'error';
                    break;
                }
            }

            if (register_product_transaction($dbh, $product_name, $price, $public_flg, $stock_qty, $image_name)) {
                header('Location: ' . basename(__FILE__));
                exit;
            } else {
                $message = '商品追加に失敗しました。';
                $message_type = 'error';
            }
            break;

        // ==============================
        // 在庫更新
        // ==============================
        case 'update_stock':
            $product_id = $_POST['product_id'] ?? '';
            $stock_qty = $_POST['stock_qty'] ?? '';

            if (!ctype_digit($stock_qty)) {
                $message = '在庫数は整数で入力してください。';
                $message_type = 'error';
                break;
            }
            if (update_stock_transaction($dbh, $product_id, $stock_qty)) {
                $message = '在庫数を更新しました。';
                $message_type = 'success';
            } else {
                $message = '在庫数更新に失敗しました。';
                $message_type = 'error';
            }
            break;

        // ==============================
        // 公開ステータス切替
        // ==============================
        case 'toggle_public':
            $product_id = $_POST['product_id'] ?? '';
            $public_flg = $_POST['public_flg'] ?? '0';
            $new_status = $public_flg == 1 ? 0 : 1;

            if (update_public_flg($dbh, $product_id, $new_status)) {
                $message = '公開ステータスを変更しました。';
                $message_type = 'success';
            } else {
                $message = '公開ステータス変更に失敗しました。';
                $message_type = 'error';
            }
            break;


        // ==============================
        // 画像変更
        // ==============================
        case 'change_image':
            $product_id = $_POST['product_id'] ?? '';
            $file = $_FILES['new_image'] ?? null;

            if (empty($product_id) || empty($file['name'])) {
                $message = '画像ファイルを選択してください。';
                $message_type = 'error';
                break;
            }
            try {
                update_product_image($dbh, $product_id, $file);
                header('Location: ' . basename(__FILE__));
                exit;
            } catch (Exception $e) {
                $message = $e->getMessage();
                $message_type = 'error';
            }
            break;

        // ==============================
        // 画像削除
        // ==============================
        case 'delete_image':
            $product_id = $_POST['product_id'] ?? '';

            if (delete_product_image($dbh, $product_id)) {
                $message = '画像を削除しました。';
                $message_type = 'success';
            } else {
                $message = '画像削除に失敗しました。';
                $message_type = 'error';
            }
            break;

        // ==============================
        // 商品削除
        // ==============================
        case 'delete_product':
            $product_id = $_POST['product_id'] ?? '';

            if (delete_product_transaction($dbh, $product_id)) {
                $message = '商品を削除しました。';
                $message_type = 'success';
            } else {
                $message = '商品の削除に失敗しました。';
                $message_type = 'error';
            }
            break;
    }
}

// 商品一覧取得
$products = get_product_list($dbh);

// ビュー呼び出し
display_product_manage($products, $message, $message_type, $user_name);

// ==============================
// 画像保存専用関数
// ==============================
function save_uploaded_image(array $file): string
{
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('アップロードエラーが発生しました。');
    }
    $finfo_type = mime_content_type($file['tmp_name']) ?: ($file['type'] ?? '');
    if (!in_array($finfo_type, ALLOWED_IMAGE_TYPES, true)) {
        throw new Exception('JPEGまたはPNG形式の画像を選択してください。');
    }
    if ($file['size'] > MAX_FILE_SIZE) {
        throw new Exception('ファイルサイズは1MB以下にしてください。');
    }
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
        $extension = ($finfo_type === 'image/png') ? 'png' : 'jpg';
    }

    $new_filename = uniqid('img_') . '.' . $extension;
    $save_path = IMAGE_DIR . $new_filename;

    if (!is_dir(IMAGE_DIR)) {
        mkdir(IMAGE_DIR, 0755, true);
    }
    if (!move_uploaded_file($file['tmp_name'], $save_path)) {
        throw new Exception('画像ファイルの保存に失敗しました。');
    }
    return $new_filename;
}
