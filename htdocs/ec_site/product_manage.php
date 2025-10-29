<?php

/**
 * 商品管理ページ（管理者用）
 * - 商品の追加、在庫数変更、公開ステータス変更、画像変更・削除、商品削除
 * - バリデーション・メッセージ表示付き
 * - ログイン制御・ログアウト対応
 */

require_once '../../include/config/const.php';
require_once '../../include/functions/common.php';
require_once '../../include/model/product_manage_model.php';
require_once '../../include/view/product_manage_view.php';

ensure_session_started();
check_login();

$dbh = db_connect();
$message = '';
$message_type = '';
// ログイン中ユーザー名
$user_name = $_SESSION['user_name'] ?? '';
// ==============================
// POST処理
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        // --------------------------------
        // 商品追加
        // --------------------------------
        case 'insert_product':
            $product_name = trim($_POST['product_name'] ?? '');
            $price = $_POST['price'] ?? '';
            $stock_qty = $_POST['stock_qty'] ?? '';
            $public_flg = $_POST['public_flg'] ?? '0';
            $image = $_FILES['image'] ?? null;
            // 入力チェック
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
            // 画像処理（アップロードがない場合はno_image.png）
            $image_name = 'no_image.png';
            if (!empty($image['name']) && $image['error'] === UPLOAD_ERR_OK) {
                $mime_type = mime_content_type($image['tmp_name']);
                if (!in_array($mime_type, ALLOWED_IMAGE_TYPES, true)) {
                    $message = '画像形式はJPEGまたはPNGのみです。';
                    $message_type = 'error';
                    break;
                }
                $image_name = uniqid('img_') . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                $upload_path = IMAGE_DIR . $image_name;

                if (!move_uploaded_file($image['tmp_name'], $upload_path)) {
                    $message = '画像のアップロードに失敗しました。';
                    $message_type = 'error';
                    break;
                }
            }
            // ✅ モデル関数名を修正
            if (register_product_transaction($dbh, $product_name, $price, $public_flg, $stock_qty, $image_name)) {
                $message = '商品を追加しました。';
                $message_type = 'success';
            } else {
                $message = '商品追加に失敗しました。';
                $message_type = 'error';
            }
            break;
        // --------------------------------
        // 在庫数更新
        // --------------------------------
        case 'update_stock':
            $product_id = $_POST['product_id'] ?? '';
            $stock_qty = $_POST['stock_qty'] ?? '';

            if (!ctype_digit($stock_qty)) {
                $message = '在庫数は整数で入力してください。';
                $message_type = 'error';
                break;
            }
            // ✅ 関数名を修正
            if (update_stock_transaction($dbh, $product_id, $stock_qty)) {
                $message = '在庫数を更新しました。';
                $message_type = 'success';
            } else {
                $message = '在庫数更新に失敗しました。';
                $message_type = 'error';
            }
            break;
        // --------------------------------
        // 公開ステータス切替
        // --------------------------------
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
        // --------------------------------
        // 画像変更
        // --------------------------------
        case 'change_image':
            $product_id = $_POST['product_id'] ?? '';
            $new_image = $_FILES['new_image'] ?? null;

            if (empty($product_id) || empty($new_image['name'])) {
                $message = '画像ファイルを選択してください。';
                $message_type = 'error';
                break;
            }

            $mime_type = mime_content_type($new_image['tmp_name']);
            if (!in_array($mime_type, ALLOWED_IMAGE_TYPES, true)) {
                $message = 'JPEGまたはPNG形式のみ対応しています。';
                $message_type = 'error';
                break;
            }

            $new_name = uniqid('img_') . '.' . pathinfo($new_image['name'], PATHINFO_EXTENSION);
            $upload_path = IMAGE_DIR . $new_name;

            if (move_uploaded_file($new_image['tmp_name'], $upload_path)) {
                if (update_product_image($dbh, $product_id, $new_name)) {
                    $message = '画像を変更しました。';
                    $message_type = 'success';
                } else {
                    $message = '画像変更に失敗しました。';
                    $message_type = 'error';
                }
            } else {
                $message = '画像ファイルの保存に失敗しました。';
                $message_type = 'error';
            }
            break;
        // --------------------------------
        // 画像削除（no_image.pngに置き換え）
        // --------------------------------
        case 'delete_image':
            $product_id = $_POST['product_id'] ?? '';
            if (update_product_image($dbh, $product_id, 'no_image.png')) {
                $message = '画像を削除しました（no_image.pngに変更）。';
                $message_type = 'success';
            } else {
                $message = '画像削除に失敗しました。';
                $message_type = 'error';
            }
            break;
        // -------------------------------
        // 商品削除
        // --------------------------------
        case 'delete_product':
            $product_id = $_POST['product_id'] ?? '';

            // ✅ 関数名を修正
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
// ==============================
// 商品一覧取得
// ==============================
$products = get_product_list($dbh);
// =============================
// ビュー呼び出し
// ==============================
display_product_manage($products, $message, $message_type, $user_name);
