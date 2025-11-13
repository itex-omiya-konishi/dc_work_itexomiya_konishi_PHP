<?php

/**
 * 商品管理ページ（管理者用）
 * - 商品の追加、在庫数変更、公開ステータス変更、画像変更・削除、商品削除
 * - バリデーション・メッセージ表示付き
 * - ログイン制御・ログアウト対応
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

// ==============================
// POST処理
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        // --------------------------------
        // 商品追加
        // --------------------------------
        // --- (product_manage.php の insert_product ケース) ---
        case 'insert_product':
            $product_name = trim($_POST['product_name'] ?? '');
            $price = $_POST['price'] ?? '';
            $stock_qty = $_POST['stock_qty'] ?? '';
            $public_flg = $_POST['public_flg'] ?? '0';
            $image = $_FILES['image'] ?? null;

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


            // 画像処理（アップロードがある場合のみ・エラー確認）
            $image_name = NO_IMAGE;
            if (!empty($image['name'])) {
                if (!isset($image['error']) || $image['error'] !== UPLOAD_ERR_OK) {
                    $message = '画像アップロードに問題があります（エラーコード:' . ($image['error'] ?? 'N/A') . '）。';
                    $message_type = 'error';
                    break;
                }
                try {
                    $image_name = save_uploaded_image($image); // 下で改良した関数を使う
                } catch (Exception $e) {
                    $message = $e->getMessage();
                    $message_type = 'error';
                    break;
                }
            }
            if (register_product_transaction($dbh, $product_name, $price, $public_flg, $stock_qty, $image_name)) {
                // 成功したらリダイレクト（PRG）して一覧を最新化・二重送信防止
                header('Location: ' . basename(__FILE__));
                exit;
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
        // --- (product_manage.php の change_image ケース) ---
        case 'change_image':
            $product_id = $_POST['product_id'] ?? '';
            $new_image = $_FILES['new_image'] ?? null;

            if (empty($product_id) || empty($new_image['name'])) {
                $message = '画像ファイルを選択してください。';
                $message_type = 'error';
                break;
            }

            if (!isset($new_image['error']) || $new_image['error'] !== UPLOAD_ERR_OK) {
                $message = '画像アップロードに問題があります（エラーコード:' . ($new_image['error'] ?? 'N/A') . '）。';
                $message_type = 'error';
                break;
            }

            try {
                update_product_image($dbh, $product_id, $new_image); // model内で保存＋DB更新
                header('Location: ' . basename(__FILE__)); // PRGで反映
                exit;
            } catch (Exception $e) {
                $message = $e->getMessage();
                $message_type = 'error';
            }
            break;


        // --------------------------------
        // 画像削除
        // --------------------------------
        case 'delete_image':
            $product_id = $_POST['product_id'] ?? '';
            if (delete_product_image($dbh, $product_id)) {
                $message = '画像を削除しました（no_image.pngに変更）。';
                $message_type = 'success';
            } else {
                $message = '画像削除に失敗しました。';
                $message_type = 'error';
            }
            break;

        // --------------------------------
        // 商品削除
        // --------------------------------
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

// ==============================
// 商品一覧取得
// ==============================
$products = get_product_list($dbh);

// ==============================
// ビュー呼び出し
// ==============================
display_product_manage($products, $message, $message_type, $user_name);


/**
 * 商品追加時の画像アップロード専用関数
 */
function save_uploaded_image(array $file): string
{
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('アップロードエラーが発生しました。');
    }

    // MIMEタイプチェック
    $finfo_type = mime_content_type($file['tmp_name']) ?: ($file['type'] ?? '');
    if (!in_array($finfo_type, ALLOWED_IMAGE_TYPES, true)) {
        throw new Exception('JPEGまたはPNG形式の画像を選択してください。');
    }

    // ファイルサイズチェック
    if ($file['size'] > MAX_FILE_SIZE) {
        throw new Exception('ファイルサイズは1MB以下にしてください。');
    }

    // 保存ファイル名（拡張子は安全に取得）
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
        // 予防措置：拡張子が予期外ならMIMEに合わせる
        $extension = $finfo_type === 'image/png' ? 'png' : 'jpg';
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
