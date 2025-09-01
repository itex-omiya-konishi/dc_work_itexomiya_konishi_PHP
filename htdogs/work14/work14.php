<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>WORK14</title>
</head>

<body>
    <?php
    $stack = array();
    for ($i = 0; $i < 5; $i++) {
        $num = rand(1, 100);         // 乱数を生成
        array_push($stack, $num);
        if ($num % 2 == 0) {
            print $num . "(偶数)<br>";
        } else {
            print $num . "(奇数)<br>";
        }
    }
    ?>
</body>

</html>
