<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>WORK16</title>
</head>

<body>
    <div>入力内容の取得</div>
    <form method="get" action="work16_02.php">
        <input type="text" name="name_text"><br>
        <label><input type="checkbox" name="check_b[]" value="選択肢1"> 選択肢1</label><br>
        <label><input type="checkbox" name="check_b[]" value="選択肢2"> 選択肢2</label><br>
        <label><input type="checkbox" name="check_b[]" value="選択肢3"> 選択肢3</label><br>

        <input type="submit" value="送信">
    </form>
</body>

</html>
