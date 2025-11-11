<?php

/**
 * purchase_history_model.php
 * - 注文履歴の取得処理
 */

function get_purchase_history($dbh, $user_id)
{
    $sql = "
        SELECT 
            o.order_id,
            o.create_date AS order_date,
            o.total_amount,
            d.product_name,
            d.price,
            d.quantity,
            d.subtotal,
            p.img AS product_img
        FROM orders o
        JOIN order_details d ON o.order_id = d.order_id
        JOIN products p ON d.product_id = p.product_id
        WHERE o.user_id = ?
        ORDER BY o.create_date DESC, d.order_detail_id ASC
    ";

    $stmt = $dbh->prepare($sql);
    $stmt->execute([$user_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 注文ごとにグループ化
    $orders = [];
    foreach ($rows as $row) {
        $order_id = $row['order_id'];
        if (!isset($orders[$order_id])) {
            $orders[$order_id] = [
                'order_id' => $order_id,
                'order_date' => $row['order_date'],
                'total_amount' => $row['total_amount'],
                'items' => []
            ];
        }
        $orders[$order_id]['items'][] = [
            'product_name' => $row['product_name'],
            'price' => $row['price'],
            'quantity' => $row['quantity'],
            'subtotal' => $row['subtotal'],
            'product_img' => $row['product_img']
        ];
    }

    return $orders;
}
