<?php

/**
 * const.php
 * 共通定数設定ファイル
 */

// ==============================
// DB接続情報
// ==============================
define('DB_HOST', 'localhost');
define('DB_USER', 'xb513874_18q1d');
define('DB_PASS', '2qtajdv62h');
define('DB_NAME', 'xb513874_gnjy0');
define('DB_CHARSET', 'utf8mb4');
define('DSN', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET);

// ==============================
// 文字コード
// ==============================
define('HTML_CHARACTER_SET', 'UTF-8');

// ==============================
// 商品画像関連
// ==============================

// サーバー上の保存先（絶対パス）
define('IMAGE_DIR', __DIR__ . '/../../htdocs/ec_site/images/');

// ブラウザ表示用の相対パス（htdocs/ec_site/product_list.php などから見て正しい位置）
define('IMAGE_PATH', 'images/');

// NO IMAGE 画像名
define('NO_IMAGE', 'no_image.png');

// ==============================
// 画像アップロード設定
// ==============================
define('MAX_FILE_SIZE', 1048576); // 1MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png']);

// ==============================
// タイムゾーン
// ==============================
date_default_timezone_set('Asia/Tokyo');
