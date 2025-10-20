<?php
// ========================================
// user_model.php
// ユーザー登録・ログイン関連のDB処理
// ========================================

// 共通関数・定数の読み込み
require_once __DIR__ . '/../functions/common.php';
require_once __DIR__ . '/../config/const.php';

/**
 * ユーザー登録処理
 * @param PDO $dbh データベース接続
 * @param string $user_id ユーザーID
 * @param string $username ユーザー名
 * @param string $password パスワード
 * @return bool 成功時 true、失敗時 false
 */
function register_user($dbh, $user_id, $username, $password)
{
    try {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (user_id, username, password) VALUES (?, ?, ?)";
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
 * @param PDO $dbh データベース接続
 * @param string $user_id ユーザーID
 * @return bool 存在する場合 true
 */
function check_user_exists($dbh, $user_id)
{
    $sql = "SELECT COUNT(*) FROM user WHERE user_id = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn() > 0;
}

/**
 * ログイン認証処理
 * @param PDO $dbh データベース接続
 * @param string $user_id ユーザーID
 * @param string $password パスワード
 * @return bool ログイン成功時 true
 */
function check_user($dbh, $user_id, $password)
{
    $sql = "SELECT password FROM user WHERE user_id = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        return true;
    } else {
        return false;
    }
}
