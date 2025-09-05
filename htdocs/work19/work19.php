<?php
$file = 'data.txt';
$title_text = '';
$main_text = '';
$error_message = '';

// POST送信時の処理
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['title_text']) && !empty($_POST['main_text'])) {
        $title_text = htmlspecialchars($_POST['title_text'], ENT_QUOTES, 'UTF-8');
        $main_text = htmlspecialchars($_POST['main_text'], ENT_QUOTES, 'UTF-8');

        $line = $title_text . "：" . $main_text . "\n";

        // ファイルの先頭に追記するには一時的に読み込んで新しい内容を先頭に追加
        $existing_content = '';
        if (file_exists($file)) {
            $existing_content = file_get_contents($file);
        }

        file_put_contents($file, $line . $existing_content); // 新しい内容を先頭に追加
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
    <title>WORK19</title>
</head>

<body>

    <?php if (!empty($error_message)): ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="post">
        <div>タイトル</div>
        <input type="text" name="title_text" value="<?php echo $title_text; ?>"><br>

        <div>書き込み内容</div>
        <input type="text" name="main_text" value="<?php echo $main_text; ?>"><br><br>

        <input type="submit" value="送信">
    </form>

    <?php foreach ($lines as $line): ?>
        <div><?php echo htmlspecialchars($line, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endforeach; ?>
</body>

</html>
