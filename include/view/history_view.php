<?php
function display_history(array $order_history, string $user_name, string $message = '', string $message_type = '')
{
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

            nav a {
                background: #4CAF50;
                color: #fff;
                padding: 6px 12px;
                border-radius: 5px;
                text-decoration: none;
            }

            .success {
                color: green;
            }

            .error {
                color: red;
            }

            .order-card {
                background: #fff;
                border-radius: 10px;
                padding: 15px;
                margin-bottom: 25px;
                box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
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

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            .product-img {
                width: 80px;
                height: 80px;
                object-fit: contain;
            }

            .order-total {
                text-align: right;
                font-weight: bold;
                margin-top: 5px;
            }
        </style>
    </head>

    <body>

        <header>
            <h1><?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8') ?> さんの購入履歴</h1>
            <nav>
                <a href="product_list.php">🏠 商品一覧へ戻る</a>
                <a href="logout.php">ログアウト</a>
            </nav>
        </header>

        <?php if ($message): ?>
            <p class="<?= htmlspecialchars($message_type) ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if (empty($order_history)): ?>
            <p>購入履歴がありません。</p>
        <?php else: ?>
            <?php foreach ($order_history as $order): ?>
                <div class="order-card">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <strong>注文番号：</strong><?= htmlspecialchars($order['order_id']) ?><br>
                            <span>注文日：<?= htmlspecialchars($order['order_date']) ?></span>
                        </div>
                        <form method="post" onsubmit="return confirm('この注文を削除しますか？');">
                            <input type="hidden" name="delete_order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                            <button type="submit" class="delete-btn">削除</button>
                        </form>
                    </div>

                    <table>
                        <tr>
                            <th>商品画像</th>
                            <th>商品名</th>
                            <th>単価</th>
                            <th>数量</th>
                            <th>小計</th>
                        </tr>
                        <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <td>
                                    <?php
                                    $image_path = $item['product_img']
                                        ? IMAGE_PATH . $item['product_img']
                                        : IMAGE_PATH . NO_IMAGE;
                                    ?>
                                    <img src="<?= htmlspecialchars($image_path) ?>" alt="商品画像" class="product-img">
                                </td>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td><?= number_format($item['price']) ?>円</td>
                                <td><?= htmlspecialchars($item['quantity']) ?></td>
                                <td><?= number_format($item['subtotal']) ?>円</td>
                            </tr>
                        <?php endforeach; ?>
                    </table>

                    <div class="order-total">
                        合計金額：¥<?= number_format($order['total_amount']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </body>

    </html>
<?php
}
