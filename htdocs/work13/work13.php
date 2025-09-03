<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Work13</title>
</head>

<body>
    <?php

    $i = 1;
    while ($i <= 10) :
        $s = 1;
        while ($s <= $i) :
            print "*";
            $s++;
        endwhile;
        print "<br>";

        if ($i < 10) {
            print "!<br>";
        }
        $i++;

    endwhile;

    ?>
</body>

</html>
