<?php
require_once 'work39_model.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>WORK39</title>
</head>

<body>
    <?php
    $pdo = connectDB();
    echo "データベースへの接続に成功しました。";
    ?>

    <form method="post" action="work39_gallery.php" enctype="multipart/form-data">
        <p>
        <div>画像投稿</div>
        </p>
        <div style="color: red;">画像タイトルを入力してください。</div>
        <p>画像タイトル：<input type="text" name="title"></p>
        <p>画像：<input type="file" name="upload_image"></p>
        <p><input type="submit" value="画像投稿"></p>
    </form>

    <p><a href="work39_gallery.php">画像一覧ページへ</a></p>
</body>

</html>
