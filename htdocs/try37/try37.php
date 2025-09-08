<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>TRY37</title>
</head>

<body>
    <?php
    // データベースへ接続
    $db = new mysqli('localhost', 'xb513874_18q1d', '2qtajdv62h', 'xb513874_gnjy0');
    if ($db->connect_error) {
        echo $db->connect_error;
        exit();
    } else {
        print("データベースへの接続に成功しました。");
    }
    $db->close();        // 接続を閉じる
    ?>
</body>

</html>
