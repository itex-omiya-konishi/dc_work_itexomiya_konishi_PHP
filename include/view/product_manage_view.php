<?php
require_once __DIR__ . '/../config/const.php';

function display_product_manage($products, $message = '', $message_type = '', $user_name = '')
{
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
        <title>商品管理ページ</title>
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

            .message {
                margin-bottom: 15px;
                font-weight: bold;
            }

            .success {
                color: green;
            }

            .error {
                color: red;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            th,
            td {
                border: 1px solid #ccc;
                padding: 10px;
                text-align: center;
            }

            th {
                background-color: #eee;
            }

            img {
                max-width: 80px;
                border-radius: 5px;
            }

            input[type="text"],
            input[type="number"] {
                width: 80px;
                padding: 3px;
            }

            button {
                padding: 5px 10px;
                border: none;
                border-radius: 5px;
                background: #4CAF50;
                color: white;
                cursor: pointer;
            }

            button:hover {
                opacity: 0.8;
            }

            form {
                display: inline;
            }
        </style>
    </head>

    <body>
        <header>
            <div class="logout">
                <?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?> さん |
                <a href="logout.php">ログアウト</a>
            </div>
            <h1>商品管理ページ</h1>
        </header>

        <?php if ($message !== ''): ?>
            <p class="message <?= htmlspecialchars($message_type, ENT_QUOTES, 'UTF-8'); ?>">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>

        <!-- 商品追加フォーム -->
        <h2>商品追加</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="insert_product">
            商品名: <input type="text" name="product_name" required>
            価格: <input type="number" name="price" min="0" required>
            在庫: <input type="number" name="stock_qty" min="0" required>
            公開: <select name="public_flg">
                <option value="1">公開</option>
                <option value="0">非公開</option>
            </select>
            画像: <input type="file" name="image">
            <button type="submit">追加</button>
        </form>

        <!-- 商品一覧 -->
        <h2>商品一覧</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫</th>
                    <th>公開</th>
                    <th>画像</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product):
                    $image_name = $product['image_name'] ?? NO_IMAGE;
                    $image_path = IMAGE_PATH . $image_name;
                ?>
                    <tr>
                        <td><?= (int)$product['product_id']; ?></td>
                        <td><?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?= number_format($product['price']); ?>円</td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="action" value="update_stock">
                                <input type="hidden" name="product_id" value="<?= (int)$product['product_id']; ?>">
                                <input type="number" name="stock_qty" value="<?= (int)$product['stock_qty']; ?>" min="0">
                                <button type="submit">更新</button>
                            </form>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="action" value="toggle_public">
                                <input type="hidden" name="product_id" value="<?= (int)$product['product_id']; ?>">
                                <input type="hidden" name="public_flg" value="<?= (int)$product['public_flg']; ?>">
                                <button type="submit"><?= ((int)$product['public_flg'] === 1 ? '公開中' : '非公開'); ?></button>
                            </form>
                        </td>
                        <td>
                            <img src="<?= htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8'); ?>" alt="">
                            <form method="post" enctype="multipart/form-data" style="margin-top:5px;">
                                <input type="hidden" name="action" value="change_image">
                                <input type="hidden" name="product_id" value="<?= (int)$product['product_id']; ?>">
                                <input type="file" name="new_image" required>
                                <button type="submit">変更</button>
                            </form>
                            <form method="post" style="margin-top:3px;">
                                <input type="hidden" name="action" value="delete_image">
                                <input type="hidden" name="product_id" value="<?= (int)$product['product_id']; ?>">
                                <button type="submit" style="background:#f44336;">削除</button>
                            </form>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="action" value="delete_product">
                                <input type="hidden" name="product_id" value="<?= (int)$product['product_id']; ?>">
                                <button type="submit" style="background:#f44336;">削除</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>

    </html>
<?php
}
?>
