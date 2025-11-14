<?php
// ======================================
// 共通ビュー関数群
// （ログイン・登録ページ表示）
// ======================================

/**
 * ログインフォームを表示
 */
function display_login_form($message = '')
{
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="utf-8">
        <title>ログインページ</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f0f8ff;
                text-align: center;
                margin: 0;
                padding: 0;
            }

            .container {
                margin: 50px auto;
                width: 320px;
                background: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            }

            h1 {
                font-size: 22px;
                color: #333;
            }

            form {
                margin-top: 20px;
            }

            input[type="text"],
            input[type="password"] {
                width: 90%;
                padding: 8px;
                margin: 8px 0;
                border-radius: 5px;
                border: 1px solid #ccc;
            }

            input[type="submit"] {
                background-color: #007bff;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
            }

            input[type="submit"]:hover {
                background-color: #0056b3;
            }

            .message {
                color: red;
                margin-bottom: 10px;
            }

            a {
                color: #007bff;
                text-decoration: none;
            }

            a:hover {
                text-decoration: underline;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h1>ログイン</h1>

            <?php if ($message !== ''): ?>
                <div class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form action="index.php" method="post">
                <input type="text" name="user_id" placeholder="ユーザーID"><br>
                <input type="password" name="password" placeholder="パスワード"><br>
                <input type="submit" value="ログイン">
            </form>

            <p><a href="register.php">新規登録はこちら</a></p>
        </div>
    </body>

    </html>
<?php
}
/**
 * ユーザー登録フォームを表示
 */
function display_register_form($message = '')
{
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="utf-8">
        <title>ユーザー登録ページ</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #fff0f5;
                text-align: center;
                margin: 0;
                padding: 0;
            }

            .container {
                margin: 50px auto;
                width: 320px;
                background: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            }

            h1 {
                font-size: 22px;
                color: #333;
            }

            form {
                margin-top: 20px;
            }

            input[type="text"],
            input[type="password"] {
                width: 90%;
                padding: 8px;
                margin: 8px 0;
                border-radius: 5px;
                border: 1px solid #ccc;
            }

            input[type="submit"] {
                background-color: #e91e63;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
            }

            input[type="submit"]:hover {
                background-color: #c2185b;
            }

            .message {
                color: red;
                margin-bottom: 10px;
            }

            a {
                color: #e91e63;
                text-decoration: none;
            }

            a:hover {
                text-decoration: underline;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h1>新規ユーザー登録</h1>

            <?php if ($message !== ''): ?>
                <div class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form action="register.php" method="post">
                <input type="text" name="user_id" placeholder="ユーザーID"><br>
                <input type="text" name="username" placeholder="ユーザー名"><br>
                <input type="password" name="password" placeholder="パスワード"><br>
                <input type="submit" value="登録">
            </form>

            <p><a href="index.php">ログインページへ戻る</a></p>
        </div>
    </body>

    </html>
<?php
}
?>
