<?php
// ========================================
// user_model.php
// ユーザー登録・ログイン関連のDB処理
// ========================================

require_once __DIR__ . '/../functions/common.php';
require_once __DIR__ . '/../config/const.php';

/**
 * ユーザー登録処理
 * 
 * @param PDO $dbh データベース接続
 * @param string $user_id ユーザーID（例：ec_adminなど）
 * @param string $username ユーザー名
 * @param string $password パスワード
 * @return bool 成功時 true、失敗時 false
 */
function register_user($dbh, $user_id, $username, $password)
{
    try {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // users テーブルに挿入
        $sql = "INSERT INTO users (user_id, user_name, password, create_date, update_date)
                VALUES (?, ?, ?, NOW(), NOW())";

        $stmt = $dbh->prepare($sql);
        $stmt->execute([$user_id, $username, $hash]);
        return true;
    } catch (PDOException $e) {
        // エラー時は false を返す（例: 重複・SQLエラーなど）
        return false;
    }
}

/**
 * ユーザーIDの重複確認
 * 
 * @param PDO $dbh データベース接続
 * @param string $user_id ユーザーID
 * @return bool 存在する場合 true
 */
function check_user_exists($dbh, $user_id)
{
    $sql = "SELECT COUNT(*) FROM users WHERE user_id = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn() > 0;
}

/**
 * ログイン認証処理
 * 
 * @param PDO $dbh データベース接続
 * @param string $user_id ユーザーID
 * @param string $password パスワード
 * @return array|false ログイン成功時はユーザー情報配列、失敗時は false
 */
function check_user($dbh, $user_id, $password)
{
    $sql = "SELECT user_id, user_name, password FROM users WHERE user_id = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        // ログイン成功 → ユーザー情報配列を返す
        return $row;
    } else {
        // ログイン失敗
        return false;
    }
}

/**
 * ログイン中のユーザー情報を取得
 * 
 * @return array|null ['user_id' => string, 'user_name' => string] or null
 */
function get_login_user()
{
    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_SESSION['user_id'], $_SESSION['user_name'])) {
        return [
            'user_id'   => $_SESSION['user_id'],
            'user_name' => $_SESSION['user_name']
        ];
    }
    return null;
}

/**
 * ログアウト処理
 */
function logout()
{
    if (!isset($_SESSION)) {
        session_start();
    }

    $_SESSION = [];
    session_destroy();
}
