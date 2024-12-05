<?php

/* @var yii\web\View $this */
/* @var sjaakp\donate\Module $module */
/* @var Mollie\Api\Resources\Payment $payment */

$this->title = Yii::t('donate', 'Dummy Payment');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>
<p><?= Yii::t('donate', 'This is a dummy payment for testing purposes only. No money is transferred') ?></p>

<hr />
<h3><?= $payment->description ?></h3>
<h4><?= Yii::$app->formatter->asCurrency($payment->amount->value, $payment->amount->currency) ?></h4>
<hr />
<p>
    <a href="<?= $payment->redirectUrl ?>" class="btn btn-primary"><?= Yii::t('donate', 'Complete Dummy Payment') ?></a>
    <a href="<?= $payment->cancelUrl ?>" class="btn btn-primary"><?= Yii::t('donate', 'Cancel Dummy Payment') ?></a>
</p>
