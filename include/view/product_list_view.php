<?php
require_once __DIR__ . '/../config/const.php';

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

            .logout {
                margin-bottom: 10px;
            }

            .product-list {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 20px;
            }

            .product-item {
                background: #fff;
                border-radius: 10px;
                padding: 10px;
                text-align: center;
                box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            }

            img {
                max-width: 150px;
                border-radius: 5px;
                margin-bottom: 10px;
            }

            .soldout {
                color: red;
                font-weight: bold;
            }

            .success {
                color: green;
                font-weight: bold;
            }

            .error {
                color: red;
                font-weight: bold;
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
        </style>
    </head>

    <body>
        <header>
            <div class="logout">
                <?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?> さん ようこそ |
                <a href="logout.php">ログアウト</a>
            </div>
            <h1>商品一覧</h1>
            <nav><a href="cart.php">🛒 カートを見る</a></nav>
        </header>

        <?php if ($message !== ''): ?>
            <p class="<?= $message_type ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <div class="product-list">
            <?php foreach ($products as $product): ?>
                <?php
                $image_name = $product['image_name'] ?? NO_IMAGE;
                $image_path = IMAGE_PATH . $image_name;
                ?>
                <div class="product-item">
                    <img src="<?= htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8'); ?>"
                        alt="<?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?>">
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
    </body>

    </html>
<?php
}
