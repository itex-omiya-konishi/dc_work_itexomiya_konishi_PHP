<?php

require_once __DIR__ . '/../config/const.php';

function display_cart($cart_items, $total_price, $message = '', $message_type = '', $user_name = '')
{
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
        <title>ショッピングカート</title>
        <link rel="stylesheet" href="../../css/style.css">
        <style>
            body {
                font-family: "Meiryo", sans-serif;
                background: #f9f9f9;
                padding: 20px;
            }

            header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }

            .logout {
                margin-bottom: 10px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            th,
            td {
                padding: 10px;
                border: 1px solid #ccc;
                text-align: center;
            }

            img {
                max-width: 100px;
                border-radius: 5px;
            }

            .success {
                color: green;
                font-weight: bold;
            }

            .error {
                color: red;
                font-weight: bold;
            }

            input[type="number"] {
                width: 50px;
                text-align: center;
            }

            button {
                padding: 5px 10px;
                border: none;
                background: #4CAF50;
                color: white;
                border-radius: 5px;
                cursor: pointer;
            }

            button:hover {
                opacity: 0.8;
            }

            .total {
                text-align: right;
                font-size: 1.2em;
                font-weight: bold;
            }

            nav a {
                text-decoration: none;
                color: #4CAF50;
                font-weight: bold;
            }
        </style>
    </head>

    <body>
        <header>
            <div class="logout">
                <?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?> さん | <a href="logout.php">ログアウト</a>
            </div>
            <h1>ショッピングカート</h1>
            <nav><a href="product_list.php">商品一覧に戻る</a></nav>
        </header>

        <?php if ($message !== ''): ?>
            <p class="<?= $message_type ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
            <p>カートに商品が入っていません。</p>
        <?php else: ?>
            <form method="post">
                <table>
                    <thead>
                        <tr>
                            <th>商品画像</th>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>数量</th>
                            <th>小計</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item):
                            $image_path = IMAGE_PATH . ($item['image_name'] ?? NO_IMAGE);
                            $subtotal = $item['price'] * $item['product_qty'];
                        ?>
                            <tr>
                                <td><img src="<?= htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                                <td><?= htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= number_format($item['price']); ?>円</td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="action" value="update_qty">
                                        <input type="hidden" name="cart_id" value="<?= (int)$item['cart_id']; ?>">
                                        <input type="number" name="product_qty" value="<?= (int)$item['product_qty']; ?>" min="1">
                                        <button type="submit">更新</button>
                                    </form>
                                </td>
                                <td><?= number_format($subtotal); ?>円</td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="action" value="delete_item">
                                        <input type="hidden" name="cart_id" value="<?= (int)$item['cart_id']; ?>">
                                        <button type="submit">削除</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p class="total">合計: <?= number_format($total_price); ?>円</p>

                <form method="post">
                    <input type="hidden" name="action" value="purchase">
                    <button type="submit">購入する</button>
                </form>
            </form>
        <?php endif; ?>
    </body>

    </html>
<?php
}
