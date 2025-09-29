<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>擬似ログイン</title>
</head>

<body>
    <div>WORK37：擬似ログイン</div>
    <?php
    // Cookie読み込み
    $cookie_confirmation = isset($_COOKIE['cookie_confirmation']) ? 'checked' : '';
    $user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
    ?>
    <form action="work37.home.php" method="post">
        <label for="user_id">ユーザーID</label>
        <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>"><br>

        <label for="password">パスワード</label>
        <input type="password" id="password" name="password"><br>

        <input type="checkbox" name="cookie_confirmation" value="checked" <?php echo $cookie_confirmation; ?>>次回からユーザーIDの入力を省略する<br>

        <input type="submit" value="ログイン">
    </form>
</body>

</html>
