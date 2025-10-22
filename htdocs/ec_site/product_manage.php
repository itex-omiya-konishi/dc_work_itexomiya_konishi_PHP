<?php

/**
 * 商品管理ページ（管理者用）
 * - 商品の追加、在庫数変更、公開ステータス変更、削除
 * - バリデーション、メッセージ表示付き
 */

require_once '../../include/config/const.php';
require_once '../../include/model/product_model.php';
require_once '../../include/view/product_view.php';

// データベース接続
$dbh = db_connect();

$err_msgs = [];
$success_msgs = [];

// --------------------------------------
// POST処理（追加／更新／削除）
// --------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $process_kind = $_POST['process_kind'] ?? '';

    // 商品追加処理
    if ($process_kind === 'insert') {
        $product_name = trim($_POST['product_name'] ?? '');
        $price = $_POST['price'] ?? '';
        $stock_qty = $_POST['stock_qty'] ?? '';
        $public_flg = $_POST['public_flg'] ?? '';
        $image = $_FILES['image'] ?? null;

        // 入力チェック
        if ($product_name === '' || $price === '' || $stock_qty === '' || $public_flg === '' || empty($image['name'])) {
            $err_msgs[] = 'すべての項目を入力してください。';
        } else {
            // 数値チェック
            if (!ctype_digit($price) || (int)$price < 0) {
                $err_msgs[] = '価格は0以上の整数を入力してください。';
            }
            if (!ctype_digit($stock_qty) || (int)$stock_qty < 0) {
                $err_msgs[] = '在庫数は0以上の整数を入力してください。';
            }

            // 画像チェック
            if ($image['error'] === UPLOAD_ERR_OK) {
                $mime_type = mime_content_type($image['tmp_name']);
                if (!in_array($mime_type, ALLOWED_IMAGE_TYPES, true)) {
                    $err_msgs[] = '画像形式はJPEGまたはPNGのみアップロード可能です。';
                }
            } else {
                $err_msgs[] = '画像のアップロードに失敗しました。';
            }
        }

        // エラーがなければ登録処理
        if (empty($err_msgs)) {
            $image_name = basename($image['name']);
            $upload_path = IMAGE_DIR . $image_name;

            if (move_uploaded_file($image['tmp_name'], $upload_path)) {
                try {
                    $dbh->beginTransaction();

                    $product_id = insert_product($dbh, $product_name, $price, $public_flg);
                    insert_stock($dbh, $product_id, $stock_qty);
                    insert_image($dbh, $product_id, $image_name);

                    $dbh->commit();
                    $success_msgs[] = '商品を追加しました。';
                } catch (PDOException $e) {
                    $dbh->rollBack();
                    $err_msgs[] = '商品追加に失敗しました。';
                }
            } else {
                $err_msgs[] = '画像ファイルの保存に失敗しました。';
            }
        }
    }

    // 在庫数更新処理
    if ($process_kind === 'update_stock') {
        $product_id = $_POST['product_id'] ?? '';
        $stock_qty = $_POST['stock_qty'] ?? '';

        if (!ctype_digit($stock_qty) || (int)$stock_qty < 0) {
            $err_msgs[] = '在庫数は0以上の整数を入力してください。';
        } else {
            if (update_stock($dbh, $product_id, $stock_qty)) {
                $success_msgs[] = '在庫数を更新しました。';
            } else {
                $err_msgs[] = '在庫数の更新に失敗しました。';
            }
        }
    }

    // 公開ステータス変更処理
    if ($process_kind === 'update_status') {
        $product_id = $_POST['product_id'] ?? '';
        $public_flg = $_POST['public_flg'] ?? '';

        $new_status = ($public_flg == 1) ? 0 : 1;
        if (update_public_flg($dbh, $product_id, $new_status)) {
            $success_msgs[] = '公開ステータスを変更しました。';
        } else {
            $err_msgs[] = '公開ステータスの変更に失敗しました。';
        }
    }

    // 商品削除処理
    if ($process_kind === 'delete') {
        $product_id = $_POST['product_id'] ?? '';

        try {
            $dbh->beginTransaction();

            delete_image($dbh, $product_id);
            delete_stock($dbh, $product_id);
            delete_product($dbh, $product_id);

            $dbh->commit();
            $success_msgs[] = '商品を削除しました。';
        } catch (PDOException $e) {
            $dbh->rollBack();
            $err_msgs[] = '商品の削除に失敗しました。';
        }
    }
}

// --------------------------------------
// 商品一覧取得
// --------------------------------------
$products = get_product_list($dbh);

// --------------------------------------
// ビューの読み込み
// --------------------------------------
display_product_manage_page($products, $err_msgs, $success_msgs);
