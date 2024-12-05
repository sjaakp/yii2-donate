<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $donation sjaakp\donate\models\Donation */

$formatter = \Yii::$app->formatter;
$this->title = Yii::t('donate', 'Thanks');
?>
<div class="confirm-email">
    <p><?= Yii::t('donate', 'Hello {username},', [
            'username' => $donation->email
        ]) ?></p>

    <p><?= Yii::t('donate', 'Thank you for your {amount} donation of {date}.', [
            'amount' => $formatter->asCurrency($donation->amount),
            'date' => $formatter->asDatetime($donation->donated_at, 'medium')
        ]) ?></p>
</div>
