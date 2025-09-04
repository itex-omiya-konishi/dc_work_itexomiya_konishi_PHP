<?php
define('MAX', '3'); // 1ページの表示数

$customers = array( // 表示データの配列
    array('name' => '佐藤', 'age' => '10'),
    array('name' => '鈴木', 'age' => '15'),
    array('name' => '高橋', 'age' => '20'),
    array('name' => '田中', 'age' => '25'),
    array('name' => '伊藤', 'age' => '30'),
    array('name' => '渡辺', 'age' => '35'),
    array('name' => '山本', 'age' => '40'),
);

$customers_num = count($customers); // トータルデータ件数

$max_page = ceil($customers_num / MAX); // トータルページ数

// データ表示、ページネーションを実装

// 1ページに表示する件数
$per_page = 3;

// 現在のページ番号を取得（初期値は1）
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// 配列の開始位置を計算
$start_index = ($page - 1) * $per_page;

// 配列から表示する部分だけを切り出し
$display_customers = array_slice($customers, $start_index, $per_page);

// 最大ページ数を計算
$max_page = ceil(count($customers) / $per_page);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>顧客一覧</title>
    <style>
        table,
        th,
        td {
            border: 1px solid #000;
            border-collapse: collapse;
            padding: 4px 8px;
            text-align: center;
        }

        .pagination a {
            margin: 0 4px;
            text-decoration: none;
            color: purple;
        }
    </style>
</head>

<body>

    <h3>顧客リスト</h3>
    <table>
        <tr>
            <th>名前</th>
            <th>年齢</th>
        </tr>
        <?php foreach ($display_customers as $customer): ?>
            <tr>
                <td><?= htmlspecialchars($customer['name']) ?></td>
                <td><?= htmlspecialchars($customer['age']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- ページネーション表示 -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $max_page; $i++): ?>
            <a href="?page=<?= $i ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>

</body>

</html>
