<?php
require_once __DIR__ . '/../config/const.php'; // IMAGE_PATH, NO_IMAGE 定義

/**
 * get_history
 * 購入履歴の取得（商品画像対応）
 *
 * @param PDO $dbh
 * @param int $user_id
 * @return array
 */
function get_history(PDO $dbh, string $user_id): array
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
            p.image_name AS product_img
        FROM orders o
        JOIN order_details d ON o.order_id = d.order_id
        JOIN products p ON d.product_id = p.product_id
        WHERE o.user_id = ?
        ORDER BY o.create_date DESC, d.order_detail_id ASC
    ";

    $stmt = $dbh->prepare($sql);
    $stmt->execute([$user_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
            'product_img' => $row['product_img'] ?: 'NO_IMAGE'
        ];
    }

    return $orders;
}

/**
 * delete_history
 * 注文履歴を削除（orders + order_details）
 *
 * @param PDO $dbh
 * @param int $order_id
 * @param int $user_id
 * @return bool
 */
function delete_history(PDO $dbh, int $order_id, int $user_id): bool
{
    try {
        $dbh->beginTransaction();

        // 本人確認
        $stmt = $dbh->prepare("SELECT order_id FROM orders WHERE order_id = :order_id AND user_id = :user_id");
        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            $dbh->rollBack();
            return false;
        }

        // order_details 削除
        $stmt = $dbh->prepare("DELETE FROM order_details WHERE order_id = :order_id");
        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        // orders 削除
        $stmt = $dbh->prepare("DELETE FROM orders WHERE order_id = :order_id");
        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        $dbh->commit();
        return true;
    } catch (Exception $e) {
        $dbh->rollBack();
        return false;
    }
}
