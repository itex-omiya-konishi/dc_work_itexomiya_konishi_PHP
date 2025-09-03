<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>TRY08</title>
</head>

<body>
    <?php
    $fruit01 = "りんご";
    $fruit02 = "バナナ";

    if ($fruit01 == "りんご" && $fruit02 == "バナナ") {
        echo "<p>fruit01はリンゴで、かつ、frute02はバナナです!</p>";
    }
    if ($fruit01 == "りんご" || $fruit02 = "りんご") {
        echo "<p>fruit01がりんご、あるいは、frute02がりんごのどちらかです!</p>";
    }
    if (!($fruit01 == "バナナ")) {
        echo "<p>fruit01はバナナではありません。</p>";
    }
    ?>
</body>

</html>
