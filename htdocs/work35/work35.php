<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>TRY49</title>
</head>

<body>
    <?php

    $ran = rand(1, 10);
    //引数あり・返り値ありの関数を実行し、返り値を出力  
    $function_num = make_function_num($ran);
    echo $function_num;

    //引数：あり、返り値：ありの関数
    function make_function_num($num)
    {
        if ($num % 2 == 0) {
            $ran10 = $num * 10;
            $str = "<p>引数：" . $num . "<p>返り値：" . $ran10 . "</p>";
            return $str;
        } else {
            $ran100 = $num * 100;
            $str = "<p>引数：" . $num . "<p>返り値：" . $ran100 . "</p>";
            return $str;
        }
    }

    ?>
</body>

</html>
