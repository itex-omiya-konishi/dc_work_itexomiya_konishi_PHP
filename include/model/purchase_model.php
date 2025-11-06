<?php

/**
 * purchase_model.php
 * 購入処理関連
 */

require_once __DIR__ . '/../functions/common.php';

/**
 * 購入処理本体
 * - 在庫数の更新
 * - カート情報削除
 */
function complete_purchase($dbh, $user_id, $cart_items)
{
    try {
        $dbh->beginTransaction();

        foreach ($cart_items as $item) {
            $product_id = $item['product_id'];
            $quantity   = $item['product_qty'];

            // 在庫更新（マイナス）
            $stmt = $dbh->prepare('UPDATE stocks SET stock_qty = stock_qty - ? WHERE product_id = ? AND stock_qty >= ?');
            $stmt->execute([$quantity, $product_id, $quantity]);

            if ($stmt->rowCount() === 0) {
                throw new Exception("在庫が不足しています（商品ID: {$product_id}）");
            }
        }

        // カート削除
        $stmt = $dbh->prepare('DELETE FROM carts WHERE user_id = ?');
        $stmt->execute([$user_id]);

        $dbh->commit();
        return ['success' => true];
    } catch (Exception $e) {
        $dbh->rollBack();
        error_log('complete_purchase error: ' . $e->getMessage());
        return ['success' => false];
    }
}
