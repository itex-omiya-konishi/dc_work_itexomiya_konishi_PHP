<?php
require_once __DIR__ . '/../config/const.php';
require_once __DIR__ . '/../functions/common.php';

// ユーザ認証処理
function check_user($dbh, $user_id, $password)
{
    $sql = 'SELECT user_id, password FROM users WHERE user_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($user && password_verify($password, $user['password']));
}
