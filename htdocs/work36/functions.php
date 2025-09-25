<?php
// DB接続
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

// バリデーション
function validateUpload(array $post, array $file): array
{
    $errors = [];
    $title = trim($post['title'] ?? '');

    if (empty($title)) {
        $errors[] = 'タイトルが入力されていません。';
    }

    if (!isset($file['upload_image']) || $file['upload_image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = '画像が選択されていません。';
    } else {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['upload_image']['tmp_name']);
        finfo_close($finfo);
        $allowed = ['image/jpeg', 'image/png'];
        if (!in_array($mime_type, $allowed)) {
            $errors[] = 'JPEG または PNG 形式の画像のみアップロードできます。';
        }
    }

    return $errors;
}

// 画像保存処理
function saveImage(PDO $pdo, string $title, array $file): bool
{
    $upload_dir = 'img/';
    $ext = pathinfo($file['upload_image']['name'], PATHINFO_EXTENSION);
    $unique_name = date('YmdHis') . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
    $save_path = $upload_dir . $unique_name;

    if (move_uploaded_file($file['upload_image']['tmp_name'], $save_path)) {
        $stmt = $pdo->prepare("INSERT INTO image_gallery2 (title, filename) VALUES (?, ?)");
        return $stmt->execute([$title, $unique_name]);
    }

    return false;
}

// 画像一覧取得
function getAllImages(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT * FROM image_gallery2 ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

// フラグ切り替え
function toggleVisibility(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare("SELECT is_public FROM image_gallery2 WHERE id = ?");
    $stmt->execute([$id]);

    if ($row = $stmt->fetch()) {
        $new_status = $row['is_public'] ? 0 : 1;
        $update = $pdo->prepare("UPDATE image_gallery2 SET is_public = ? WHERE id = ?");
        $update->execute([$new_status, $id]);
    }
}

// メッセージ表示
function displayMessages(array $messages, string $color = 'red'): void
{
    foreach ($messages as $msg) {
        echo "<p style='color:{$color};'>{$msg}</p>";
    }
}
