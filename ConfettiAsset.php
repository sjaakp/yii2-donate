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

use yii\web\AssetBundle;

/**
 * Class ConfettiAsset
 * @package sjaakp\donate
 */
class ConfettiAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'assets';

    public $js = [
        'confetti.js'
    ];
    public $depends = [
    ];
    public $publishOptions = [
//        'forceCopy' => true
    ];
}
