<?php
// ========================================
// user_model.php
// ユーザー登録・ログイン関連のDB処理
// ========================================

require_once __DIR__ . '/../functions/common.php';
require_once __DIR__ . '/../config/const.php';

/**
 * ユーザー登録処理
 * @param PDO $dbh データベース接続
 * @param string $user_id ユーザーID
 * @param string $user_name ユーザー名
 * @param string $password パスワード
 * @return bool 成功時 true、失敗時 false
 */
function register_user($dbh, $user_id, $user_name, $password)
{
    try {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (user_id, user_name, password, create_date, update_date)
                VALUES (?, ?, ?, NOW(), NOW())";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$user_id, $user_name, $hash]);
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
    $sql = "SELECT COUNT(*) FROM users WHERE user_id = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn() > 0;
}

/**
 * ログイン認証処理
 * @param PDO $dbh データベース接続
 * @param string $user_id ユーザーID
 * @param string $password パスワード
 * @return array|false 成功時は ['user_id'=>..., 'user_name'=>...]、失敗時 false
 */
function check_user($dbh, $user_id, $password)
{
    $sql = "SELECT user_id, user_name, password FROM users WHERE user_id = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        return [
            'user_id' => $row['user_id'],
            'user_name' => $row['user_name']
        ];
    } else {
        return false;
    }
}
