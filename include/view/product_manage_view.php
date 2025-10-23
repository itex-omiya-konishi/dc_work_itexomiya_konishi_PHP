<?php

/**
 * 商品管理ページ（管理者用）ビュー
 * - 商品追加フォーム
 * - 在庫数変更
 * - 公開ステータス変更
 * - 商品削除
 * - エラー／成功メッセージ表示
 * - ログアウトリンク表示
 */

function display_product_manage_page($products, $err_msgs = [], $success_msgs = [], $user_name = '')
{
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
        <title>商品管理ページ</title>
        <link rel="stylesheet" href="../../css/common.css">
        <style>
            body {
                font-family: "Meiryo", sans-serif;
                background-color: #f8f8f8;
                margin: 0;
                padding: 20px;
            }

            header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }

            h1 {
                background-color: #333;
                color: #fff;
                padding: 12px 20px;
                border-radius: 8px;
                margin: 0;
            }

            .logout {
                font-size: 14px;
            }

            .logout a {
                color: #fff;
                background-color: #d00;
                padding: 6px 12px;
                border-radius: 4px;
                text-decoration: none;
            }

            .logout a:hover {
                background-color: #900;
            }

            .message-box {
                margin: 15px 0;
                padding: 10px 15px;
                border-radius: 6px;
            }

            .error {
                background-color: #ffe4e4;
                color: #d00;
            }

            .success {
                background-color: #e8ffe8;
                color: #060;
            }

            .form-section {
                background-color: #fff;
                padding: 20px;
                border-radius: 12px;
                margin-bottom: 30px;
                box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
            }

            .form-section h2 {
                border-bottom: 2px solid #555;
                padding-bottom: 5px;
                margin-bottom: 15px;
            }

            .form-item {
                margin-bottom: 10px;
            }

            input[type="text"],
            input[type="number"],
            select {
                padding: 6px;
                border-radius: 4px;
                border: 1px solid #ccc;
                width: 250px;
            }

            input[type="file"] {
                margin-top: 4px;
            }

            input[type="submit"],
            button {
                background-color: #0066cc;
                color: white;
                padding: 8px 14px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
            }

            input[type="submit"]:hover,
            button:hover {
                background-color: #004a99;
            }

            table {
                border-collapse: collapse;
                width: 100%;
                background-color: #fff;
                box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
            }

            th,
            td {
                border: 1px solid #ccc;
                padding: 10px;
                text-align: center;
            }

            th {
                background-color: #f2f2f2;
            }

            img {
                max-width: 100px;
                height: auto;
            }

            .actions form {
                display: inline;
            }
        </style>
    </head>

    <body>

        <header>
            <h1>商品管理ページ</h1>

            <div class="logout">
                <?php if ($user_name): ?>
                    <?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8') ?> さん
                    <a href="?action=logout">ログアウト</a>
                <?php endif; ?>
            </div>
        </header>

        <?php if (!empty($err_msgs)): ?>
            <div class="message-box error">
                <?php foreach ($err_msgs as $msg): ?>
                    <p>⚠ <?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_msgs)): ?>
            <div class="message-box success">
                <?php foreach ($success_msgs as $msg): ?>
                    <p>✅ <?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- 商品追加フォーム -->
        <div class="form-section">
            <h2>新規商品追加</h2>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="process_kind" value="insert">

                <div class="form-item">
                    <label>商品名：</label><br>
                    <input type="text" name="product_name" required>
                </div>

                <div class="form-item">
                    <label>価格：</label><br>
                    <input type="number" name="price" min="0" required>
                </div>

                <div class="form-item">
                    <label>在庫数：</label><br>
                    <input type="number" name="stock_qty" min="0" required>
                </div>

                <div class="form-item">
                    <label>公開ステータス：</label><br>
                    <select name="public_flg" required>
                        <option value="1">公開</option>
                        <option value="0">非公開</option>
                    </select>
                </div>

                <div class="form-item">
                    <label>商品画像：</label><br>
                    <input type="file" name="image" accept="image/jpeg, image/png" required>
                </div>

                <input type="submit" value="商品を追加">
            </form>
        </div>

        <!-- 商品一覧テーブル -->
        <table>
            <tr>
                <th>商品ID</th>
                <th>画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>公開ステータス</th>
                <th>操作</th>
            </tr>

            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <?php if (!empty($product['image_name'])): ?>
                            <img src="<?= IMAGE_PATH . htmlspecialchars($product['image_name'], ENT_QUOTES, 'UTF-8') ?>" alt="商品画像">
                        <?php else: ?>
                            画像なし
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>¥<?= htmlspecialchars(number_format($product['price']), ENT_QUOTES, 'UTF-8') ?></td>

                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="process_kind" value="update_stock">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="number" name="stock_qty" min="0" value="<?= htmlspecialchars($product['stock_qty'], ENT_QUOTES, 'UTF-8') ?>" required>
                            <input type="submit" value="更新">
                        </form>
                    </td>

                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="process_kind" value="update_status">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="public_flg" value="<?= htmlspecialchars($product['public_flg'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="submit" value="<?= $product['public_flg'] == 1 ? '公開 → 非公開' : '非公開 → 公開' ?>">
                        </form>
                    </td>

                    <td class="actions">
                        <form method="post">
                            <input type="hidden" name="process_kind" value="delete">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="submit" value="削除" onclick="return confirm('本当に削除しますか？');">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

    </body>

    </html>
<?php
}
?>
