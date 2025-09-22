<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>WORK36</title>
    <style>
        .image-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 0;
        }

        .image-item {
            flex: 0 0 calc(33.33% - 20px);
            background-color: #ffffff;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            list-style: none;
        }

        .image-item.private {
            background-color: #f0f0f0;
            color: #666;
        }

        .image-item img {
            width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <?php
    require_once 'functions.php';
    $pdo = connectDB();

    // 公開・非公開切り替え処理
    if (isset($_GET['toggle_id'])) {
        $image_id = (int)$_GET['toggle_id'];
        $stmt = $pdo->prepare("SELECT is_public FROM image_gallery2 WHERE id = ?");
        $stmt->execute([$image_id]);
        if ($row = $stmt->fetch()) {
            $new_status = $row['is_public'] ? 0 : 1;
            $update = $pdo->prepare("UPDATE image_gallery2 SET is_public = ? WHERE id = ?");
            $update->execute([$new_status, $image_id]);
        }
        header("Location: work36_gallery.php?message=flag_updated");
        exit;
    }

    // フラグ更新メッセージ
    if (isset($_GET['message']) && $_GET['message'] === 'flag_updated') {
        echo "<p style='color: green;'>公開フラグを更新しました。</p>";
    }

    // 投稿処理（POST）
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = [];

        // タイトル検証
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        if (empty($title)) {
            $errors[] = 'タイトルが入力されていません。';
        } else {
            $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        }

        // 画像検証
        if (!isset($_FILES['upload_image']) || $_FILES['upload_image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = '画像が選択されていません。';
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $_FILES['upload_image']['tmp_name']);
            finfo_close($finfo);
            $allowed_types = ['image/jpeg', 'image/png'];
            if (!in_array($mime_type, $allowed_types)) {
                $errors[] = 'JPEG または PNG 形式の画像のみアップロードできます。';
            }
        }

        // エラー処理
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p style='color:red;'>$error</p>";
            }
            echo '<p><a href="work36.php">戻る</a></p>';
            exit;
        }

        // アップロード処理
        $upload_dir = 'img/';
        $ext = pathinfo($_FILES['upload_image']['name'], PATHINFO_EXTENSION);
        $unique_name = date('YmdHis') . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
        $save_path = $upload_dir . $unique_name;

        if (move_uploaded_file($_FILES['upload_image']['tmp_name'], $save_path)) {
            $stmt = $pdo->prepare("INSERT INTO image_gallery2 (title, filename) VALUES (?, ?)");
            $stmt->execute([$title, $unique_name]);
            echo "<p>アップロード成功しました。</p>";
        } else {
            echo "<p style='color:red;'>アップロード失敗しました。</p>";
        }
    }

    echo "<hr>";
    echo "<div>画像一覧</div>";
    echo "<p><a href='work36.php'>画像投稿ページへ</a></p>";

    // 一覧表示
    $stmt = $pdo->query("SELECT * FROM image_gallery2 ORDER BY created_at DESC");
    $images = $stmt->fetchAll();

    if ($images) {
        echo "<ul class='image-grid'>";
        foreach ($images as $row) {
            $safe_title = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
            $safe_file = htmlspecialchars($row['filename'], ENT_QUOTES, 'UTF-8');
            $is_public = $row['is_public'] ? true : false;
            $status_label = $is_public ? '公開中' : '非公開';
            $toggle_label = $is_public ? '非公開にする' : '公開にする';
            $item_class = $is_public ? 'image-item' : 'image-item private';

            echo "<li class='{$item_class}'>";
            echo "<p>タイトル: {$safe_title}</p>";
            echo "<img src='img/{$safe_file}' alt='{$safe_title}'>";
            echo "<p>状態: <strong>{$status_label}</strong></p>";
            echo "<a href='?toggle_id={$row['id']}'>{$toggle_label}</a>";
            echo "</li>";
        }
        echo "</ul>";
    }
    ?>
</body>

</html>
