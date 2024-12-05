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
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\base\Module as YiiModule;
use yii\helpers\ArrayHelper;
use yii\console\Application as ConsoleApplication;
use yii\web\Application as WebApplication;
use yii\web\GroupUrlRule;

class Module extends YiiModule implements BootstrapInterface
{
    /**
     * @var array Keys: amount in cents, values: textual representations
     */
    public array $choices = [
        500 => '€5',
        1000 => '€10',
        2500 => '€25',
        5000 => '€50',
        10000 => '€100',
    ];

    /**
     * @var string|null Optional header in widget
     */
    public ?string $header = null;

    /**
     * @var bool Whether message field is included in widget. Default: true.
     */
    public bool $includeMessage = true;

    /**
     * @var bool Whether confetti is shown on 'thanks' page. Default: true.
     */
    public bool $confetti = true;

    /**
     * @var string|null Description displayed on payment page.
     * If null, defaults to 'Donation for <site name>'.
     */
    public ?string $description = null;

    /**
     * @var string|null Locale sent to payment site.
     * If null, defaults to site's <language> property.
     */
    public ?string $locale = null;

    /**
     * @var string|null Mollie API key, must be set.
     */
    public ?string $mollieApiKey = null;

    /**
     * @var array options for app mailer
     * @link https://www.yiiframework.com/doc/api/2.0/yii-mail-basemailer
     * If you want to override the mailer views, set viewPath.
     * If null, no mail is sent.
     */
    public $mailOptions = [
        'viewPath' => '@sjaakp/donate/views/mail',
        'htmlLayout' => '@sjaakp/donate/views/mail/layouts/html',
        'textLayout' => '@sjaakp/donate/views/mail/layouts/text',
    ];

    /**
     * @var bool|null  If true, performs dummy-payment on local system, for debugging and testing.
     * If null: set to true if YII_ENV === 'dev'.
     */
    public ?bool $localTest = null;

    /**
     * @var array access rule for action index.
     * Default: only accessible to authenticated visitors.
     */
    public array $indexAccess = [
        'allow' => true,
        'roles' => ['@']
    ];

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (! $this->mollieApiKey) {
            throw new InvalidConfigException('Donate: property "mollieApiKey" is not set.');
        }
        parent::init();

        if (! isset( Yii::$app->i18n->translations['donate']))   {
            Yii::$app->i18n->translations['donate'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@sjaakp/donate/messages',
            ];
        }

        if (is_null($this->description)) {
            $this->description = Yii::t('donate', 'Donation for {site}', [ 'site' => Yii::$app->name ]);
        }

        if (is_null($this->locale)) {
            $this->locale = str_replace('-', '_', Yii::$app->language);
        }

        if (is_null($this->localTest)) {
            $this->localTest = YII_ENV_DEV;
        }
    }


    public function bootstrap($app)
    {
        if ($app instanceof WebApplication) {
            $rules = new GroupUrlRule([
                'prefix' => $this->id,
                'rules' => [
                     '<a:[\w\-]+>/<id:\d+>' => 'default/<a>',
                     '<a:[\w\-]+>' => 'default/<a>',
                ]
            ]);
            $app->getUrlManager()->addRules([$rules], false);
        } else {
            /* @var $app ConsoleApplication */

            $app->controllerMap = ArrayHelper::merge($app->controllerMap, [
                'migrate' => [
                    'class' => '\yii\console\controllers\MigrateController',
                    'migrationNamespaces' => [
                        'sjaakp\donate\migrations'
                    ]
                ],
            ]);
        }
    }
}