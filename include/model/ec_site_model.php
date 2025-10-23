<?php

/**
 * ec_site_model.php
 * 共通モデル（DB接続・ユーザー認証など）
 */

require_once __DIR__ . '/../config/const.php';
require_once __DIR__ . '/../functions/common.php';

/**
 * DB接続
 * @return PDO
 */
function db_connect()
{
    try {
        $dbh = new PDO(DSN, DB_USER, DB_PASS);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $dbh->exec('SET NAMES utf8mb4');
        return $dbh;
    } catch (PDOException $e) {
        // エラー時は安全なメッセージで停止
        die('データベース接続エラー：' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
}

/**
 * ユーザ認証処理
 * @param PDO $dbh
 * @param string $user_id
 * @param string $password
 * @return bool
 */
function check_user($dbh, $user_id, $password)
{
    $sql = 'SELECT user_id, password FROM users WHERE user_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($user && password_verify($password, $user['password']));
}
