<?php

/**
 * cart_model.php
 * ショッピングカート関連のデータ操作
 */

require_once __DIR__ . '/../config/const.php';

/**
 * カートに商品を追加（既にある場合は数量を加算）
 */
function add_to_cart($dbh, $user_id, $product_id, $quantity = 1)
{
    try {
        // 在庫チェック
        $stmt = $dbh->prepare('SELECT stock_qty FROM stocks WHERE product_id = ?');
        $stmt->execute([$product_id]);
        $stock = $stmt->fetchColumn();
        if ($stock === false) {
            error_log("add_to_cart: product_id {$product_id} not found in stocks");
            return false;
        }
        if ($quantity > $stock) {
            error_log("add_to_cart: requested quantity {$quantity} exceeds stock {$stock} for product_id {$product_id}");
            return false;
        }

        // カート内の既存数量確認
        $stmt = $dbh->prepare('SELECT product_qty FROM carts WHERE user_id = ? AND product_id = ?');
        $stmt->execute([$user_id, $product_id]);
        $existing_qty = $stmt->fetchColumn();

        if ($existing_qty !== false) {
            $new_qty = $existing_qty + $quantity;
            $stmt = $dbh->prepare('UPDATE carts SET product_qty = ?, update_date = NOW() WHERE user_id = ? AND product_id = ?');
            if (!$stmt->execute([$new_qty, $user_id, $product_id])) {
                $errorInfo = $stmt->errorInfo();
                error_log('add_to_cart UPDATE error: ' . print_r($errorInfo, true));
                return false;
            }
        } else {
            $stmt = $dbh->prepare('INSERT INTO carts (user_id, product_id, product_qty, create_date, update_date) VALUES (?, ?, ?, NOW(), NOW())');
            if (!$stmt->execute([$user_id, $product_id, $quantity])) {
                $errorInfo = $stmt->errorInfo();
                error_log('add_to_cart INSERT error: ' . print_r($errorInfo, true));
                return false;
            }
        }

        return true;
    } catch (PDOException $e) {
        error_log('add_to_cart PDOException: ' . $e->getMessage());
        echo '<pre style="color:red;">PDOException: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
        return false;
    }
}


/**
 * カート一覧を取得（JOINで商品情報・画像も取得）
 */
function get_cart_list($dbh, $user_id)
{
    $sql = 'SELECT 
                c.cart_id,
                c.product_id,
                c.product_qty,
                p.product_name,
                p.price,
                i.image_name
            FROM carts c
            INNER JOIN products p ON c.product_id = p.product_id
            LEFT JOIN images i ON c.product_id = i.product_id
            WHERE c.user_id = ?
            ORDER BY c.update_date DESC';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * カート内商品の数量を更新
 */
function update_cart_quantity($dbh, $cart_id, $new_qty)
{
    if ($new_qty < 1) {
        return ['success' => false, 'message' => '数量は1以上で指定してください。'];
    }

    try {
        $sql = 'UPDATE carts SET product_qty = ?, update_date = NOW() WHERE cart_id = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([(int)$new_qty, (int)$cart_id]);
        return ['success' => true, 'message' => '数量を更新しました。'];
    } catch (PDOException $e) {
        error_log('update_cart_quantity error: ' . $e->getMessage());
        return ['success' => false, 'message' => '数量の更新に失敗しました。'];
    }
}

/**
 * カート内の商品を削除
 */
function delete_cart_item($dbh, $cart_id)
{
    try {
        $sql = 'DELETE FROM carts WHERE cart_id = ?';
        $stmt = $dbh->prepare($sql);
        return $stmt->execute([(int)$cart_id]);
    } catch (PDOException $e) {
        error_log('delete_cart_item error: ' . $e->getMessage());
        return false;
    }
}

/**
 * カート内商品の合計金額を計算
 */
function calculate_cart_total(array $cart_items)
{
    $total = 0;
    foreach ($cart_items as $item) {
        $total += (int)$item['price'] * (int)$item['product_qty'];
    }
    return $total;
}
