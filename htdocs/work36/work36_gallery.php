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
        }

        .image-item {
            flex: 0 0 calc(33.33% - 20px);
            border: 1px solid #ccc;
            padding: 10px;
            box-sizing: border-box;
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

    // 公開・非公開切替
    if (isset($_GET['toggle_id'])) {
        toggleVisibility($pdo, (int)$_GET['toggle_id']);
        header("Location: work36_gallery.php?message=flag_updated");
        exit;
    }

    // フラグ更新メッセージ
    if (isset($_GET['message']) && $_GET['message'] === 'flag_updated') {
        displayMessages(['公開フラグを更新しました。'], 'green');
    }

    // 投稿処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = validateUpload($_POST, $_FILES);

        if (!empty($errors)) {
            displayMessages($errors);
            echo '<p><a href="work36.php">戻る</a></p>';
            exit;
        }

        $title = htmlspecialchars(trim($_POST['title']), ENT_QUOTES, 'UTF-8');
        $success = saveImage($pdo, $title, $_FILES);

        if ($success) {
            displayMessages(['アップロード成功しました。'], 'green');
        } else {
            displayMessages(['アップロード失敗しました。']);
        }
    }

    // 一覧表示
    echo "<div>画像一覧</div>";
    echo "<p><a href='work36.php'>画像投稿ページへ</a><hr></p>";

    $images = getAllImages($pdo);

    if ($images) {
        echo "<ul class='image-grid'>";
        foreach ($images as $img) {
            $safe_title = htmlspecialchars($img['title'], ENT_QUOTES, 'UTF-8');
            $safe_file = htmlspecialchars($img['filename'], ENT_QUOTES, 'UTF-8');
            $is_public = (bool)$img['is_public'];
            $status_label = $is_public ? '公開中' : '非公開';
            $toggle_label = $is_public ? '非公開にする' : '公開にする';
            $item_class = $is_public ? 'image-item' : 'image-item private';

            echo "<li class='{$item_class}'>";
            echo "<p>タイトル: {$safe_title}</p>";
            echo "<img src='img/{$safe_file}' alt='{$safe_title}'>";
            echo "<p>状態: <strong>{$status_label}</strong></p>";
            echo "<a href='?toggle_id={$img['id']}'>{$toggle_label}</a>";
            echo "</li>";
        }
        echo "</ul>";
    }
    ?>
</body>

</html>
