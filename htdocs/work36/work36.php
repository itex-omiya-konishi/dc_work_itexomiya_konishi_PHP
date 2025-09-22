<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>WORK36</title>
</head>

<body>
    <?php
    require_once 'functions.php';

    // DB接続確認メッセージ
    $pdo = connectDB();
    echo "データベースへの接続に成功しました。";
    ?>

    <form method="post" action="work36_gallery.php" enctype="multipart/form-data">
        <p>
        <div>画像投稿</div>
        </p>
        <div style="color: red;">画像タイトルを入力してください。</div>
        <p>画像タイトル：<input type="text" name="title"></p>
        <p>画像：<input type="file" name="upload_image"></p>
        <p><input type="submit" value="画像投稿"></p>
    </form>

    <?php
    echo "<p><a href='work36_gallery.php'>画像一覧ページへ</a></p>";
    echo "<hr>";
    echo "<p>投稿された画像</p>";
    ?>
</body>

</html>
