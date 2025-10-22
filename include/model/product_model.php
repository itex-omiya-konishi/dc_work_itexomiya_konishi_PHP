<?php

/**
 * product_model.php
 * 商品情報・在庫情報・画像情報を扱うモデル
 */

require_once dirname(__FILE__) . '/../config/const.php';
/**
 * DB接続
 * @return PDO
 */
function db_connect()
{
    try {
        $dbh = new PDO(DSN, DB_USER, DB_PASS);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $dbh->exec('SET NAMES utf8mb4');
        return $dbh;
    } catch (PDOException $e) {
        die('データベース接続エラー：' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
}
/**
 * 商品登録
 * @param PDO $dbh
 * @param string $name
 * @param int $price
 * @param int $public_flg
 * @return int 登録した商品ID
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
 * @param PDO $dbh
 * @param int $product_id
 * @param int $stock_qty
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
 * @param PDO $dbh
 * @param int $product_id
 * @param string $image_name
 */
function insert_image($dbh, $product_id, $image_name)
{
    $sql = 'INSERT INTO images (product_id, image_name, create_date, update_date)
            VALUES (?, ?, NOW(), NOW())';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$product_id, $image_name]);
}
/**
 * 全商品取得（管理者用）
 * @param PDO $dbh
 * @return array
 */
function get_all_products($dbh)
{
    $sql = 'SELECT 
                p.product_id,
                p.product_name,
                p.price,
                p.public_flg,
                s.stock_qty,
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
 * 公開中の商品を取得（ユーザー表示用）
 * @param PDO $dbh
 * @return array
 */
function get_public_products($dbh)
{
    $sql = 'SELECT 
                p.product_id,
                p.product_name,
                p.price,
                s.stock_qty,
                i.image_name
            FROM products AS p
            LEFT JOIN stocks AS s ON p.product_id = s.product_id
            LEFT JOIN images AS i ON p.product_id = i.product_id
            WHERE p.public_flg = 1
            ORDER BY p.create_date DESC';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
/**
 * 在庫更新
 * @param PDO $dbh
 * @param int $product_id
 * @param int $new_stock
 */
function update_stock($dbh, $product_id, $new_stock)
{
    $sql = 'UPDATE stocks 
            SET stock_qty = ?, update_date = NOW()
            WHERE product_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$new_stock, $product_id]);
}
/**
 * 入力値のサニタイズ（XSS対策）
 * @param string $str
 * @return string
 */
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
/**
 * 商品登録トランザクション
 * （商品・在庫・画像をまとめて登録）
 * @param PDO $dbh
 * @param string $name
 * @param int $price
 * @param int $public_flg
 * @param int $stock_qty
 * @param string $image_name
 * @return bool
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
