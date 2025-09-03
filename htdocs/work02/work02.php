<?php
$height = 170;         //変数$heightを定義し、値を代入
const WEIGHT = 60;    //定数WEIGHTを定義し、値を代入
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>WORK03</title>
</head>

<body>
    <?php
    print '身長 ';
    print $height;
    print 'cm ';
    print '体重 ' . WEIGHT . 'kg';
    // WEIGHT=50;
    ?>
</body>

</html>
