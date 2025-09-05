<?php
$title_text = '';
$main_text = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 名前の取得
    if (isset($_POST['title_text'])) {
        $title_text = htmlspecialchars($_POST['title_text'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($_POST['main_text'])) {
        $main_text = htmlspecialchars($_POST['main_text'], ENT_QUOTES, 'UTF-8');
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>WORK19</title>
</head>

<body>
    <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <div><?php echo $title_text; ?>
            ：書き込み内容「<?php echo $main_text; ?>」</div>
        else: <div><?php ?>
            入力情報が不足しています「<?php ?>」</div>
    <?php endif; ?>
    <div>タイトル</div>
    <form method="post">
        <input type="text" name="title_text" value="<?php echo $title_text; ?>"><br>
        <div>書き込み内容</div>
        <input type="text" name="main_text" value="<?php echo $main_text; ?>"><br>

        <input type="submit" value="送信">
    </form>
</body>

</html>
