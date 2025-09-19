<?php
$dsn = 'mysql:host=localhost;dbname=xb513874_gnjy0';
$login_user = 'xb513874_18q1d';
$password = '2qtajdv62h';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>TRY48</title>
</head>

<body>
    <?php
    try {
        // データベースへ接続
        $db = new PDO($dsn, $login_user, $password);
        //PDOのエラー時にPDOExceptionが発生するように設定
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();    // トランザクション開始

        //クエリを生成する
        $sql = "UPDATE product SET price = ? WHERE product_id = ?";

        //prepareメソッドによるクエリの実行準備をする
        $stmt = $db->prepare($sql);

        //値をバインドする
        $stmt->bindValue(1, 170);
        $stmt->bindValue(2, '1');

        //クエリのの実行
        $stmt->execute();
        $row = $stmt->rowCount();
        echo $row . '件更新しました。';

        // SELECTクエリを準備
        $sql_select = "SELECT product_name, price FROM product WHERE product_id = :id";

        // prepareメソッドによるクエリの実行準備
        $stmt_select = $db->prepare($sql_select);

        // 値をバインド
        $stmt_select->bindValue(':id', 1);

        // クエリを実行
        $stmt_select->execute();

        // 結果を取得
        $result = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo "<p>商品名: " . htmlspecialchars($result['product_name'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p>価格: " . htmlspecialchars($result['price'], ENT_QUOTES, 'UTF-8') . "円</p>";
        } else {
            echo "<p>該当する商品が見つかりませんでした。</p>";
        }

        $db->commit();        // 正常に終了したらコミット
    } catch (PDOException $e) {
        echo $e->getMessage();
        if (isset($db)) {
            $db->rollBack();
        }      // エラーが起きたらロールバック
    }
    ?>
</body>

</html>
