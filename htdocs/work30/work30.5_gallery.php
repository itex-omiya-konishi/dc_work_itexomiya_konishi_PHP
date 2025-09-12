<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>WORK30</title>
</head>

<body>
    <div>画像一覧</div>
    <p><a href="work30.php">画像投稿ページへ</a></p>
    <?php
    // 入力バリデーション
    $errors = [];

    // タイトルのチェック
    if (empty($_POST['title'])) {
        $errors[] = 'タイトルが入力されていません。';
    } else {
        $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
    }

    // 画像ファイルのチェック
    if (!isset($_FILES['upload_image']) || $_FILES['upload_image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = '画像が選択されていません。';
    }
    // エラーがある場合は表示して終了
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
        echo '<p><a href="work30.php">戻る</a></p>';
        exit;
    }

    $save = 'img/' . basename($_FILES['upload_image']['name']);

    //ファイルを保存先ディレクトリに移動させる
    if (move_uploaded_file($_FILES['upload_image']['tmp_name'], $save)) {
        echo 'アップロード成功しました。';
    } else {
        echo 'アップロード失敗しました。';
    }

    echo "<p>画像一覧ページ。</p>";
    echo "<hr>"; // ここでPHPから水平線（<hr>タグ）を出力します
    echo "<p>投稿された画像</p>";
    ?>
    <div></div>
</body>

</html>
