<?php

require_once __DIR__ . '/../config/const.php';
require_once __DIR__ . '/product_model.php'; // 商品情報取得用

/**
 * カートに商品追加
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
            $new_qty = $cart['product_qty'] + 1;
            $sql = 'UPDATE carts SET product_qty = ?, update_date = NOW() WHERE user_id = ? AND product_id = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$new_qty, $user_id, $product_id]);
        } else {
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
 * ユーザーのカート一覧取得（商品情報付き）
 */
function get_cart_list($dbh, $user_id)
{
    $sql = "
        SELECT 
            c.cart_id,
            c.product_id,
            c.product_qty,
            p.product_name,
            p.price,
            COALESCE(i.image_name, '" . NO_IMAGE . "') AS image_name,
            COALESCE(s.stock_qty, 0) AS stock_qty
        FROM carts AS c
        INNER JOIN products AS p ON c.product_id = p.product_id
        LEFT JOIN images AS i ON p.product_id = i.product_id
        LEFT JOIN stocks AS s ON p.product_id = s.product_id
        WHERE c.user_id = ?
        ORDER BY c.create_date DESC
    ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * カート数量変更
 * - 正の整数のみ有効
 * - 在庫数チェック
 */
function update_cart_quantity($dbh, $cart_id, $new_qty)
{
    if (!ctype_digit((string)$new_qty) || (int)$new_qty <= 0) {
        return ['success' => false, 'message' => '数量は1以上の整数で入力してください。'];
    }

    // 在庫確認
    $sql = "
        SELECT s.stock_qty
        FROM carts AS c
        INNER JOIN stocks AS s ON c.product_id = s.product_id
        WHERE c.cart_id = ?
        LIMIT 1
    ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$cart_id]);
    $stock = $stmt->fetchColumn();

    if ($stock === false) {
        return ['success' => false, 'message' => '商品が存在しません。'];
    }

    if ((int)$new_qty > (int)$stock) {
        return ['success' => false, 'message' => '在庫が足りません。'];
    }

    $sql = 'UPDATE carts SET product_qty = ?, update_date = NOW() WHERE cart_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([(int)$new_qty, $cart_id]);

    return ['success' => true];
}

/**
 * カートから商品削除
 */
function delete_cart_item($dbh, $cart_id)
{
    $sql = 'DELETE FROM carts WHERE cart_id = ?';
    $stmt = $dbh->prepare($sql);
    return $stmt->execute([$cart_id]);
}

/**
 * カート合計金額計算
 */
function calculate_cart_total($cart_items)
{
    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['product_qty'];
    }
    return $total;
}
