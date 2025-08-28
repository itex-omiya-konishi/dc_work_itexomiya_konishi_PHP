<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Work12</title>
</head>

<body>
    <?php
    // iが1から始まり、100以下の間繰り返し処理を行う
    /*$i = 1;
    while ($i <= 100) {
        if ($i % 3 == 0 && $i % 4 == 0) {
            print "<p>Fizz Buzz</p>";
        } else if ($i % 3 == 0 && $i % 4 != 0) {
            print "<p>Fizz</p>";
        } else if ($i % 3 != 0 && $i % 4 == 0) {
            print "<p>Buzz</p>";
        } else {
            print "<p>$i</p>";
        }
        $i++;
    }
   //
    $i = 0;
    while ($i <= 9) {
        $i++;
        $s = 0;
        while ($s <= 9) {
            $s++;
            print "$i*$s=" . ($i * $s) . "　";;
        }
        print "<br>";
    }
    */
    $i = 1;
    while ($i <= 10) {
        $s = 1;
        while ($s <= $i) {
            print "*";
            $s++;
        }
        print "<br>";

        if ($i < 10) {
            print "!<br>";
        }
        $i++;
    }
    ?>
</body>

</html>
