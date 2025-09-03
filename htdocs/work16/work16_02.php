<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>WORK16</title>
</head>

<body>
    <div>フォームに入力した内容を取得する</div>
    <?php
    if (isset($_GET['name_text'])) {
        print '入力した内容： ' . htmlspecialchars($_GET['name_text'], ENT_QUOTES, 'UTF-8');
    } else {
        print '入力されていません';
    }
    if (isset($_GET["check_b"])) {
        echo '<br>選んだ選択肢：<br>';
        foreach ($_GET["check_b"] as $choice) {
            echo htmlspecialchars($choice, ENT_QUOTES, 'UTF-8') . '<br>';
        }
    }
    ?>
</body>

</html>
