<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Work10</title>
</head>

<body>
    <?php
    // iが1から始まり、100以下の間繰り返し処理を行う
    /*for ($i = 1; $i <= 100; $i++) {
        if ($i % 3 == 0 && $i % 4 == 0) {
            print "<p>Fizz Buzz</p>";
        } else if ($i % 3 == 0 && $i % 4 != 0) {
            print "<p>Fizz</p>";
        } else if ($i % 3 != 0 && $i % 4 == 0) {
            print "<p>Buzz</p>";
        } else {
            print "<p>$i</p>";
        }
    }
    for ($i = 1; $i <= 9; $i++) {

        for ($s = 1; $s <= 9; $s++) {
            print "$i*$s=" . ($i * $s) . "　";;
        }
        print "<br>";
    }*/
    for ($i = 1; $i <= 10; $i++) {
        // $i個の * を表示
        for ($s = 1; $s <= $i; $s++) {
            print "*";
        }
        print "<br>";
        // ! を表示
        print "!<br>";
    }
    ?>
</body>

</html>
