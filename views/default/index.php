<?php

use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var float $total
 */

$this->title = Yii::t('donate', 'Donations');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>

<p><strong><?= Yii::t('donate', 'Total:') ?></strong> <?= Yii::$app->formatter->asCurrency($total) ?></p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'donated_at:datetime',
        'amount:currency',
        [
            'attribute' => 'email',
            'format' => 'email',
            'contentOptions' => [ 'title' => Yii::t('donate', 'Send an email') ],
        ],
        'message:text',
        'page:text'
    ],
    'tableOptions' => [
        'class' => 'table table-sm table-bordered'
    ]
]) ?>
