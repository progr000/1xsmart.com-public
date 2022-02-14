<?php

/** @var $this \yii\web\View */
/** @var $content string */

?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <?= $this->render('head'); ?>
    </head>
    <body lang="<?= Yii::$app->language ?>">

    <?php $this->beginBody() ?>


    <?= $content ?>


    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>