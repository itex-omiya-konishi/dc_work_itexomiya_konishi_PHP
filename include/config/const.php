<?php

/**
 * const.php
 * 共通定数設定ファイル
 * DB接続・パス設定・共通値の定義
 */

// ==============================
// データベース接続情報
// ==============================
define('DB_HOST', 'localhost');
define('DB_USER', 'xb513874_18q1d');
define('DB_PASS', '2qtajdv62h');
define('DB_NAME', 'xb513874_gnjy0');
define('DB_CHARSET', 'utf8mb4');

// PDO接続用DSN
define('DSN', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET);

// ==============================
// 文字コード設定
// ==============================
define('HTML_CHARACTER_SET', 'UTF-8');

// ==============================
// ディレクトリ・ファイル設定
// ==============================
// 商品画像の保存先（サーバー内絶対パス）
define('IMAGE_DIR', __DIR__ . '/../../htdocs/ec_site/images/');

// 商品画像の表示用（HTMLで利用する相対パス）
define('IMAGE_PATH', './images/');

// ==============================
// 画像アップロード設定
// ==============================
define('MAX_FILE_SIZE', 1048576); // 1MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png']); // 許可するMIMEタイプ

// ==============================
// タイムゾーン設定
// ==============================
date_default_timezone_set('Asia/Tokyo');
