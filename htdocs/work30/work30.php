<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>WORK30</title>
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
    <form method="post" action="work30_gallery.php" enctype="multipart/form-data">
        <p>
        <div>画像投稿</div>
        </p>
        <div style="color: red;">画像タイトルを入力してください。</div>
        <p>画像タイトル：<input type="text" name="title"></p>
        <p>画像：<input type="file" name="upload_image"></p>
        <p><input type="submit" value="画像投稿"></p>
    </form>
    <div></div>
    <?php
    echo "<p><a href='work30_gallery.php'>画像一覧ページへ</a></p>";
    echo "<hr>"; // ここでPHPから水平線（<hr>タグ）を出力します
    echo "<p>投稿された画像</p>";
    ?>
    <div></div>
</body>

</html>
