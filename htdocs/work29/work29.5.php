<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>WORK29</title>
</head>

<body>
    <?php
    // データベースへ接続
    $db = new mysqli('localhost', 'xb513874_18q1d', '2qtajdv62h', 'xb513874_gnjy0');
    if ($db->connect_error) {
        echo $db->connect_error;
        exit();
    } else {
        $db->set_charset("utf8");
    }


    // 商品情報
    $product_id = 21;
    $code = 1021;
    $name = "エシャロット";
    $price = 200;
    $category = 1;
    $error_msg = [];


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['price'])) {
            $price = $_POST['price'];
        }
        $db->begin_transaction();    // トランザクション開始



        $insert = "INSERT  product INTO product_id: =" . $product_id . "VALUES product_id = 1;" .
            "INSERT  product INTO product_code =" . $code . "VALUES product_id = 1;" .
            "INSERT  product INTO product_name =" . $name . "VALUES product_id = 1;" .
            "INSERT  product INTO price: =" . $price . "VALUES product_id = 1;" .
            "INSERT  product INTO price: =" . $category . "VALUES product_id = 1;";

        if ($result = $db->query($insart)) {
            $row = $db->affected_rows;
        } else {
            $error_msg[] = 'INSERT実行エラー [実行SQL]' . $insert;
        }
        //$error_msg[] = '強制的にエラーメッセージを挿入';

        //エラーメッセージ格納の有無によりトランザクションの成否を判定
        if (count($error_msg) == 0) {
            echo $row . '件追加しました。';
            $db->commit();    // 正常に終了したらコミット
        } else {
            echo '追加が失敗しました。';
            $db->rollback();    // エラーが起きたらロールバック
        }
        // 下記はエラー確認用。エラー確認が必要な際にはコメントを外してください。
        //var_dump($error_msg); 
    }

    $select = "SELECT product_name, price FROM product WHERE product_id = 1;";
    if ($result = $db->query($select)) {
        // 連想配列を取得
        while ($row = $result->fetch_assoc()) {
            $product_name = $row["product_name"];
            $price = $row["price"];
        }
        // 結果セットを閉じる
        $result->close();
    }
    if ($product_id != 150) {
        $product_id_val = 21;
        $code_val = 1021;
        $name_val = "エシャロット";
        $price_val = 200;
        $category_val = 1;
    } else {
    }
    $db->close();
    // 接続を閉じる


    //商品の現在の状態表示（確認用）

    $select = "SELECT product_name, price FROM product WHERE product_id = ?";
    $stmt = $db->prepare($select);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($product_name, $price);
        $stmt->fetch();
        echo "<p>現在の商品：{$product_name}（価格：{$price}円）</p>";
    } else {
        echo "<p>現在、商品ID {$product_id} は登録されていません。</p>";
    }

    $stmt->close();
    $db->close();
    ?>

    <form method="post">
        <input type="submit" name="insert" value="挿入">
        <input type="submit" name="delete" value="削除">
    </form>
</body>

</html>
