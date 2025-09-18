<?php
$dsn = 'mysql:host=localhost;dbname=xb513874_gnjy0';
$login_user = 'xb513874_18q1d';
$password = '2qtajdv62h';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>TRY46</title>
</head>

<body>
    <?php
    try {
        $db = new PDO($dsn, $login_user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();

        // わざと存在しないカラム名でエラーを発生
        $sql = "UPDATE product SET invalid_column = 160 WHERE product_id = 1";
        $result = $db->query($sql);

        $row = $result->rowCount();
        echo $row . '件更新しました。';

        $db->commit();
    } catch (PDOException $e) {
        echo "エラー発生: " . $e->getMessage();
        $db->rollBack();
    }
    ?>
</body>

</html>
