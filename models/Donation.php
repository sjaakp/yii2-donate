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

namespace sjaakp\donate\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "donation".
 *
 * @property int $id
 * @property string|null $email
 * @property float $amount
 * @property string|null $message
 * @property string|null $page
 * @property string|null $mollie
 * @property string|null $donated_at
 */
class Donation extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'donation';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'donated_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount'], 'required'],
            [['amount'], 'number'],
            [['message'], 'string'],
            [['donated_at'], 'safe'],
            [['email', 'page'], 'string', 'max' => 80],
            [['email'], 'email'],
            [['mollie'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => Yii::t('donate', 'Email'),
            'amount' => Yii::t('donate', 'Amount'),
            'message' => Yii::t('donate', 'Message'),
            'donated_at' => Yii::t('donate', 'Donated At'),
            'page' => Yii::t('donate', 'Page'),
            'mollie' => Yii::t('donate', 'Mollie payment ID'),
        ];
    }

    public function sendEmail($subject, $view, $mailOptions, $options = [])
    {
        if (is_null($mailOptions) || empty($this->email) || is_null(Yii::$app->mailer)) {
            return false;
        }
        $mailView = [
            'html' => "$view-html",
            'text' => "$view-text",
        ];
        $from = Yii::$app->params['supportEmail'] ?? Yii::$app->params['adminEmail'];
        $options['donation'] = $this;

        $mailer = Yii::$app->mailer;
        foreach ($mailOptions as $key => $value)  {
            $mailer->$key = $value;
        }
        return $mailer
            ->compose($mailView, $options)
            ->setFrom([$from => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject($subject)
            ->send();
    }
}
