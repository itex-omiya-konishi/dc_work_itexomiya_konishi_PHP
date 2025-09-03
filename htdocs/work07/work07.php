<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>WORK07</title>
</head>

<body>
    <?php
    $score = rand(1, 100);
    $score3 = $score % 3;
    $score6 = $score % 6; ?>
    <p>$score: <?php echo $score; ?></p>

    <?php var_dump($score3 == 0 && $score6 == 0); ?>
    <?php var_dump($score3 == 0 && $score6 != 0); ?>

    <?php if ($score3 == 0 && $score6 == 0) : ?>
        <p> 3と6の倍数です </p>
    <?php elseif ($score3 == 0 && $score6 != 0) : ?>
        <p> 3の倍数で、6の倍数ではありません </p>
    <?php else : ?>
        <p> 倍数ではありません </p>
    <?php endif; ?>

</body>

</html>
