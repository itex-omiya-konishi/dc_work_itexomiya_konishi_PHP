<?php

/**
 * purchase_model.php
 * 購入処理関連
 */

require_once __DIR__ . '/../functions/common.php';

/**
 * 購入処理本体
 * - orders / order_details 登録
 * - 在庫数の更新
 * - カート情報削除
 */
function complete_purchase($dbh, $user_id, $cart_items)
{
    try {
        $dbh->beginTransaction();

        // ----------------------------------------
        // 1. 注文情報を orders テーブルに登録
        // ----------------------------------------
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['price'] * $item['product_qty'];
        }

        $stmt = $dbh->prepare('
            INSERT INTO orders (user_id, total_amount, create_date)
            VALUES (?, ?, NOW())
        ');
        $stmt->execute([$user_id, $total]);
        $order_id = $dbh->lastInsertId(); // 注文IDを取得

        // ----------------------------------------
        // 2. 注文明細を order_details に登録
        // ----------------------------------------
        $stmt_detail = $dbh->prepare('
            INSERT INTO order_details 
                (order_id, product_id, product_name, price, quantity, subtotal, create_date)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ');

        foreach ($cart_items as $item) {
            $subtotal = $item['price'] * $item['product_qty'];

            // 🔸 修正：$item['product_name'] に変更
            $stmt_detail->execute([
                $order_id,
                $item['product_id'],
                $item['product_name'],  // ← 正しいキー名
                $item['price'],
                $item['product_qty'],
                $subtotal
            ]);

            // ----------------------------------------
            // 3. 在庫を減算
            // ----------------------------------------
            $stmt_stock = $dbh->prepare('
                UPDATE stocks 
                SET stock_qty = stock_qty - ? 
                WHERE product_id = ? AND stock_qty >= ?
            ');
            $stmt_stock->execute([$item['product_qty'], $item['product_id'], $item['product_qty']]);

            if ($stmt_stock->rowCount() === 0) {
                throw new Exception("在庫が不足しています（商品ID: {$item['product_id']}）");
            }
        }

        // ----------------------------------------
        // 4. カート情報を削除
        // ----------------------------------------
        $stmt = $dbh->prepare('DELETE FROM carts WHERE user_id = ?');
        $stmt->execute([$user_id]);

        $dbh->commit();
        return ['success' => true, 'order_id' => $order_id];
    } catch (Exception $e) {
        $dbh->rollBack();
        error_log('complete_purchase error: ' . $e->getMessage());
        echo '<p style="color:red;">購入処理エラー:<br>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
        return ['success' => false, 'message' => $e->getMessage()];
    }
}
