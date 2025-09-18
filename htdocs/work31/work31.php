<?php
$dsn = 'mysql:host=localhost;dbname=xb513874_gnjy0';
$login_user = 'xb513874_18q1d';
$password = '2qtajdv62h';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>WORK31</title>
</head>

<body>
    <?php
    try {
        // データベースへ接続
        $db = new PDO($dsn, $login_user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // product と category を結合して、category_id が 1 のデータを取得
        $sql = "
        SELECT p.product_id, p.product_name, c.category_name
        FROM product p
        JOIN category c ON p.category_id = c.category_id
        WHERE p.category_id = 1
    ";

        $stmt = $db->query($sql);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "商品ID: " . htmlspecialchars($row["product_id"]) . "<br>";
            echo "商品名: " . htmlspecialchars($row["product_name"]) . "<br>";
            echo "カテゴリ名: " . htmlspecialchars($row["category_name"]) . "<br><br>";
        }
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
        exit();
    }
    ?>
</body>

</html>
