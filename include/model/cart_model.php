<?php

require_once __DIR__ . '/../config/const.php';

/**
 * カート追加
 * - すでにカートにある場合は数量 +1
 * - 新規の場合は挿入
 */
function add_to_cart($dbh, $user_id, $product_id)
{
    try {
        $dbh->beginTransaction();

        // 既にカートにあるか確認
        $sql = 'SELECT product_qty FROM carts WHERE user_id = ? AND product_id = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$user_id, $product_id]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) {
            // 数量を +1
            $new_qty = $cart['product_qty'] + 1;
            $sql = 'UPDATE carts SET product_qty = ?, update_date = NOW() WHERE user_id = ? AND product_id = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$new_qty, $user_id, $product_id]);
        } else {
            // 新規挿入
            $sql = 'INSERT INTO carts (user_id, product_id, product_qty, create_date, update_date) VALUES (?, ?, 1, NOW(), NOW())';
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$user_id, $product_id]);
        }

        $dbh->commit();
        return true;
    } catch (PDOException $e) {
        $dbh->rollBack();
        error_log('カート追加エラー: ' . $e->getMessage());
        return false;
    }
}
/**
 * カート関連モデル
 */
// カート内に同一商品があるか確認
function get_cart_item($dbh, $user_id, $product_id)
{
    $sql = "
        SELECT cart_id, product_qty
        FROM cart_table
        WHERE user_id = :user_id AND product_id = :product_id
        LIMIT 1
    ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// カート新規追加
function insert_cart_item($dbh, $user_id, $product_id, $qty)
{
    $sql = "
        INSERT INTO cart_table (user_id, product_id, product_qty, create_date, update_date)
        VALUES (:user_id, :product_id, :product_qty, NOW(), NOW())
    ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->bindValue(':product_qty', $qty, PDO::PARAM_INT);
    $stmt->execute();
}
// カート数量更新
function update_cart_qty($dbh, $cart_id, $new_qty)
{
    $sql = "
        UPDATE cart_table
        SET product_qty = :qty, update_date = NOW()
        WHERE cart_id = :cart_id
    ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':qty', $new_qty, PDO::PARAM_INT);
    $stmt->bindValue(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->execute();
}
