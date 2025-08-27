<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>TRY15</title>
</head>

<body>
    <?php
    // iが1から始まり、10以下の間繰り返し処理を行う
    for ($i = 1; $i <= 100; $i++) {
        if ($i % 3 == 0 && $i % 4 == 0) {
            print "<p>Fizz Buzz</p>";
        } else if ($i % 3 == 0 && $i % 4 != 0) {
            print "<p>Fizz</p>";
        } else if ($i % 3 == 0 && $i % 4 != 0) {
            print "<p>Buzz</p>";
        }
    }
    ?>
</body>

</html>
