<?php

// データベース接続
function connectDB()
{
    $dsn = 'mysql:host=localhost;dbname=xb513874_gnjy0;charset=utf8mb4';
    $user = 'xb513874_18q1d';
    $password = '2qtajdv62h';

    try {
        return new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        exit('DB接続エラー: ' . $e->getMessage());
    }
}

// 画像一覧を取得
function getAllImages(PDO $pdo)
{
    $stmt = $pdo->query("SELECT * FROM image_gallery ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

// 公開・非公開の切り替え
function toggleImageVisibility(PDO $pdo, int $image_id)
{
    $stmt = $pdo->prepare("SELECT is_public FROM image_gallery WHERE id = ?");
    $stmt->execute([$image_id]);
    if ($row = $stmt->fetch()) {
        $new_status = $row['is_public'] ? 0 : 1;
        $update = $pdo->prepare("UPDATE image_gallery SET is_public = ? WHERE id = ?");
        $update->execute([$new_status, $image_id]);
    }
}

// 投稿のバリデーション
function validateUpload(array $post, array $files): array
{
    $errors = [];

    // タイトル
    if (empty($post['title'])) {
        $errors[] = 'タイトルが入力されていません。';
    }

    // 画像
    if (!isset($files['upload_image']) || $files['upload_image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = '画像が選択されていません。';
    } else {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $files['upload_image']['tmp_name']);
        finfo_close($finfo);
        $allowed = ['image/jpeg', 'image/png'];
        if (!in_array($mime_type, $allowed)) {
            $errors[] = 'JPEG または PNG 形式の画像のみアップロードできます。';
        }
    }

    return $errors;
}

// 投稿をDBへ保存
function saveImageToDB(PDO $pdo, string $title, string $filename): bool
{
    $stmt = $pdo->prepare("INSERT INTO image_gallery (title, filename) VALUES (?, ?)");
    return $stmt->execute([$title, $filename]);
}

// メッセージ表示
function displayMessages(array $messages, string $color = 'red')
{
    foreach ($messages as $msg) {
        echo "<p style='color:{$color};'>{$msg}</p>";
    }
}
