<?php
function display_product_list($products, $user_name, $message = '', $message_type = '')
{
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
        <title>商品一覧</title>
        <style>
            ul {
                list-style: none;
                padding: 0;
            }

            li {
                border: 1px solid #ccc;
                padding: 10px;
                margin-bottom: 10px;
            }

            img {
                max-width: 150px;
                display: block;
            }

            .success {
                color: green;
            }

            .error {
                color: red;
            }
        </style>
    </head>

    <body>
        <div>
            <?= htmlspecialchars($user_name) ?> さん
            <a href="logout.php">ログアウト</a>
            <a href="cart.php">カートを見る</a>
        </div>

        <?php if ($message !== ''): ?>
            <div class="<?= htmlspecialchars($message_type) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <h1>商品一覧</h1>
        <ul>
            <?php foreach ($products as $product): ?>
                <li>
                    <img src="../images/<?= htmlspecialchars($product['image_name']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                    <p>商品名: <?= htmlspecialchars($product['product_name']) ?></p>
                    <p>価格: <?= htmlspecialchars($product['price']) ?> 円</p>
                    <?php if ($product['stock_qty'] > 0): ?>
                        <form method="post">
                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                            <button type="submit">カートに入れる</button>
                        </form>
                    <?php else: ?>
                        <p>売り切れ</p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </body>

    </html>
<?php
}
