<?php

/**
 * sjaakp/yii2-donate
 * ----------
 * Donation module for Yii2 framework
 * Works with Mollie payments
 * Version 1.0.0
 * Copyright (c) 2024
 * Sjaak Priester, Amsterdam
 * MIT License
 * https://github.com/sjaakp/yii2-donate
 * https://sjaakpriester.nl
 * https://mollie.com
 */

namespace sjaakp\donate;

use Yii;
use yii\base\Widget;
use sjaakp\donate\models\Donation;

class DonateWidget extends Widget
{
    public $moduleId = 'donate';

    public bool $small = false;

    public function run()
    {
        return $this->render('widget/donate', [
            'model' => new Donation(),
            'module' => Yii::$app->getModule($this->moduleId),
            'small' => $this->small,
        ]);
    }
}
