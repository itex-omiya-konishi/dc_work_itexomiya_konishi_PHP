<?php
define('MAX', 3); // 1ページの表示数

$customers = array(
    array('name' => '佐藤', 'age' => '10'),
    array('name' => '鈴木', 'age' => '15'),
    array('name' => '高橋', 'age' => '20'),
    array('name' => '田中', 'age' => '25'),
    array('name' => '伊藤', 'age' => '30'),
    array('name' => '渡辺', 'age' => '35'),
    array('name' => '山本', 'age' => '40'),
);

$customers_num = count($customers); // データ件数
$max_page = ceil($customers_num / MAX); // トータルページ数

// 現在のページ番号を取得（初期値は1）
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = (int)$_GET['page'];
    if ($page < 1) {
        $page = 1;
    } elseif ($page > $max_page) {
        $page = $max_page;
    }
} else {
    $page = 1;
}

// 配列の何番目からデータを取得するか
$start = ($page - 1) * MAX;

// 指定した範囲のデータを抽出
$view_customers = array_slice($customers, $start, MAX);

// 表示部分
echo '<table border="1">';
echo '<tr><th>名前</th><th>年齢</th></tr>';
foreach ($view_customers as $customer) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($customer['name']) . '</td>';
    echo '<td>' . htmlspecialchars($customer['age']) . '</td>';
    echo '</tr>';
}
echo '</table>';

// ページリンクの表示
for ($i = 1; $i <= $max_page; $i++) {
    // 現在のページはリンクにしない
    if ($i === $page) {
        echo $i . ' ';
    } else {
        echo '<a href="?page=' . $i . '">' . $i . '</a> ';
    }
}
