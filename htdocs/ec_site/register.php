<?php
require_once '../../include/model/ec_site_model.php';
require_once '../../include/view/ec_site_view.php';

$dbh = db_connect();

$user_id = $_POST['user_id'] ?? '';
$password = $_POST['password'] ?? '';

if (check_user($dbh, $user_id, $password)) {
    header('Location: product_list.php');
    exit;
} else {
    echo '<p>ユーザー名またはパスワードが正しくありません。</p>';
    display_login_form($user_id);
}
