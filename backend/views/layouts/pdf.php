<?php

?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/admin/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/admin/favicon.ico" type="image/x-icon">


    <?php $this->head() ?>
</head>
<body>


<div class="wrap">

    <div class="container">

        <?= $content ?>
    </div>
</div>

<footer class="footer">

</footer>


</body>
</html>