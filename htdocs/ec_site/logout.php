<?php
session_start();

// セッションを破棄
$_SESSION = [];
session_destroy();

// ログインページへリダイレクト
header('Location: login.php'); // ログインページのパスに変更
exit;
