<?php
require_once '../../include/model/ec_site_model.php';
include_once '../../include/view/ec_site_view.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>EC SITE</title>
    <style>
        .name {
            background-color: aqua;
        }

        .title {
            text-align: center;
        }

        form {
            text-align: center;
        }

        .login {
            text-align: center;
            background-color: blue;
            color: white;
        }

        a {
            color: blue;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="name">EC SITE</div>
    <div class="title">ログイン</div>
    <?php
    // Cookie読み込み
    $cookie_confirmation = isset($_COOKIE['cookie_confirmation']) ? 'checked' : '';
    $user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
    ?>
    <form action="register.php" method="post">
        <label for="user_id">ユーザー名</label>
        <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>"><br>

        <label for="password">パスワード</label>
        <input type="password" id="password" name="password"><br>

        <input type="checkbox" name="cookie_confirmation" value="checked" <?php echo $cookie_confirmation; ?>>次回からユーザーIDの入力を省略する<br>

        <input class="login" type="submit" value="登録">
        <div>
            <a href="register.php">新規登録ページへ</a>
        </div>
    </form>
</body>

</html>
