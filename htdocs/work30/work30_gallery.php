<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>WORK30</title>
    <style>
        .image-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            /* 画像の間隔 */
            padding: 0;
        }

        .image-item {
            flex: 0 0 calc(33.33% - 20px);
            /* 横3列（隙間引いた幅） */
            background-color: #ffffff;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            list-style: none;
        }

        .image-item.private {
            background-color: #f0f0f0;
            /* 非公開画像はグレー背景 */
            color: #666;
        }

        .image-item img {
            width: 100%;
            /* 親要素に合わせてリサイズ */
            height: auto;
        }
    </style>
</head>

<body>
    <div>画像一覧</div>
    <p><a href="work30.php">画像投稿ページへ</a></p>

    <?php

    // 公開/非公開切り替え処理
    if (isset($_GET['toggle_id'])) {
        $image_id = (int) $_GET['toggle_id'];

        $db = new mysqli('localhost', 'xb513874_18q1d', '2qtajdv62h', 'xb513874_gnjy0');
        if (!$db->connect_error) {
            $res = $db->query("SELECT is_public FROM image_gallery WHERE id = $image_id");
            if ($res && $row = $res->fetch_assoc()) {
                $new_status = $row['is_public'] ? 0 : 1;
                $db->query("UPDATE image_gallery SET is_public = $new_status WHERE id = $image_id");
            }
            $db->close();

            // リダイレクト時にメッセージ付きで戻す
            header("Location: work30_gallery.php?message=flag_updated");
            exit;
        }
    }

    // フラグ更新後のメッセージ
    if (isset($_GET['message']) && $_GET['message'] === 'flag_updated') {
        echo "<p style='color: green;'>公開フラグを更新しました。</p>";
    }

    // 投稿処理はPOSTリクエスト時だけ行うようにする
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
        } else {
            // MIMEタイプの確認（JPEG, PNG のみ許可）
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $_FILES['upload_image']['tmp_name']);
            finfo_close($finfo);

            $allowed_types = ['image/jpeg', 'image/png'];
            if (!in_array($mime_type, $allowed_types)) {
                $errors[] = 'JPEG または PNG 形式の画像のみアップロードできます。';
            }
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

        // ファイルの拡張子を取得
        $ext = pathinfo($_FILES['upload_image']['name'], PATHINFO_EXTENSION);

        // ユニークなファイル名を生成（例: 20250917123000_ab12cd34ef.png）
        $unique_name = date('YmdHis') . '_' . bin2hex(random_bytes(5)) . '.' . $ext;

        $save_path = $upload_dir . $unique_name;

        // ファイルを保存
        if (move_uploaded_file($_FILES['upload_image']['tmp_name'], $save_path)) {
            echo "<p>アップロード成功しました。</p>";

            // データベースへ接続
            $db = new mysqli('localhost', 'xb513874_18q1d', '2qtajdv62h', 'xb513874_gnjy0');
            if ($db->connect_error) {
                echo "<p style='color:red;'>DB接続エラー: " . $db->connect_error . "</p>";
                exit;
            }

            // SQLで保存（ユニーク名で）
            $stmt = $db->prepare("INSERT INTO image_gallery (title, filename) VALUES (?, ?)");
            $stmt->bind_param('ss', $title, $unique_name);
            $stmt->execute();
            $stmt->close();
            $db->close();
        } else {
            echo "<p style='color:red;'>アップロード失敗しました。</p>";
        }
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
        echo "<ul class='image-grid'>"; // グリッド開始

        while ($row = $result->fetch_assoc()) {
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

        echo "</ul>"; // グリッド終了
    }

    $db->close();
    ?>
</body>

</html>
