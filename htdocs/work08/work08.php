<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>WORK08</title>
</head>

<body>
    <?php
    $score = rand(1, 100);
    $score3 = $score % 3;
    $score6 = $score % 6;
    print '<p>$score: ' . $score . '</p>';

    var_dump($score3 == 0 && $score6 == 0);
    print ' </p>';

    var_dump($score3 == 0 && $score6 != 0);
    print '</p>';

    switch (true) {
        case ($score3 == 0 && $score6 == 0):
            print '<p>3と6の倍数です</p>';
            break;
        case ($score3 == 0 && $score6 != 0):
            print '<p>3の倍数で、6の倍数ではありません</p>';
            break;
        default:
            print '<p>倍数ではありません</p>';
            break;
    }
    ?>
</body>

</html>
