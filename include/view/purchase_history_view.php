<?php
function display_purchase_history($orders, $user_name)
{
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
        <title>購入履歴</title>
        <link rel="stylesheet" href="../../css/common.css">
    </head>

    <body>
        <header>
            <h1>購入履歴</h1>
            <div class="logout">
                <?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8') ?> さん
                <a href="logout.php">ログアウト</a>
            </div>
        </header>

        <main>
            <?php if (empty($orders)): ?>
                <p>購入履歴はありません。</p>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <section class="order-block">
                        <h2>注文番号 #<?= htmlspecialchars($order['order_id']) ?></h2>
                        <p>注文日：<?= htmlspecialchars($order['order_date']) ?></p>
                        <table border="1" cellspacing="0" cellpadding="8">
                            <tr>
                                <th>商品画像</th>
                                <th>商品名</th>
                                <th>単価</th>
                                <th>数量</th>
                                <th>小計</th>
                            </tr>
                            <?php foreach ($order['items'] as $item): ?>
                                <tr>
                                    <td><img src="images/<?= htmlspecialchars($item['product_img']) ?>" width="80"></td>
                                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                                    <td><?= number_format($item['price']) ?>円</td>
                                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                                    <td><?= number_format($item['subtotal']) ?>円</td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="4" align="right"><strong>合計</strong></td>
                                <td><strong><?= number_format($order['total_amount']) ?>円</strong></td>
                            </tr>
                        </table>
                    </section>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="back-link">
                <a href="product_list.php">商品一覧に戻る</a>
            </div>
        </main>
    </body>

    </html>
<?php
}
