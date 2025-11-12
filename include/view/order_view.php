<?php
require_once __DIR__ . '/../config/const.php';

/**
 * 購入履歴ビュー
 * - 注文ごとにまとめて表示
 * - 注文日、合計金額、商品一覧を表示
 * - メッセージ対応（成功・エラー）
 */
function display_order_history(
    array $order_history,
    string $user_name,
    string $message = '',
    string $message_type = ''
) {
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>購入履歴</title>
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        body {
            font-family: "Meiryo", sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        h1 {
            margin: 0;
        }

        nav a {
            margin-left: 10px;
            text-decoration: none;
            background: #4CAF50;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
        }

        .order-card {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }

        .order-header span {
            font-weight: bold;
        }

        .order-date {
            color: #666;
            font-size: 0.9em;
        }

        .product {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .product img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-right: 10px;
            border-radius: 5px;
        }

        .subtotal {
            text-align: right;
            font-weight: bold;
            margin-top: 5px;
            color: #333;
        }

        .order-total {
            text-align: right;
            font-size: 1.1em;
            font-weight: bold;
            color: #4CAF50;
            margin-top: 10px;
        }

        .no-history {
            text-align: center;
            margin-top: 100px;
            color: #666;
            font-size: 1.1em;
        }

        .delete-btn {
            background: #e53935;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-btn:hover {
            opacity: 0.8;
        }

        .success {
            color: green;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<header>
    <h1><?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?> さんの購入履歴</h1>
    <nav>
        <a href="product_list.php">🏠 商品一覧へ戻る</a>
        <a href="logout.php">ログアウト</a>
    </nav>
</header>

<?php if ($message !== ''): ?>
    <p class="<?= htmlspecialchars($message_type, ENT_QUOTES, 'UTF-8'); ?>">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
    </p>
<?php endif; ?>

<?php
if (empty($order_history)) {
    echo "<p class='no-history'>購入履歴がありません。</p>";
} else {
    $current_order_id = null;
    $order_total = 0;

    foreach ($order_history as $row) {
        // 注文が変わったら前の注文カードを閉じる
        if ($current_order_id !== $row['order_id']) {
            if ($current_order_id !== null) {
                echo "<div class='order-total'>合計金額：¥" . number_format($order_total) . "</div>";
                echo "</div>"; // .order-card 閉じる
            }

            // 新しい注文の開始
            echo "<div class='order-card'>";
            echo "<div class='order-header'>
                    <div>
                        <span>注文番号：{$row['order_id']}</span>
                        <span class='order-date'>注文日：" . htmlspecialchars($row['order_date']) . "</span>
                    </div>
                    <form method='post' action='order.php' onsubmit='return confirm(\"この注文履歴を削除しますか？\");'>
                        <input type='hidden' name='delete_order_id' value='{$row['order_id']}'>
                        <button type='submit' class='delete-btn'>削除</button>
                    </form>
                  </div>";

            $current_order_id = $row['order_id'];
            $order_total = 0;
        }

        // 商品ごとに表示
        $image = IMAGE_PATH . ($row['image_name'] ?: NO_IMAGE);
        echo "<div class='product'>
                <img src='" . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . "' alt='商品画像'>
                <div>
                    <div>" . htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8') . "</div>
                    <div>価格：" . number_format($row['price']) . "円 × " . (int)$row['quantity'] . "</div>
                    <div class='subtotal'>小計：" . number_format($row['subtotal']) . "円</div>
                </div>
              </div>";

        $order_total += $row['subtotal'];
    }

    // 最後の注文を閉じる
    echo "<div class='order-total'>合計金額：¥" . number_format($order_total) . "</div>";
    echo "</div>";
}
?>
</body>
</html>
<?php
}

/**
 * 注文履歴を削除（orders + order_details）
 *
 * @param PDO $dbh
 * @param int $order_id
 * @param int $user_id （本人確認用）
 * @return bool
 */
function delete_order(PDO $dbh, int $order_id, int $user_id): bool
{
    try {
        $dbh->beginTransaction();

        // 本人の注文か確認
        $sql = "SELECT order_id FROM orders WHERE order_id = :order_id AND user_id = :user_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            $dbh->rollBack();
            return false;
        }

        // order_detailsを先に削除
        $sql = "DELETE FROM order_details WHERE order_id = :order_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        // ordersを削除
        $sql = "DELETE FROM orders WHERE order_id = :order_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        $dbh->commit();
        return true;
    } catch (Exception $e) {
        $dbh->rollBack();
        return false;
    }
}
