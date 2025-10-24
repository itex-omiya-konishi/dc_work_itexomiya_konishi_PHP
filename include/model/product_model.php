<?php

/**
 * product_model.php
 * 商品情報・在庫情報・画像情報を扱うモデル
 */

require_once dirname(__FILE__) . '/../config/const.php';

/**
 * 商品関連モデル
 */

// 公開商品を取得（在庫・画像JOIN込み）
function get_public_products($dbh)
{
    $sql = "
        SELECT 
            p.product_id,
            p.product_name,
            p.price,
            p.public_flg,
            i.image_name,
            COALESCE(s.stock_qty, 0) AS stock_qty
        FROM products AS p
        LEFT JOIN images AS i ON p.product_id = i.product_id
        LEFT JOIN stocks AS s ON p.product_id = s.product_id
        WHERE p.public_flg = 1
        ORDER BY p.create_date DESC
    ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * 全商品取得（管理者用）
 */
function get_all_products($dbh)
{
    $sql = 'SELECT 
                p.product_id,
                p.product_name,
                p.price,
                p.public_flg,
                COALESCE(s.stock_qty, 0) AS stock_qty,
                i.image_name
            FROM products AS p
            LEFT JOIN stocks AS s ON p.product_id = s.product_id
            LEFT JOIN images AS i ON p.product_id = i.product_id
            ORDER BY p.create_date DESC';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * 商品登録
 */
function insert_product($dbh, $name, $price, $public_flg)
{
    $sql = 'INSERT INTO products (product_name, price, public_flg, create_date, update_date)
            VALUES (?, ?, ?, NOW(), NOW())';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$name, $price, $public_flg]);
    return $dbh->lastInsertId();
}

/**
 * 在庫登録
 */
function insert_stock($dbh, $product_id, $stock_qty)
{
    $sql = 'INSERT INTO stocks (product_id, stock_qty, create_date, update_date)
            VALUES (?, ?, NOW(), NOW())';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$product_id, $stock_qty]);
}

/**
 * 画像登録
 */
function insert_image($dbh, $product_id, $image_name)
{
    $sql = 'INSERT INTO images (product_id, image_name, create_date, update_date)
            VALUES (?, ?, NOW(), NOW())';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$product_id, $image_name]);
}

/**
 * 在庫更新
 */
function update_stock($dbh, $product_id, $new_stock)
{
    $sql = 'UPDATE stocks 
            SET stock_qty = ?, update_date = NOW()
            WHERE product_id = ?';
    $stmt = $dbh->prepare($sql);
    return $stmt->execute([$new_stock, $product_id]);
}

/**
 * 公開フラグ更新
 */
function update_public_flg($dbh, $product_id, $new_status)
{
    $sql = 'UPDATE products 
            SET public_flg = ?, update_date = NOW()
            WHERE product_id = ?';
    $stmt = $dbh->prepare($sql);
    return $stmt->execute([$new_status, $product_id]);
}

/**
 * 公開フラグ切替（1⇔0）
 */
function toggle_public_flg($dbh, $product_id, $public_flg)
{
    $new_flg = ($public_flg == 1) ? 0 : 1;
    $sql = 'UPDATE products 
            SET public_flg = ?, update_date = NOW() 
            WHERE product_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$new_flg, $product_id]);
}

/**
 * 画像削除
 */
function delete_image($dbh, $product_id)
{
    $sql = 'DELETE FROM images WHERE product_id = ?';
    $stmt = $dbh->prepare($sql);
    return $stmt->execute([$product_id]);
}

/**
 * 在庫削除
 */
function delete_stock($dbh, $product_id)
{
    $sql = 'DELETE FROM stocks WHERE product_id = ?';
    $stmt = $dbh->prepare($sql);
    return $stmt->execute([$product_id]);
}

/**
 * 商品削除
 */
function delete_product($dbh, $product_id)
{
    $sql = 'DELETE FROM products WHERE product_id = ?';
    $stmt = $dbh->prepare($sql);
    return $stmt->execute([$product_id]);
}

/**
 * 商品登録トランザクション（商品・在庫・画像をまとめて登録）
 */
function register_product_transaction($dbh, $name, $price, $public_flg, $stock_qty, $image_name)
{
    try {
        $dbh->beginTransaction();

        $product_id = insert_product($dbh, $name, $price, $public_flg);
        insert_stock($dbh, $product_id, $stock_qty);
        insert_image($dbh, $product_id, $image_name);

        $dbh->commit();
        return true;
    } catch (PDOException $e) {
        $dbh->rollBack();
        error_log('商品登録エラー: ' . $e->getMessage());
        return false;
    }
}
