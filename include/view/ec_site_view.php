function display_register_form($message = '') {
echo '
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ユーザー登録</title>
</head>

<body>
    <h2>ユーザー登録</h2>
    <p style="color:red;">' . htmlspecialchars($message) . '</p>
    <form action="register.php" method="post">
        <label>ユーザーID：<input type="text" name="user_id"></label><br>
        <label>ユーザー名：<input type="text" name="username"></label><br>
        <label>パスワード：<input type="password" name="password"></label><br>
        <input type="submit" value="登録">
    </form>
</body>

</html>';
}
