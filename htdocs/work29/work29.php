<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>WORK29</title>
</head>

<body>
    <?php
    // DB接続
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
        if (isset($_POST['insert'])) {
            // --------------------
            // 挿入処理
            // --------------------
            $db->begin_transaction();

            $insert = "INSERT INTO product (product_id, product_code, product_name, price, category_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($insert);
            if (!$stmt) {
                $error_msg[] = "ステートメント準備失敗: " . $db->error;
            } else {
                $stmt->bind_param("iisii", $product_id, $code, $name, $price, $category);
                if ($stmt->execute()) {
                    echo "商品が挿入されました。<br>";
                    $db->commit();
                } else {
                    $error_msg[] = "挿入失敗: " . $stmt->error;
                    $db->rollback();
                    echo "挿入処理でエラーが発生しました（ロールバック実施）。<br>";
                }
                $stmt->close();
            }
        } elseif (isset($_POST['delete'])) {
            // --------------------
            // 削除処理
            // --------------------
            $db->begin_transaction();

            $delete = "DELETE FROM product WHERE product_id = ?";
            $stmt = $db->prepare($delete);
            if (!$stmt) {
                $error_msg[] = "ステートメント準備失敗: " . $db->error;
            } else {
                $stmt->bind_param("i", $product_id);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        echo "商品が削除されました。<br>";
                        $db->commit();
                    } else {
                        $error_msg[] = "該当商品が存在しません。";
                        $db->rollback();
                        echo "削除失敗（ロールバック実施）。<br>";
                    }
                } else {
                    $error_msg[] = "削除失敗: " . $stmt->error;
                    $db->rollback();
                    echo "削除処理でエラーが発生しました（ロールバック実施）。<br>";
                }
                $stmt->close();
            }
        }
    }

    // 商品の現在の状態表示（確認用）
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
