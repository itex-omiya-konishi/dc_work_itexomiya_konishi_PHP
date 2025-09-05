<?php
$check_data = '';
$check_data2 = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['check_data'])) {
        $check_data = htmlspecialchars($_POST['check_data'], ENT_QUOTES, 'UTF-8');
    }

    if (isset($_POST['check_data2'])) {
        $check_data2 = htmlspecialchars($_POST['check_data2'], ENT_QUOTES, 'UTF-8');
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>WORK21</title>
</head>

<body>

    <!-- input① -->
    <form method="post">
        <div>半角英で入力を行ってください。</div>
        <input type="text" name="check_data" value="<?php echo $check_data ?>">
        <input type="submit" value="送信">
    </form>

    <?php
    if ($check_data !== '') {
        // 半角英字（大文字小文字）のみ
        if (!preg_match("/^[a-zA-Z]+$/", $check_data)) {
            echo "<div>正しい入力形式ではありません。</div>";
        }

        // "dc" が含まれている
        if (strpos($check_data, 'dc') !== false) {
            echo "<div>ディーキャリアが含まれています</div>";
        }

        // "end" で終わっている
        if (preg_match("/end$/", $check_data)) {
            echo "<div>終了です！</div>";
        }
    }
    ?>

    <!-- input② -->
    <form method="post">
        <div>携帯電話番号を入力してください（例：090-1234-5678）</div>
        <input type="text" name="check_data2" value="<?php echo $check_data2 ?>">
        <input type="submit" value="送信">
    </form>

    <?php
    if ($check_data2 !== '') {
        // 携帯電話番号の正規表現
        if (!preg_match("/^(090|080|070)-\d{4}-\d{4}$/", $check_data2)) {
            echo "<div>携帯電話番号の形式ではありません。</div>";
        }
    }
    ?>

</body>

</html>
