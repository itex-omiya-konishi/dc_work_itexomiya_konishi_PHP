<?php

/**
 * ログアウト処理
 * - セッションを安全に破棄
 * - トップページ（ログインページ）へリダイレクト
 */

session_start();

// セッション変数を全削除
$_SESSION = [];
// セッションCookieが存在する場合は削除
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}
// セッションを完全に破棄
session_destroy();
// ログインページへリダイレクト
header('Location: index.php');
exit;
