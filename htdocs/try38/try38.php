<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>TRY38</title>
</head>

<body>
    <?php
    // データベースへ接続
    $db = new mysqli('localhost', 'xb513874_18q1d', '2qtajdv62h', 'xb513874_gnjy0');
    if ($db->connect_error) {
        echo $db->connect_error;
        exit();
    } else {
        $db->set_charset("utf8");    //文字コードをUTF8に設定
    }
    //SELECT文の実行
    $sql = "SELECT product_name, price FROM product WHERE price <= 100";
    if ($result = $db->query($sql)) {
        // 連想配列を取得
        while ($row = $result->fetch_assoc()) {
            echo $row["product_name"] . $row["price"] . "<br>";
        }
        // 結果セットを閉じる
        $result->close();
    }

    $db->close();        // 接続を閉じる
    ?>
</body>

</html>
