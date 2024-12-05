<?php


use yii\widgets\ActiveForm;
use yii\helpers\Html;
use sjaakp\donate\Module;
use sjaakp\donate\models\Donation;

/* @var  yii\web\View $this */
/* @var Donation $model */
/* @var Module $module */
/* @var bool $small */

$don1 = Yii::t('donate', 'Donate');
$don2 = Yii::t('donate', 'Donate €');


$this->registerJs("document.querySelectorAll('.donation-form select').forEach((el) => {
    el.addEventListener('change', function(evt) {
        const amt = .01 * parseFloat(evt.target.value),
            btn = evt.target.closest('form').lastElementChild,
            cls = btn.classList;
        btn.innerHTML = amt ? '$don2' + amt.toFixed(2) : '$don1';
        if (amt) {
            cls.add('donation-armed');
        } else {
            cls.remove('donation-armed');
        }
    });
});");

$this->registerCss("
.donation-form {
    font-size: 75%;
    max-width: 24em;
    padding: 2em 1em;
    margin-inline: auto;
    border: .4em dotted darkgoldenrod;
    border-radius: 1em;
    h3 {
        text-align: center;
    }
    div {
        margin-bottom: 1rem;
    }
    label {
        display: inline-block;
        font-weight: bold;
        margin-bottom: .5rem;
    }
    input, select, textarea {
        color: fieldtext !important;
        display: block;
        width: 100%;
        padding: .375rem .75rem;
        font-size: inherit;
        font-family: system-ui;
        line-height: 1.5;
        color: #7b8a8b;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .1);
    }
    button, summary {
        color: #fff;
        background-color: #2c3e50;
        border-color: #2c3e50;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
        display: block;
        width: 100%;
        font-weight: bold;
        font-size: 1rem;
        text-align: center;
        vertical-align: middle;
        user-select: none;
        padding-block: .375rem;
        border-radius: .25rem;
        &:hover {
            background-color: #1e2b37;        
            border-color: #1e2b37;        
        }
        &.donation-armed {
            background-color: #18bc9c;
            border-color: #18bc9c;
            &:hover {
                background-color: #149a80;
                border-color: #128f76;
            } 
        }
        &+ form {
            margin-top: 1em;
        }
    }
    &[open] summary {
        opacity: .4;
    }
}");
$tag = $small ? 'details' : 'div';
$fieldOpts = [
    'options' => [],
    'inputOptions' => [],
    'labelOptions' => [],
    'errorOptions' => [ 'tag' => false ],
];
?>

<?= Html::beginTag($tag, ['class' => 'donation-form']) ?>
<?php if ($small) : ?>
    <summary class="btn-primary text-center"><?= Yii::t('donate', 'Donate!') ?></summary>
<?php endif; ?>

<?php $form = ActiveForm::begin([
    'action' => ["{$module->id}/donate"],
]) ?>

<?php if ($module->header) : ?>
    <h3><?= $module->header ?></h3>
    <hr />
<?php endif; ?>

<?= $form->field($model, 'amount', $fieldOpts)->dropDownList($module->choices, [
    'prompt' => Yii::t('donate', 'Please, select an amount...'),
    'required' => true,
    'encode' => false,
]) ?>

<?= $form->field($model, 'email', $fieldOpts)->textInput([
    'type' => 'email',
    'placeholder' => Yii::t('donate', 'donor@example.com (optional)'),
]) ?>

<?php if ($module->includeMessage) : ?>
<?= $form->field($model, 'message', $fieldOpts)->label(Yii::t('donate', 'Say something friendly'))->textArea([
    'placeholder' => Yii::t('donate', 'Keep up the good work!'),
]) ?>
<?php endif; ?>

<?= Html::submitButton($don1) ?>

<?php ActiveForm::end() ?>
<?= Html::endTag($tag) ?>
