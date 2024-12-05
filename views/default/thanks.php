<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use sjaakp\donate\ConfettiAsset;

/**
 * @var yii\web\View $this
 * @var sjaakp\donate\models\Donation $model
 * @var sjaakp\donate\Module $module
 */

if ($module->confetti)  {
    (new ConfettiAsset())->register($this);

    $this->registerJs('startConfetti();');
    $this->registerCss('#confetti-canvas {position: absolute;top: 0;}');
}

$this->title = Yii::t('donate', 'Thanks');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>

<p><?= Yii::t('donate', 'Many thanks for your donation!') ?></p>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'donated_at',
            'value' => function($model) { return Yii::$app->formatter->asDatetime($model->donated_at, 'long'); },
        ],
        'amount:currency',
//        'email:email',
        'message:text',
//        'page:text'
    ],
]) ?>

<?= Html::a(Yii::t('donate', 'Resume'), $model->page, [ 'class' => 'btn btn-success']) ?>
