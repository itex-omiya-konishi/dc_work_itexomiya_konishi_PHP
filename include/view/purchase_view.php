<?php
function display_purchase_complete(array $cart_items, string $message, string $message_type, string $user_name): void
{
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
        <title>購入完了</title>
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
            }

            .logout a {
                color: #007BFF;
                text-decoration: none;
            }

            .message.success {
                color: green;
                font-weight: bold;
            }

            .message.error {
                color: red;
                font-weight: bold;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                background: #fff;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: center;
            }

            img {
                width: 100px;
                height: 100px;
                object-fit: contain;
            }

            .total {
                text-align: right;
                margin-top: 20px;
                font-size: 1.2em;
            }

            .actions {
                margin-top: 30px;
                text-align: center;
            }

            button {
                padding: 8px 16px;
                border: none;
                background: #4CAF50;
                color: #fff;
                border-radius: 5px;
                cursor: pointer;
            }

            button:hover {
                opacity: 0.8;
            }
        </style>
    </head>

    <body>
        <header>
            <div class="logout">
                <?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?> さん |
                <a href="logout.php">ログアウト</a>
            </div>
            <h1>購入完了</h1>
        </header>

        <p class="message <?= htmlspecialchars($message_type, ENT_QUOTES, 'UTF-8'); ?>">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </p>

        <table>
            <tr>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>数量</th>
                <th>小計</th>
            </tr>
            <?php $total = 0; ?>
            <?php foreach ($cart_items as $item): ?>
                <?php
                $subtotal = $item['price'] * $item['product_qty'];
                $total += $subtotal;
                ?>
                <tr>
                    <td><img src="<?= IMAGE_PATH . htmlspecialchars($item['image_name'] ?? NO_IMAGE, ENT_QUOTES, 'UTF-8'); ?>" alt=""></td>
                    <td><?= htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= number_format($item['price']); ?>円</td>
                    <td><?= (int)$item['product_qty']; ?></td>
                    <td><?= number_format($subtotal); ?>円</td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p class="total">合計金額：<?= number_format($total); ?>円</p>

        <div class="actions">
            <a href="product_list.php"><button>商品一覧へ戻る</button></a>
        </div>

    </body>

    </html>
<?php
}
