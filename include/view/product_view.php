<?php

/**
 * product_view.php
 * 商品管理ページの表示テンプレート
 * （管理者用）
 * - 商品追加フォーム
 * - 商品一覧（在庫変更・公開/非公開切替・削除）
 */

function display_product_manage_page($products, $err_msgs = [], $success_msgs = [])
{
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="<?= HTML_CHARACTER_SET ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>商品管理ページ</title>
        <style>
            body {
                font-family: "Segoe UI", "Hiragino Kaku Gothic ProN", Meiryo, sans-serif;
                background-color: #f8f9fa;
                margin: 0;
                padding: 20px;
            }

            h1 {
                color: #333;
                text-align: center;
                margin-bottom: 30px;
            }

            .message {
                max-width: 800px;
                margin: 0 auto 20px auto;
                padding: 10px 20px;
                border-radius: 8px;
            }

            .error {
                background-color: #ffe5e5;
                color: #d00000;
                border: 1px solid #d00000;
            }

            .success {
                background-color: #e6ffed;
                color: #007a2b;
                border: 1px solid #007a2b;
            }

            .form-section {
                max-width: 800px;
                background: #fff;
                padding: 20px;
                margin: 0 auto 40px auto;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            table {
                width: 100%;
                border-collapse: collapse;
                background: #fff;
                margin-top: 10px;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 8px 10px;
                text-align: center;
            }

            th {
                background: #f1f1f1;
            }

            tr:nth-child(even) {
                background: #f9f9f9;
            }

            input[type="text"],
            input[type="number"],
            select {
                width: 90%;
                padding: 5px;
            }

            input[type="submit"],
            button {
                background: #007bff;
                color: #fff;
                border: none;
                border-radius: 6px;
                padding: 6px 10px;
                cursor: pointer;
            }

            input[type="submit"]:hover,
            button:hover {
                background: #0056b3;
            }

            img {
                width: 80px;
                height: 80px;
                object-fit: cover;
            }
        </style>
    </head>

    <body>

        <h1>商品管理ページ</h1>

        <!-- エラーメッセージ表示 -->
        <?php if (!empty($err_msgs)) : ?>
            <div class="message error">
                <?php foreach ($err_msgs as $msg) : ?>
                    <p><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- 成功メッセージ表示 -->
        <?php if (!empty($success_msgs)) : ?>
            <div class="message success">
                <?php foreach ($success_msgs as $msg) : ?>
                    <p><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- 商品追加フォーム -->
        <div class="form-section">
            <h2>商品追加</h2>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="process_kind" value="insert">
                <table>
                    <tr>
                        <th>商品名</th>
                        <td><input type="text" name="product_name" required></td>
                    </tr>
                    <tr>
                        <th>価格</th>
                        <td><input type="number" name="price" min="0" required></td>
                    </tr>
                    <tr>
                        <th>在庫数</th>
                        <td><input type="number" name="stock_qty" min="0" required></td>
                    </tr>
                    <tr>
                        <th>公開ステータス</th>
                        <td>
                            <select name="public_flg" required>
                                <option value="1">公開</option>
                                <option value="0">非公開</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>商品画像</th>
                        <td><input type="file" name="image" accept="image/jpeg,image/png" required></td>
                    </tr>
                </table>
                <p style="text-align:center; margin-top:10px;">
                    <input type="submit" value="商品追加">
                </p>
            </form>
        </div>

        <!-- 商品一覧表示 -->
        <div class="form-section">
            <h2>商品一覧</h2>
            <?php if (empty($products)) : ?>
                <p>登録されている商品はありません。</p>
            <?php else : ?>
                <table>
                    <tr>
                        <th>商品画像</th>
                        <th>商品名</th>
                        <th>価格</th>
                        <th>在庫数</th>
                        <th>公開ステータス</th>
                        <th>操作</th>
                    </tr>

                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td>
                                <?php if (!empty($product['image_name'])) : ?>
                                    <img src="<?= htmlspecialchars(IMAGE_PATH . $product['image_name'], ENT_QUOTES, 'UTF-8') ?>" alt="商品画像">
                                <?php else : ?>
                                    画像なし
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>¥<?= htmlspecialchars(number_format($product['price']), ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <!-- 在庫変更フォーム -->
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="process_kind" value="update_stock">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="number" name="stock_qty" min="0" value="<?= htmlspecialchars($product['stock_qty'], ENT_QUOTES, 'UTF-8') ?>" required>
                                    <input type="submit" value="変更">
                                </form>
                            </td>
                            <td>
                                <!-- 公開ステータス切替フォーム -->
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="process_kind" value="update_status">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="public_flg" value="<?= htmlspecialchars($product['public_flg'], ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="submit" value="<?= ($product['public_flg'] == 1) ? '公開 → 非公開' : '非公開 → 公開' ?>">
                                </form>
                            </td>
                            <td>
                                <!-- 商品削除フォーム -->
                                <form method="post" onsubmit="return confirm('本当に削除しますか？');">
                                    <input type="hidden" name="process_kind" value="delete">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="submit" value="削除" style="background:#dc3545;">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>

    </body>

    </html>
<?php
}
?>
