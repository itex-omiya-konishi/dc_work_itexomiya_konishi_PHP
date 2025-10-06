<?php
require_once 'work39_model.php';
require_once 'work39_view.php';

$pdo = connectDB();

// 公開・非公開切替
if (isset($_GET['toggle_id'])) {
    toggleVisibility($pdo, (int)$_GET['toggle_id']);
    header("Location: work39_gallery.php?message=flag_updated");
    exit;
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>WORK39</title>
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
    // 投稿処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = validateUpload($_POST, $_FILES);

        if (!empty($errors)) {
            displayMessages($errors);
            echo '<p><a href="work39.php">戻る</a></p>';
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

    // メッセージ表示
    if (isset($_GET['message']) && $_GET['message'] === 'flag_updated') {
        displayMessages(['公開フラグを更新しました。'], 'green');
    }

    // 一覧表示
    $images = getAllImages($pdo);
    renderGallery($images);
    ?>
</body>

</html>
