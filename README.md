yii2-donate
===========

#### Donation widget for Yii2 ####

[![Latest Stable Version](https://poser.pugx.org/sjaakp/yii2-ddonate/v/stable)](https://packagist.org/packages/sjaakp/yii2-donate)
[![Total Downloads](https://poser.pugx.org/sjaakp/yii2-donate/downloads)](https://packagist.org/packages/sjaakp/yii2-donate)
[![License](https://poser.pugx.org/sjaakp/yii2-donate/license)](https://packagist.org/packages/sjaakp/yii2-donate)

**Yii2-donate** is a module for the [Yii 2.0](https://yiiframework.com/ "Yii") PHP Framework
to handle donations. It makes use of the payment service provider
[Mollie](https://www.mollie.com/ "Mollie"), which is mainly active in
Western European countries.

**Yii2-donate** sports a [widget](https://www.yiiframework.com/doc/guide/2.0/en/structure-widgets),
which can be placed on any page (or even *all* pages).

## Basic functionality ##

If a visitor selects an amount and presses the 'Donate'-button, she is 
transfered to a Mollie payment page. If she successfully completes the
payment, she is redirected to the site's `donate/thanks` page, where she 
is rewarded with a joyful shower of confetti. If the visitor did supply
an email address, she receives a 'Thank you' mail. The 'thanks' page 
also sports a button to resume her visit to the site.

If the visitor cancels the payment, she is redirected to the site's
`donate/cancel` page, from where she can resume her surfing.

At any time, the site's administrator can get an overview of granted
donations  on the `donate` page.

## Prerequisites ##

You'll need a [Mollie account](https://help.mollie.com/hc/en-us/articles/210709969-How-do-I-create-an-account).
It's free, but depending on your country, you may need a valid
registration as a (small) business. You'll get two API keys, one for testing purposes
and one for the real work. One of the API keys is used tot initialize
the module.

It is strongly advised that the app uses
[Pretty URLs](https://www.yiiframework.com/doc/guide/2.0/en/runtime-routing#using-pretty-urls).

Because **Yii2-donate** may send emails, the `mailer` component of the application has to be up and running.
Be sure that the `'adminEmail'` parameter of the application has a sensible value. If you prefer, you may set
the `'supportEmail'` parameter as well; if set, **Yii2-donate** will use this.

## Installation ##

Install **Yii2-donate** in the usual way with [Composer](https://getcomposer.org/).
Add the following to the require section of your `composer.json` file:

`"sjaakp/yii2-donate": "*"`

or run:

`composer require sjaakp/yii2-donate`

You can manually install **yii2-comus** by [downloading the source in ZIP-format](https://github.com/sjaakp/yii2-donate/archive/master.zip).

#### Module ####

**Yii2-donate** is a [module](https://www.yiiframework.com/doc/guide/2.0/en/structure-modules#using-modules "Yii2")
in the Yii2 framework. It has to be configured
in the main configuration file, usually called `web.php` or `main.php` in the `config`
directory. Add the following to the configuration array:

    <?php
    // ...
    'modules' => [
        'donate' => [
            'class' => sjaakp\donate\Module::class,
            // several options
        ],
    ],
    // ...


The module has to be *bootstrapped*. Do this by adding the following to the
application configuration array:

    <php
    // ...
    'bootstrap' => [
        'donate',
    ]
    // ...

There probably already is a `bootstrap` property in your configuration file; just
add `'donate'` to it.

**Important**: the module should also be set up in the same way in the console configuration (usually
called `console.php`).

#### Console command ####

To complete the installation, a [console command](https://www.yiiframework.com/doc/guide/2.0/en/tutorial-console#usage "Yii2")
have to be run. This will create a database table for the donations:

    yii migrate

The migration applied is called `sjaakp\donate\migrations\m000000_000000_init`.

## The Donate widget ##

Placing the **Donate widget** in any view is trivial:

    <?php
    use sjaakp\donate\DonateWidget;
    ?>
    ...
    <?= DonateWidget::widget() ?>
    ...

The small, collapsed variant is obtained by:

    <?php
    use sjaakp\donate\DonateWidget;
    ?>
    ...
    <?= DonateWidget::widget([
        'small' => true
    ]) ?>
    ...

## Module options ##

The **Donate** module has a range of options. They are set in the application
configuration like so:

     <?php
     // ...
     'modules' => [
         'donate' => [
             'class' => sjaakp\donate\Module::class,
             'description' => 'Please, buy me a drink!',
             // ...
             // ... more options ...
         ],
     ],
     // ...

The options (most are optional) are:

- **mollieApiKey** `string` One of the API keys obtained from Mollie.
Not optional, must be set.
- **choices** `array` Amounts to select from. Keys are integers representing 
the amounts in cents, values are textual representations.
Example: `[ ..., 250 => '€2,50', 500 => '€5', ... ]`. Defaults: amounts of 5, 10, 25, 50, and 100.
- **header** `string|null` Text header appearing in the donate-widget. If
`null` (default) no header is rendered.
- **includeMessage** `bool` Whether  a 'friendly message' field is included in the widget.
Default: `true`.
- **confetti** `bool` Whether confetti is shown on the 'thanks' page.
Default: `true`.
- **description** `string|null` The textual description displayed on Mollie's 
payment page. If `null` (default), defaults to `'Donation for <site name>'`.
- **locale** `string|null` The locale sent to the payment site. If `null`,
defaults to site's [`language`](https://www.yiiframework.com/doc/api/2.0/yii-base-application#$language-detail "Yii2") property.
- **mailOptions** `array` Options for the [app mailer](https://www.yiiframework.com/doc/api/2.0/yii-mail-basemailer "Yii2").
  Default: see source.
- **localTest** `bool|null`  If `true`, performs a dummy-payment on the 
local system, useful for debugging and testing. If `null` (default),
**localTest** is set to `true` if `YII_ENV === 'dev'`, in other words
if the site is in the development environment.
- **indexAccess** `array` The [access rule](https://www.yiiframework.com/doc/guide/2.0/en/security-overview "Yii2")
for the donations overview (`donate` page). Default: only accessible to
authenticated visitors (`[ 'allow' => true, 'roles' => ['@'] ]`). For most
sites, you'll want to refine this.

## Internationalization ##

All of **Yii2-donate**'s utterances are translatable. The translations are
in the `'sjaakp\donate\messages'` directory.

You can override **Yii2-donate**'s translations by setting the application's
[message source](https://www.yiiframework.com/doc/guide/2.0/en/tutorial-i18n#2-configure-one-or-multiple-message-sources "Yii2") in the main configuration, like so:

     <?php
     // ...
     'components' => [
         // ... other components ...     
         'i18n' => [
              'translations' => [
                   // ... other translations ...
                  'donate' => [    // override donate's standard messages
                      'class' => yii\i18n\PhpMessageSource::class,
                      'basePath' => '@app/messages',  // this is a default
                      'sourceLanguage' => 'en-US',    // this as well
                  ],
              ],
         ],
         // ... still more components ...
     ]

The translations should be in a file called `'donate.php'`.

If you want a single or only a few messages translated and use **Yii2-donate**'s
translations for the main part, the trick is to set up `'i18n'` like above
and write your translation file something like:

      <?php
      // app/messages/nl/donate.php
      
      $donateMessages = Yii::getAlias('@sjaakp/donate/messages/nl/donate.php');
      
      return array_merge (require($donateMessages), [
         'Amount' => 'Bedrag in euro',   // your preferred translation
      ]);


At the moment, the only language implemented is Dutch. Agreed, it's only the world's
[52th language](https://en.wikipedia.org/wiki/List_of_languages_by_number_of_native_speakers "Wikipedia"),
but it happens to be my native tongue. Please, feel invited to translate
**Yii2-donate** in other languages. I'll be more than glad to include
them into **Yii2-donate**'s next release.

## Module ID ##

By default, the Module ID is `'donate'`. It is set in the module 
configuration. If necessary (for instance if there is a conflict with
another module or application component), you may set the Module
ID to something different. **Important:** in that case, the `moduleId` 
property of the **Donate** widget must be set to
this new value as well.

### FAQ ###

**Can I change the layout for the **Yii2-donate** views?**
- Use the [`EVENT_BEFORE_ACTION` event](https://www.yiiframework.com/doc/api/2.0/yii-base-controller#EVENT_BEFORE_ACTION-detail "Yii").
One easy way is to incorporate it in the module setup, like so:

      <?php
      // ...
      'modules' => [
         'donate' => [
             'class' => sjaakp\donate\Module::class,
             'description' => 'Please, buy me a drink!',
             'on beforeAction' => function ($event) {
                 $event->sender->layout = '@app/views/layouts/one_column';
             },
             // ... more options ...
         ],
      ],
      // ...


