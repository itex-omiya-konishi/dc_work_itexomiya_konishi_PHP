<?php

/**
 * order_model.php
 * 購入履歴関連モデル
 */

require_once __DIR__ . '/../functions/common.php';

function get_order_history($dbh, $user_id)
{
    $stmt = $dbh->prepare('
        SELECT 
            o.order_id,
            o.create_date AS order_date,
            o.total_amount,
            d.product_id,
            d.product_name,
            d.image_name,
            d.price,
            d.quantity,
            d.subtotal
        FROM orders o
        JOIN order_details d ON o.order_id = d.order_id
        WHERE o.user_id = ?
        ORDER BY o.create_date DESC, d.order_detail_id ASC
    ');
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
