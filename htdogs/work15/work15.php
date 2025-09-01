<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>WORK15</title>
</head>

<body>
    <?php
    $class01 = [
        'tokugawa' => rand(1, 100),
        'oda' => rand(1, 100),
        'toyotomi' => rand(1, 100),
        'takeda' => rand(1, 100)
    ];
    $class02 = [
        'minamoto' => rand(1, 100),
        'taira' => rand(1, 100),
        'sugawara' => rand(1, 100),
        'fujiwara' => rand(1, 100)
    ];
    $school = array($class01, $class02);
    $ave1 = (($school[0]['tokugawa'] + $school[0]['oda'] + $school[0]['toyotomi'] + $school[0]['takeda']) / 4);
    $ave2 = (($school[1]['minamoto'] + $school[1]['taira'] + $school[1]['sugawara'] + $school[1]['fujiwara']) / 4);

    ?>
    <pre>
        <?php
        print_r($school);
        if ($school[0]['oda'] > $school[1]['sugawara']) {
            print "点数が高いのはclass01のodaさんで" . $school[0]['oda'] . "点<br>";
        } elseif ($school[0]['oda'] < $school[1]['sugawara']) {
            print "点数が高いのはclass02のsugawaraさんで" . $school[1]['sugawara'] . "点<br>";
        } else {
            print "２人の点数は同じです<br>";
        }
        print "class01の平均点は" . $ave1 . "点です<br>";
        print "class02の平均点は" . $ave2 . "点です<br>";
        ?>
    </pre>
</body>

</html>
