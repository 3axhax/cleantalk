<?php $ui = Yii::app()->ui; ?><!DOCTYPE html>
<html>
<head>
    <title><?=$this->pageTitle; ?></title>
    <meta name="Keywords" content="">
    <META name="verify-v1" content="eiaXbp3vim/5ltWb5FBQR1t3zz5xo7+PG7RIErXIb/M="/>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="/css/ruslania.css"/>
    <link rel="stylesheet" type="text/css" href="/css/jquery-bubble-popup-v3.css"/>
    <link rel="stylesheet" type="text/css" href="/css/autocomplete.css"/>
    <link rel="stylesheet" type="text/css" href="/css/prettyPhoto.css"/>
    <link href="/css/font-awesome/css/font-awesome.css " rel="stylesheet">
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <meta name="csrf" content="<?= MyHTML::csrf(); ?>"/>
</head>
<body>

<?=$content; ?>

</body>
</html>