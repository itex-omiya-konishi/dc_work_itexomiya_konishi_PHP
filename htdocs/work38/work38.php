<?php
session_start();

// ✅ ログアウト処理：ログアウトボタンから来たとき
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    // セッション変数をクリア
    $_SESSION = [];

    // セッションクッキーを削除
    if (isset($_COOKIE[session_name()])) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"]);
    }

    // セッションを完全に破棄
    session_destroy();

    // ✅ メッセージを一時的に表示させたいなら
    $logout_message = "ログアウトされました。";
}

// ✅ ログイン中ならリダイレクト（ログアウト後の再遷移を防ぐ）
if (isset($_SESSION['login_id'])) {
    header('Location: work38.home.php');
    exit();
}

// ✅ エラーフラグによるメッセージ表示
if (isset($_SESSION['err_flg']) && $_SESSION['err_flg']) {
    $error_message = "ログインに失敗しました：正しいログインID（半角英数字）を入力してください。";
    $_SESSION['err_flg'] = false;
}

// ✅ Cookie読み込み（ユーザーIDの保持用）
$cookie_confirmation = isset($_COOKIE['cookie_confirmation']) ? 'checked' : '';
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>セッション</title>
</head>

<body>
    <div>WORK38：セッション</div>

    <?php
    // ✅ ログアウトメッセージ表示
    if (isset($logout_message)) {
        echo "<p style='color:green;'>$logout_message</p>";
    }

    // ✅ エラーメッセージ表示
    if (isset($error_message)) {
        echo "<p style='color:red;'>$error_message</p>";
    }
    ?>

    <form action="work38.auth.php" method="post">
        <label for="user_id">ユーザーID</label>
        <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>"><br>

        <label for="password">パスワード</label>
        <input type="password" id="password" name="password"><br>

        <input type="checkbox" name="cookie_confirmation" value="checked" <?php echo $cookie_confirmation; ?>>次回からユーザーIDの入力を省略する<br>

        <input type="submit" value="ログイン">
    </form>
</body>

</html>
