<?php

/**
 * 共通関数ファイル
 * - HTMLエスケープ
 * - DB接続
 * - セッション管理
 * - ログインチェック
 * - ログインユーザー取得
 *
 * HTML特殊文字をエスケープ
 */
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}
/**
 * データベース接続
 */
function db_connect()
{
    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $dbh = new PDO($dsn, DB_USER, DB_PASS);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    } catch (PDOException $e) {
        exit('データベース接続エラー：' . $e->getMessage());
    }
}
/**
 * セッションが未開始なら開始する
 */
function ensure_session_started()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
/**
 * ログインチェック
 * 未ログインの場合はログインページ(index.php)へリダイレクト
 */
function check_login()
{
    ensure_session_started();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../ec_site/index.php'); // ログインページへ
        exit;
    }
}
