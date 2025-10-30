<?php
echo '<pre>';
echo "現在の実行ディレクトリ: " . __DIR__ . "\n";

$target = __DIR__ . '/images/';
echo "画像ディレクトリが存在するか: " . (is_dir($target) ? '✅ 存在します' : '❌ ありません') . "\n";

$files = glob($target . '*');
if ($files) {
    echo "\n=== imagesフォルダ内のファイル一覧 ===\n";
    foreach ($files as $file) {
        echo basename($file) . "\n";
    }
} else {
    echo "\n画像フォルダにファイルがありません。\n";
}
