<?php
function display_product_list($products, $message = '', $message_type = '', $user_name = '')
{
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
        <title>商品一覧</title>
        <link rel="stylesheet" href="../../css/style.css">
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
        <header>
            <div class="logout">
                <?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?> さん ようこそ<p>
            </div>
            <h1>商品一覧</h1>
            <nav>
                <a href="cart.php">🛒 カートを見る</a>
            </nav>
        </header>

        <?php if ($message !== ''): ?>
            <p class="<?= $message_type ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <div class="product-list">
            <?php foreach ($products as $product): ?>
                <div class="product-item">
                    <img src="../../images/<?= htmlspecialchars($product['image_name'] ?? 'no_image.png', ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?>" width="150">
                    <h2><?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p>価格: <?= number_format($product['price']); ?>円</p>
                    <?php if ((int)$product['stock_qty'] > 0): ?>
                        <form method="post">
                            <input type="hidden" name="product_id" value="<?= (int)$product['product_id']; ?>">
                            <button type="submit">カートに入れる</button>
                        </form>
                    <?php else: ?>
                        <p class="soldout">売り切れ</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="logout.php">ログアウト</a>
    </body>

    </html>
<?php
}
