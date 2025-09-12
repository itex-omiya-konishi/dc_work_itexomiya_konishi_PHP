<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>WORK30</title>
</head>

<body>
    <div>画像一覧</div>
    <p><a href="work30.php">画像投稿ページへ</a></p>

    <?php
    // 入力バリデーション
    $errors = [];

    // タイトルのチェック
    if (empty($_POST['title'])) {
        $errors[] = 'タイトルが入力されていません。';
    } else {
        $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
    }

    // 画像ファイルのチェック
    if (!isset($_FILES['upload_image']) || $_FILES['upload_image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = '画像が選択されていません。';
    }

    // エラーがある場合は表示して終了
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
        echo '<p><a href="work30.php">戻る</a></p>';
        exit;
    }

    // 保存先
    $upload_dir = 'img/';
    $filename = basename($_FILES['upload_image']['name']);
    $save_path = $upload_dir . $filename;

    // ファイルを保存
    if (move_uploaded_file($_FILES['upload_image']['tmp_name'], $save_path)) {
        echo "<p>アップロード成功しました。</p>";

        // データベースへ接続
        $db = new mysqli('localhost', 'xb513874_18q1d', '2qtajdv62h', 'xb513874_gnjy0');
        if ($db->connect_error) {
            echo "<p style='color:red;'>DB接続エラー: " . $db->connect_error . "</p>";
            exit;
        }

        // SQLで保存
        $stmt = $db->prepare("INSERT INTO image_gallery (title, filename) VALUES (?, ?)");
        $stmt->bind_param('ss', $title, $filename);
        $stmt->execute();
        $stmt->close();
        $db->close();
    } else {
        echo "<p style='color:red;'>アップロード失敗しました。</p>";
    }

    echo "<hr>";
    echo "<p>投稿された画像</p>";

    // 投稿された画像を一覧表示
    // DB再接続
    $db = new mysqli('localhost', 'xb513874_18q1d', '2qtajdv62h', 'xb513874_gnjy0');
    if ($db->connect_error) {
        echo "<p style='color:red;'>DB接続エラー: " . $db->connect_error . "</p>";
        exit;
    }

    $result = $db->query("SELECT * FROM image_gallery ORDER BY created_at DESC");
    if ($result) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            $safe_title = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
            $safe_file = htmlspecialchars($row['filename'], ENT_QUOTES, 'UTF-8');
            echo "<li>";
            echo "<p>タイトル: {$safe_title}</p>";
            echo "<img src='img/{$safe_file}' alt='{$safe_title}' width='300'>";
            echo "</li>";
        }
        echo "</ul>";
    }

    $db->close();
    ?>
</body>

</html>
