<?php

/**
 * product_manage_view.php
 * 商品管理ページのビュー（画像変更・削除対応）
 */

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
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 15px;
                background: #fff;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            }

            th,
            td {
                padding: 10px;
                border-bottom: 1px solid #ddd;
                text-align: center;
            }

            th {
                background: #f0f0f0;
            }

            img {
                max-width: 100px;
                border-radius: 5px;
            }

            form {
                display: inline-block;
                margin: 0 3px;
            }

            .success {
                color: green;
                font-weight: bold;
            }

            .error {
                color: red;
                font-weight: bold;
            }

            .logout {
                margin-bottom: 10px;
            }

            .add-form {
                background: #fff;
                padding: 15px;
                margin-top: 20px;
                border-radius: 10px;
                box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            }

            input[type="text"],
            input[type="number"],
            select {
                padding: 5px;
                width: 150px;
            }

            input[type="file"] {
                width: 180px;
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

            .delete-btn {
                background: #f44336;
            }

            .image-change-btn {
                background: #2196F3;
            }

            .image-delete-btn {
                background: #FF9800;
            }
        </style>
    </head>

    <body>
        <header>
            <div class="logout">
                <?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?> さん　ようこそ |
                <a href="logout.php">ログアウト</a>
            </div>
            <h1>商品管理ページ</h1>
            <nav>
                <a href="product_list.php">🛒 商品一覧へ</a>
            </nav>
        </header>

        <?php if ($message !== ''): ?>
            <p class="<?= $message_type ?>">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>

        <!-- 商品一覧テーブル -->
        <table>
            <tr>
                <th>ID</th>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>公開状態</th>
                <th>操作</th>
            </tr>

            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= (int)$product['product_id']; ?></td>
                    <td>
                        <img src="<?= htmlspecialchars(IMAGE_PATH . ($product['image_name'] ?: NO_IMAGE), ENT_QUOTES, 'UTF-8'); ?>" alt="">
                    </td>
                    <td><?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= number_format($product['price']); ?>円</td>

                    <!-- 在庫変更 -->
                    <td>
                        <form method="post">
                            <input type="hidden" name="action" value="update_stock">
                            <input type="hidden" name="product_id" value="<?= (int)$product['product_id']; ?>">
                            <input type="number" name="stock_qty" value="<?= (int)$product['stock_qty']; ?>" min="0">
                            <button type="submit">変更</button>
                        </form>
                    </td>

                    <!-- 公開フラグ切替 -->
                    <td>
                        <form method="post">
                            <input type="hidden" name="action" value="toggle_public">
                            <input type="hidden" name="product_id" value="<?= (int)$product['product_id']; ?>">
                            <input type="hidden" name="public_flg" value="<?= (int)$product['public_flg']; ?>">
                            <button type="submit">
                                <?= $product['public_flg'] == 1 ? '公開中' : '非公開'; ?>
                            </button>
                        </form>
                    </td>

                    <!-- 操作 -->
                    <td>
                        <!-- 画像変更 -->
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="change_image">
                            <input type="hidden" name="product_id" value="<?= (int)$product['product_id']; ?>">
                            <input type="file" name="new_image">
                            <button type="submit" class="image-change-btn">画像変更</button>
                        </form>

                        <!-- 画像削除 -->
                        <form method="post">
                            <input type="hidden" name="action" value="delete_image">
                            <input type="hidden" name="product_id" value="<?= (int)$product['product_id']; ?>">
                            <button type="submit" class="image-delete-btn">画像削除</button>
                        </form>

                        <!-- 商品削除 -->
                        <form method="post">
                            <input type="hidden" name="action" value="delete_product">
                            <input type="hidden" name="product_id" value="<?= (int)$product['product_id']; ?>">
                            <button type="submit" class="delete-btn">削除</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- 新規商品追加フォーム -->
        <div class="add-form">
            <h2>新規商品追加</h2>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="insert_product">
                商品名：<input type="text" name="product_name" required>
                価格：<input type="number" name="price" min="0" required>
                在庫：<input type="number" name="stock_qty" min="0" required>
                公開：
                <select name="public_flg">
                    <option value="1">公開</option>
                    <option value="0">非公開</option>
                </select>
                画像：<input type="file" name="image">
                <button type="submit">追加</button>
            </form>
        </div>
    </body>

    </html>
<?php
}
?>
