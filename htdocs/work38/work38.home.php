<?php
session_start();

// 未ログインならログインページへ
if (!isset($_SESSION['login_id']) || !isset($_SESSION['user_name'])) {
    header('Location: work38.php');
    exit();
}

// セッションからユーザー情報取得
$username = htmlspecialchars($_SESSION['user_name']);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログイン結果</title>
</head>

<body>
    <p><?php echo $username; ?>さん：ログイン中です。</p>

    <!-- ログアウトフォーム -->
    <form method="post" action="work38.php">
        <input type="submit" name="logout" value="ログアウト">
    </form>
</body>

</html>
