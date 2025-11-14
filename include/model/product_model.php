<?php

require_once dirname(__FILE__) . '/../config/const.php';

/**
 * 公開商品一覧（一般ユーザー用）
 */
function get_public_products($dbh)
{
    $sql = "
        SELECT 
            p.product_id,
            p.product_name,
            p.price,
            p.public_flg,
            s.stock_qty,
            i.image_name
        FROM products AS p
        LEFT JOIN stocks AS s ON p.product_id = s.product_id
        LEFT JOIN images AS i ON p.product_id = i.product_id
        WHERE p.public_flg = 1
        ORDER BY p.create_date DESC
    ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * 管理用商品一覧
 */
function get_product_list($dbh)
{
    $sql = "
        SELECT 
            p.product_id,
            p.product_name,
            p.price,
            p.public_flg,
            s.stock_qty,
            i.image_name
        FROM products AS p
        LEFT JOIN stocks AS s ON p.product_id = s.product_id
        LEFT JOIN images AS i ON p.product_id = i.product_id
        ORDER BY p.create_date DESC
    ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * 新規商品登録（商品+在庫+画像）
 */
function register_product_transaction(PDO $dbh, string $name, int $price, int $public_flg, int $stock_qty, string $image_name = NO_IMAGE): bool
{
    try {
        $dbh->beginTransaction();

        $sql1 = 'INSERT INTO products (product_name, price, public_flg, image_name, create_date, update_date)
                 VALUES (?, ?, ?, ?, NOW(), NOW())';
        $stmt1 = $dbh->prepare($sql1);
        $stmt1->execute([$name, $price, $public_flg, $image_name]);
        $product_id = $dbh->lastInsertId();

        $sql2 = 'INSERT INTO stocks (product_id, stock_qty, create_date, update_date)
                 VALUES (?, ?, NOW(), NOW())';
        $stmt2 = $dbh->prepare($sql2);
        $stmt2->execute([$product_id, $stock_qty]);

        $sql3 = 'INSERT INTO images (product_id, image_name, create_date, update_date)
                 VALUES (?, ?, NOW(), NOW())';
        $stmt3 = $dbh->prepare($sql3);
        $stmt3->execute([$product_id, $image_name]);

        $dbh->commit();
        return true;
    } catch (PDOException $e) {
        $dbh->rollBack();
        error_log('register_product_transaction error: ' . $e->getMessage());
        return false;
    }
}


/**
 * 在庫更新
 */
function update_stock_transaction($dbh, $product_id, $stock_qty)
{
    try {
        $sql = 'UPDATE stocks SET stock_qty = ?, update_date = NOW()
                WHERE product_id = ?';
        $stmt = $dbh->prepare($sql);
        return $stmt->execute([(int)$stock_qty, (int)$product_id]);
    } catch (PDOException $e) {
        error_log('update_stock_transaction error: ' . $e->getMessage());
        return false;
    }
}


/**
 * 公開ステータス切替
 */
function update_public_flg($dbh, $product_id, $new_status)
{
    $sql = 'UPDATE products SET public_flg = ?, update_date = NOW()
            WHERE product_id = ?';
    $stmt = $dbh->prepare($sql);
    return $stmt->execute([(int)$new_status, (int)$product_id]);
}


/**
 * 商品の画像名取得
 */
function get_image_name($dbh, $product_id)
{
    $sql = 'SELECT image_name FROM images WHERE product_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$product_id]);
    return $stmt->fetchColumn();
}


/**
 * 画像変更（古い画像削除 → 新しい画像保存 → DB更新）
 */
function update_product_image(PDO $dbh, int $product_id, array $file)
{
    // MIMEチェック
    $finfo_type = mime_content_type($file['tmp_name']) ?: ($file['type'] ?? '');
    if (!in_array($finfo_type, ALLOWED_IMAGE_TYPES, true)) {
        throw new Exception('JPEGまたはPNG形式の画像を選択してください。');
    }
    if ($file['size'] > MAX_FILE_SIZE) {
        throw new Exception('ファイルサイズは1MB以下にしてください。');
    }

    // 保存ファイル名生成
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid('img_') . '.' . $ext;
    $save_path = IMAGE_DIR . $new_filename;

    if (!move_uploaded_file($file['tmp_name'], $save_path)) {
        throw new Exception('画像の保存に失敗しました。');
    }

    // 古い画像削除
    $old_image = get_image_name($dbh, $product_id);
    if ($old_image && $old_image !== NO_IMAGE && file_exists(IMAGE_DIR . $old_image)) {
        unlink(IMAGE_DIR . $old_image);
    }

    // DB更新
    $dbh->beginTransaction();
    try {
        // products.image_name を更新
        $sql1 = 'UPDATE products SET image_name = ?, update_date = NOW()
                 WHERE product_id = ?';
        $stmt1 = $dbh->prepare($sql1);
        $stmt1->execute([$new_filename, $product_id]);

        // images テーブルは最新のみ保持
        $sql2 = 'DELETE FROM images WHERE product_id = ?';
        $stmt2 = $dbh->prepare($sql2);
        $stmt2->execute([$product_id]);

        $sql3 = 'INSERT INTO images (product_id, image_name, create_date, update_date)
                 VALUES (?, ?, NOW(), NOW())';
        $stmt3 = $dbh->prepare($sql3);
        $stmt3->execute([$product_id, $new_filename]);

        $dbh->commit();
    } catch (Exception $e) {
        $dbh->rollBack();
        throw $e;
    }
}


/**
 * 画像削除（no_image.png に差し替え）
 */
function delete_product_image($dbh, $product_id)
{
    $old_image = get_image_name($dbh, $product_id);

    if ($old_image && $old_image !== NO_IMAGE && file_exists(IMAGE_DIR . $old_image)) {
        unlink(IMAGE_DIR . $old_image);
    }

    // products.image_name 更新
    $sql1 = 'UPDATE products SET image_name = ?, update_date = NOW()
             WHERE product_id = ?';
    $stmt1 = $dbh->prepare($sql1);
    $stmt1->execute([NO_IMAGE, $product_id]);

    // images を UPDATE（INSERT ではなく UPDATE が正しい）
    $sql2 = 'UPDATE images SET image_name = ?, update_date = NOW()
             WHERE product_id = ?';
    $stmt2 = $dbh->prepare($sql2);
    return $stmt2->execute([NO_IMAGE, $product_id]);
}


/**
 * 商品削除
 */
function delete_product_transaction($dbh, $product_id)
{
    try {
        $dbh->beginTransaction();

        // 古い画像削除
        $old_image = get_image_name($dbh, $product_id);
        if ($old_image && $old_image !== NO_IMAGE && file_exists(IMAGE_DIR . $old_image)) {
            unlink(IMAGE_DIR . $old_image);
        }

        // 関連情報削除
        $dbh->prepare('DELETE FROM images WHERE product_id = ?')->execute([$product_id]);
        $dbh->prepare('DELETE FROM stocks WHERE product_id = ?')->execute([$product_id]);
        $dbh->prepare('DELETE FROM products WHERE product_id = ?')->execute([$product_id]);

        $dbh->commit();
        return true;
    } catch (PDOException $e) {
        $dbh->rollBack();
        error_log('delete_product_transaction error: ' . $e->getMessage());
        return false;
    }
}
