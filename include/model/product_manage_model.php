<?php

/**
 * product_manage_model.php
 * 商品管理ページ用モデル（画像対応版）
 */
require_once __DIR__ . '/../config/const.php';

/**
 * 商品一覧取得
 */
function get_product_list($dbh)
{
    $sql = 'SELECT product_id, product_name, price, stock_qty, public_flg, image_name, create_date, update_date
            FROM products
            ORDER BY create_date DESC';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * 商品登録（トランザクション付き）
 */
function register_product_transaction($dbh, $product_name, $price, $public_flg, $stock_qty, $image_name = NO_IMAGE)
{
    try {
        $dbh->beginTransaction();

        $sql = 'INSERT INTO products (product_name, price, public_flg, stock_qty, image_name, create_date, update_date)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            $product_name,
            (int)$price,
            (int)$public_flg,
            (int)$stock_qty,
            $image_name
        ]);

        $dbh->commit();
        return true;
    } catch (PDOException $e) {
        $dbh->rollBack();
        error_log('Product insert error: ' . $e->getMessage());
        return false;
    }
}

/**
 * 公開フラグ更新
 */
function update_public_flg($dbh, $product_id, $public_flg)
{
    try {
        $sql = 'UPDATE products SET public_flg = ?, update_date = NOW() WHERE product_id = ?';
        $stmt = $dbh->prepare($sql);
        return $stmt->execute([(int)$public_flg, (int)$product_id]);
    } catch (PDOException $e) {
        error_log('update_public_flg error: ' . $e->getMessage());
        return false;
    }
}

/**
 * 在庫数更新（トランザクション付き）
 */
function update_stock_transaction($dbh, $product_id, $stock_qty)
{
    try {
        $dbh->beginTransaction();

        $sql = 'UPDATE products SET stock_qty = ?, update_date = NOW() WHERE product_id = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([(int)$stock_qty, (int)$product_id]);

        $dbh->commit();
        return true;
    } catch (PDOException $e) {
        $dbh->rollBack();
        error_log('update_stock_transaction error: ' . $e->getMessage());
        return false;
    }
}

/**
 * 商品削除（トランザクション付き）
 */
function delete_product_transaction($dbh, $product_id)
{
    try {
        $dbh->beginTransaction();

        // 古い画像削除（no_image.pngは削除しない）
        $old_image = get_image_name($dbh, $product_id);
        if ($old_image && $old_image !== NO_IMAGE && file_exists(IMAGE_DIR . $old_image)) {
            unlink(IMAGE_DIR . $old_image);
        }

        $sql = 'DELETE FROM products WHERE product_id = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([(int)$product_id]);

        $dbh->commit();
        return true;
    } catch (PDOException $e) {
        $dbh->rollBack();
        error_log('delete_product_transaction error: ' . $e->getMessage());
        return false;
    }
}

/**
 * 商品画像更新（アップロードファイル対応＋古い画像削除）
 */
function update_product_image($dbh, $product_id, $file)
{
    if (empty($file['name'])) {
        return false;
    }

    // MIMEタイプチェック
    if (!in_array($file['type'], ALLOWED_IMAGE_TYPES, true)) {
        throw new Exception('JPEGまたはPNG形式の画像を選択してください。');
    }

    // ファイルサイズチェック
    if ($file['size'] > MAX_FILE_SIZE) {
        throw new Exception('ファイルサイズは1MB以下にしてください。');
    }

    // 保存ファイル名
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = uniqid('img_') . '.' . $extension;
    $save_path = IMAGE_DIR . $new_filename;

    if (!is_dir(IMAGE_DIR)) {
        mkdir(IMAGE_DIR, 0755, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $save_path)) {
        throw new Exception('画像ファイルの保存に失敗しました。');
    }

    // 既存画像削除（no_image.pngは削除しない）
    $old_image = get_image_name($dbh, $product_id);
    if ($old_image && $old_image !== NO_IMAGE && file_exists(IMAGE_DIR . $old_image)) {
        unlink(IMAGE_DIR . $old_image);
    }

    // DB更新
    $sql = 'UPDATE products SET image_name = ?, update_date = NOW() WHERE product_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$new_filename, $product_id]);

    return $new_filename;
}

/**
 * 商品画像削除（サーバーからも削除＋DBをno_image.pngに更新）
 */
function delete_product_image($dbh, $product_id)
{
    $old_image = get_image_name($dbh, $product_id);

    // no_image.png は削除しない
    if ($old_image && $old_image !== NO_IMAGE && file_exists(IMAGE_DIR . $old_image)) {
        unlink(IMAGE_DIR . $old_image);
    }

    // DB の image_name を no_image.png に更新
    $sql = 'UPDATE products SET image_name = ?, update_date = NOW() WHERE product_id = ?';
    $stmt = $dbh->prepare($sql);
    return $stmt->execute([NO_IMAGE, $product_id]);
}

/**
 * 商品の既存画像名取得
 */
function get_image_name($dbh, $product_id)
{
    $sql = 'SELECT image_name FROM products WHERE product_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$product_id]);
    return $stmt->fetchColumn();
}
