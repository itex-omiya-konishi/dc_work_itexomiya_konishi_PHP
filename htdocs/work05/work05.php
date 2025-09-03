<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>WORK05</title>
</head>

<body>
    <?php
    /*$score = rand(1, 100);
    $score3 = $score % 3;
    $score6 = $score % 6;
    print '<p>$score: ' . $score . '</p>';

    var_dump($score3 == 0 && $score6 == 0);
    print ' </p>';

    var_dump($score3 == 0 && $score6 != 0);
    print '</p>';
    if ($score3 == 0 && $score6 == 0) {
        print '<p> 3と6の倍数です </p>';
    } else if ($score3 == 0 && $score6 != 0) {
        print '<p> 3の倍数で、6の倍数ではありません </p>';
    } else {
        print '<p> 倍数ではありません </p>';
    }
    */
    $random01 = rand(1, 10);
    $random02 = rand(1, 10);;
    $num1 = $random01 % 3;
    $num2 = $random02 % 3;
    if ($random01 == $random02 && $num1 == 0 && $num2 == 0) {
        print 'random01 = ' . $random01 . ', random02 =' . $random02 . 'です。 2つは同じ数です。2つの数字の中には3の倍数が2つ含まれています。';
    } else if ($random01 == $random02 && ($num1 == 0 && $num2 != 0) || ($num1 != 0 && $num2 == 0)) {
        print 'random01 = ' . $random01 . ', random02 =' . $random02 . 'です。 2つは同じ数です。2つの数字の中には3の倍数が1つ含まれています。';
    } else if ($random01 == $random02 && $num1 != 0 && $num2 != 0) {
        print 'random01 = ' . $random01 . ', random02 =' . $random02 . 'です。 2つは同じ数です。。2つの数字の中に3の倍数が含まれていません。';
    } else if ($random01 >= $random02 && $num1 == 0 && $num2 == 0) {
        print 'random01 = ' . $random01 . ', random02 =' . $random02 . 'です。 random01の方が大きいです。2つの数字の中には3の倍数が2つ含まれています。';
    } else if ($random01 >= $random02 && ($num1 == 0 && $num2 != 0) || ($num1 != 0 && $num2 == 0)) {
        print 'random01 = ' . $random01 . ', random02 =' . $random02 . 'です。 random01の方が大きいです。2つの数字の中には3の倍数が1つ含まれています。';
    } else if ($random01 >= $random02 && $num1 != 0 && $num2 != 0) {
        print 'random01 = ' . $random01 . ', random02 =' . $random02 . 'です。 random01の方が大きいです。2つの数字の中に3の倍数が含まれていません。';
    } else if ($random01 <= $random02 && $num1 == 0 && $num2 == 0) {
        print 'random01 = ' . $random01 . ', random02 =' . $random02 . 'です。 random02の方が大きいです。2つの数字の中には3の倍数が2つ含まれています。';
    } else if ($random01 <= $random02 && ($num1 == 0 && $num2 != 0) || ($num1 != 0 && $num2 == 0)) {
        print 'random01 = ' . $random01 . ', random02 =' . $random02 . 'です。 random02の方が大きいです。2つの数字の中には3の倍数が1つ含まれています。';
    } else if ($random01 <= $random02 && $num1 != 0 && $num2 != 0) {
        print 'random01 = ' . $random01 . ', random02 =' . $random02 . 'です。 random02の方が大きいです。2つの数字の中に3の倍数が含まれていません。';
    }
    ?>
</body>

</html>
