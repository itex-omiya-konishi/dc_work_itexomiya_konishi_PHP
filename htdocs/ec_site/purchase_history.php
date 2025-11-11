<?php

/**
 * 購入履歴ページ
 */

require_once '../../include/config/const.php';
require_once '../../include/functions/common.php';
require_once '../../include/model/purchase_history_model.php';
require_once '../../include/view/purchase_history_view.php';

ensure_session_started();
check_login();

$dbh = db_connect();
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? '';

$orders = get_purchase_history($dbh, $user_id);

display_purchase_history($orders, $user_name);
