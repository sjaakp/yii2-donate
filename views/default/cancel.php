<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var sjaakp\donate\models\Donation $model
 */

$this->title = Yii::t('donate', 'Cancelled');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>

<p><?= Yii::t('donate', 'Your donation has been cancelled. No money is transferred.') ?></p>

<?= Html::a(Yii::t('donate', 'Resume'), $model->page, [ 'class' => 'btn btn-success']) ?>
