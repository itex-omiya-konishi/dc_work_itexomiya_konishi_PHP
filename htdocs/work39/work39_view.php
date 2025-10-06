<?php
function displayMessages(array $messages, string $color = 'red'): void
{
    foreach ($messages as $msg) {
        echo "<p style='color:{$color};'>{$msg}</p>";
    }
}

function renderGallery(array $images): void
{
    echo "<div>画像一覧</div>";
    echo "<p><a href='work39.php'>画像投稿ページへ</a><hr></p>";

    if ($images) {
        echo "<ul class='image-grid'>";
        foreach ($images as $img) {
            $safe_title = htmlspecialchars($img['title'], ENT_QUOTES, 'UTF-8');
            $safe_file = htmlspecialchars($img['filename'], ENT_QUOTES, 'UTF-8');
            $is_public = (bool)$img['is_public'];
            $status_label = $is_public ? '公開中' : '非公開';
            $toggle_label = $is_public ? '非公開にする' : '公開にする';
            $item_class = $is_public ? 'image-item' : 'image-item private';

            echo "<li class='{$item_class}'>";
            echo "<p>タイトル: {$safe_title}</p>";
            echo "<img src='img/{$safe_file}' alt='{$safe_title}'>";
            echo "<p>状態: <strong>{$status_label}</strong></p>";
            echo "<a href='?toggle_id={$img['id']}'>{$toggle_label}</a>";
            echo "</li>";
        }
        echo "</ul>";
    }
}
