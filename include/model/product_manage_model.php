<?php

/**
 * product_manage_model.php
 * 商品管理ページ用モデル
 */
require_once __DIR__ . '/../functions/common.php';

/**
 * 商品一覧取得
 */
function get_product_list($dbh)
{
    $sql = 'SELECT product_id, product_name, price, stock_qty, public_flg, create_date, update_date
            FROM products
            ORDER BY create_date DESC';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * 商品登録（トランザクション処理付き）
 */
function register_product_transaction($dbh, $product_name, $price, $public_flg, $stock_qty, $image_name = 'no_image.png')
{
    try {
        $dbh->beginTransaction();

        $sql = 'INSERT INTO products (product_name, price, public_flg, stock_qty, create_date, update_date)
                VALUES (?, ?, ?, ?, NOW(), NOW())';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            $product_name,
            (int)$price,
            (int)$public_flg,
            (int)$stock_qty
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
 * 商品画像更新
 * - 指定した商品IDの画像名を変更する
 */
function update_product_image($dbh, $product_id, $image_name)
{
    try {
        $sql = 'UPDATE products SET image_name = ?, update_date = NOW() WHERE product_id = ?';
        $stmt = $dbh->prepare($sql);
        return $stmt->execute([$image_name, (int)$product_id]);
    } catch (PDOException $e) {
        error_log('update_product_image error: ' . $e->getMessage());
        return false;
    }
}
