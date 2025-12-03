<?php

require_once __DIR__ . '/../functions/common.php';

/**
 * ログインフォーム
 */
function display_login_form($message = '', $message_type = '')
{
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
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
                margin-bottom: 10px;
                font-weight: bold;
            }

            .message.error {
                color: red;
            }

            .message.success {
                color: green;
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
                <div class="message <?php echo h($message_type); ?>">
                    <?php echo h($message); ?>
                </div>
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
 * 新規登録フォーム
 */
function display_register_form($message = '', $message_type = '')
{
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
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
                margin: 5px 0;
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

            input[type="submit"]:disabled {
                background-color: #bbb;
                cursor: not-allowed;
            }

            input[type="submit"]:hover {
                background-color: #c2185b;
            }

            .message {
                margin-bottom: 10px;
                font-weight: bold;
            }

            .message.error {
                color: red;
            }

            .message.success {
                color: green;
            }

            .error-text {
                color: red;
                font-size: 0.85em;
                margin: 0 0 8px 0;
                display: none;
                text-align: left;
                width: 90%;
                margin-left: 5%;
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
                <div class="message <?php echo h($message_type); ?>">
                    <?php echo h($message); ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="post" id="registerForm">

                <input type="text" name="user_id" id="user_id" placeholder="ユーザーID">
                <div id="userIdError" class="error-text"></div>

                <input type="text" name="username" id="username" placeholder="ユーザー名">
                <div id="usernameError" class="error-text"></div>

                <input type="password" name="password" id="password" placeholder="パスワード">
                <div id="passwordError" class="error-text"></div>

                <input type="submit" id="submitBtn" value="登録" disabled>
            </form>

            <p><a href="index.php">ログインページへ戻る</a></p>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", () => {

                const userId = document.getElementById("user_id");
                const username = document.getElementById("username");
                const password = document.getElementById("password");
                const submitBtn = document.getElementById("submitBtn");

                const userIdError = document.getElementById("userIdError");
                const usernameError = document.getElementById("usernameError");
                const passwordError = document.getElementById("passwordError");

                let validUserId = false;
                let validUsername = false;
                let validPassword = false;

                const USER_ID_REGEX = /^[A-Za-z0-9_]{5,}$/;
                const PASSWORD_REGEX = /^[A-Za-z0-9_]{8,}$/;

                // user_id チェック
                userId.addEventListener("input", () => {
                    const v = userId.value.trim();

                    if (v === "") {
                        userIdError.style.display = "none";
                        validUserId = false;
                    } else if (!USER_ID_REGEX.test(v)) {
                        userIdError.textContent = "ユーザーIDは半角英数字と _ 、5文字以上で入力してください。";
                        userIdError.style.display = "block";
                        validUserId = false;
                    } else {
                        userIdError.style.display = "none";
                        validUserId = true;
                    }
                    toggleButton();
                });

                // username チェック
                username.addEventListener("input", () => {
                    const v = username.value.trim();

                    if (v === "") {
                        usernameError.textContent = "ユーザー名を入力してください。";
                        usernameError.style.display = "block";
                        validUsername = false;
                    } else {
                        usernameError.style.display = "none";
                        validUsername = true;
                    }
                    toggleButton();
                });

                // password チェック
                password.addEventListener("input", () => {
                    const v = password.value.trim();

                    if (v === "") {
                        passwordError.style.display = "none";
                        validPassword = false;
                    } else if (!PASSWORD_REGEX.test(v)) {
                        passwordError.textContent = "パスワードは半角英数字と _ 、8文字以上で入力してください。";
                        passwordError.style.display = "block";
                        validPassword = false;
                    } else {
                        passwordError.style.display = "none";
                        validPassword = true;
                    }
                    toggleButton();
                });

                // 全チェックが OK の時だけ送信可能に
                function toggleButton() {
                    submitBtn.disabled = !(validUserId && validUsername && validPassword);
                }

                // JS無効時の保険
                document.getElementById("registerForm").addEventListener("submit", (e) => {
                    if (!(validUserId && validUsername && validPassword)) {
                        e.preventDefault();
                    }
                });
            });
        </script>

    </body>

    </html>
<?php
}
