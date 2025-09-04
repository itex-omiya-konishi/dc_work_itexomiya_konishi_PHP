<?php
$name_text = '';
$cho_genre = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 名前の取得
    if (isset($_POST['name_text'])) {
        $name_text = htmlspecialchars($_POST['name_text'], ENT_QUOTES, 'UTF-8');
    }

    // 選択肢の取得（複数選択）
    if (isset($_POST['cho_genre']) && is_array($_POST['cho_genre'])) {
        foreach ($_POST['cho_genre'] as $val) {
            $cho_genre[] = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>WORK17</title>
</head>

<body>
    <div>好きなものを選択してください。</div>
    <form method="post">
        <input type="text" name="name_text" value="<?php echo $name_text; ?>"><br>

        <input type="checkbox" name="cho_genre[]" value="選択肢1"
            <?php if (in_array('選択肢1', $cho_genre)) echo 'checked'; ?>>選択肢1<br>

        <input type="checkbox" name="cho_genre[]" value="選択肢2"
            <?php if (in_array('選択肢2', $cho_genre)) echo 'checked'; ?>>選択肢2<br>

        <input type="checkbox" name="cho_genre[]" value="選択肢3"
            <?php if (in_array('選択肢3', $cho_genre)) echo 'checked'; ?>>選択肢3<br>

        <input type="submit" value="送信">
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <div>入力した名前：「<?php echo $name_text; ?>」</div>
        <div>好きなものは：
            <?php echo !empty($cho_genre) ? implode('、', $cho_genre) : '選択されていません'; ?>
        </div>
    <?php endif; ?>
</body>

</html>
