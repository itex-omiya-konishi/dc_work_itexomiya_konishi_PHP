<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Work11</title>
</head>

<body>
    <?php

    for ($i = 1; $i <= 10; $i++) :
        // $i個の * を表示
        for ($s = 1; $s <= $i; $s++) {
            print "*";
        }
        print "<br>";

        // ! を表示
        print "!<br>";
    endfor;
    ?>
</body>

</html>
