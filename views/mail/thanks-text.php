<?php

/* @var $this yii\web\View */
/* @var $donation sjaakp\donate\models\Donation */

$formatter = \Yii::$app->formatter;
?>
<?= Yii::t('donate', 'Hello {username},', [
    'username' => $donation->email
]) ?>

<?= Yii::t('donate', 'Thank you for your {amount} donation of {date}.', [
    'amount' => $formatter->asCurrency($donation->amount),
    'date' => $formatter->asDatetime($donation->donated_at, 'medium')
]) ?>
