<?php
function display_login_form($user_id = '', $cookie_checked = '')
{
?>
    <form action="login.php" method="post">
        <label for="user_id">ユーザー名</label>
        <input type="text" id="user_id" name="user_id" value="<?php echo h($user_id); ?>"><br>

        <label for="password">パスワード</label>
        <input type="password" id="password" name="password"><br>

        <input type="checkbox" name="cookie_confirmation" value="checked" <?php echo $cookie_checked; ?>>次回からユーザーIDの入力を省略する<br>

        <input class="login" type="submit" value="ログイン">
        <div><a href="register.php">新規登録ページへ</a></div>
    </form>
<?php
}
?>
