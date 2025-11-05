<?php
require_once __DIR__ . '/../config/const.php';

/**
 * 商品一覧ビュー
 *
 * @param array $products 商品データ配列
 * @param string $message メッセージ
 * @param string $message_type メッセージタイプ（success / error）
 * @param string $user_name ログインユーザー名
 */
function display_product_list(array $products, string $message = '', string $message_type = '', string $user_name = ''): void
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

            .product-item img {
                width: 180px;
                /* 横幅を固定 */
                height: 180px;
                /* 高さも固定 */
                object-fit: contain;
                /* 画像の縦横比を保ちつつ中央でトリミング */
                border-radius: 5px;
                margin-bottom: 10px;
            }
        </style>
    </head>

    <body>
        <header>
            <div class="logout">
                <?php if ($user_name !== ''): ?>
                    <?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?> さん ようこそ |
                    <a href="logout.php">ログアウト</a>
                <?php endif; ?>
            </div>
            <h1>商品一覧</h1>
            <nav><a href="cart.php">🛒 カートを見る</a></nav>
        </header>

        <?php if ($message !== ''): ?>
            <p class="<?= htmlspecialchars($message_type, ENT_QUOTES, 'UTF-8'); ?>">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>

        <div class="product-list">
            <?php foreach ($products as $product): ?>
                <?php
                $image_name = $product['image_name'] ?? NO_IMAGE;
                $image_path = IMAGE_PATH . $image_name;
                $stock_qty = (int)($product['stock_qty'] ?? 0);
                ?>
                <div class="product-item">
                    <img src="<?= htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8'); ?>"
                        alt="<?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?>">
                    <h2><?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p>価格: <?= number_format($product['price']); ?>円</p>

                    <?php if ($stock_qty > 0): ?>

                        <form method="post" action="product_list.php">
                            <input type="hidden" name="product_id" value="<?= (int)$product['product_id']; ?>">
                            <input type="number" name="product_qty" value="1" min="1">
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
