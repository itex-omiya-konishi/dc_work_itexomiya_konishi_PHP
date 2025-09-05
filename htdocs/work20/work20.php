<?php
$file = 'data.txt';
$title_text = '';
$main_text = '';
$error_message = '';
$upload_dir = 'img/';

// アップロードディレクトリがなければ作成
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// POST送信時の処理
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        !empty($_POST['title_text']) &&
        !empty($_POST['main_text']) &&
        isset($_FILES['upload_image']) &&
        $_FILES['upload_image']['error'] === UPLOAD_ERR_OK
    ) {
        $title_text = htmlspecialchars($_POST['title_text'], ENT_QUOTES, 'UTF-8');
        $main_text = htmlspecialchars($_POST['main_text'], ENT_QUOTES, 'UTF-8');

        // 画像ファイルの処理
        $image_name = basename($_FILES['upload_image']['name']);
        $image_path = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['upload_image']['tmp_name'], $image_path)) {
            // テキスト形式: タイトル：本文：画像ファイル名
            $line = $title_text . "：" . $main_text . "：" . $image_path . "\n";

            // 先頭に追記（古い内容を後ろに）
            $existing_content = '';
            if (file_exists($file)) {
                $existing_content = file_get_contents($file);
            }

            file_put_contents($file, $line . $existing_content);

            // フォームの値をリセット
            $title_text = '';
            $main_text = '';
        } else {
            $error_message = "画像のアップロードに失敗しました";
        }
    } else {
        $error_message = "入力情報が不足しています";
    }
}

// 投稿一覧の読み込み
$lines = [];
if (file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>WORK20</title>
</head>

<body>

    <?php if (!empty($error_message)) : ?>
        <?php echo $error_message; ?>
    <?php endif; ?>

    <form method="post" action="" enctype="multipart/form-data">
        <div>タイトル</div>
        <input type="text" name="title_text" value="<?php echo $title_text; ?>"><br>

        <div>書き込み内容</div>
        <input type="text" name="main_text" value="<?php echo $main_text; ?>"><br><br>

        <div>画像ファイル</div>
        <input type="file" name="upload_image"><br><br>

        <input type="submit" value="送信">
    </form>

    <hr>

    <h3>投稿一覧</h3>
    <?php foreach ($lines as $line) : ?>
        <?php
        $parts = explode("：", $line);
        if (count($parts) >= 3) {
            $title = $parts[0];
            $main = $parts[1];
            $img_path = $parts[2];
        ?>
            <div>
                <strong><?php echo $title; ?>：</strong>
                <?php echo $main; ?>
                <?php if (file_exists($img_path)) : ?>
                    <br><img src="<?php echo $img_path; ?>" alt="画像" width="150">
                <?php else : ?>
                    <br>画像が見つかりません
                <?php endif; ?>
            </div>
            <hr>
        <?php } ?>
    <?php endforeach; ?>
</body>

</html>
