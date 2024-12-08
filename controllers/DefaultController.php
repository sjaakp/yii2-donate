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

namespace sjaakp\donate\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\helpers\Url;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use sjaakp\donate\models\Donation;
use Mollie\Api\MollieApiClient;

/**
 * Class DefaultController
 * @package sjaakp\donate
 */
class DefaultController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    $this->module->indexAccess
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'donate' => [ 'POST' ],
                    '*' => [ 'GET' ],
                ],
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Donation::find()->where([ 'status' => 'paid' ])
                ->orderBy([ 'donated_at' => SORT_DESC ]),
            'sort' => false
        ]);
        $total = Donation::find()->where([ 'status' => 'paid' ])->sum('amount');

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'module' => $this->module,
            'total' => $total,
        ]);
    }

    public function actionDonate()
    {
        $model = new Donation;
        $model->page = substr(Yii::$app->request->getReferrer(), strlen(Url::base(true)));

        if ($model->load(Yii::$app->request->post())) {
            $model->amount *= .01;  // from cents to euros
            $model->save();
            $payments = $this->setupMolliePayments();

            $payment = $payments->create([
                'amount' => [
                    'currency' => 'EUR',
                    'value' => number_format($model->amount, 2, '.', null)
                ],
                'description' => $this->module->description,
                'redirectUrl' => Url::toRoute(['thanks', 'id' => $model->id], true),
                'cancelUrl' => Url::toRoute(['cancel', 'id' => $model->id], true),
                'locale' => $this->module->locale,
                'metadata' => [
                    'donation_id' => $model->id,
                ]
            ]);

            $model->updateAttributes([ 'mollie' => $payment->id ]);
            $url = $payment->getCheckoutUrl();

            return $this->redirect($this->module->localTest ? ['dummy', 'mollie' => $payment->id] : $url, 303);
        } else {
            return $this->redirect($model->page);
        }
    }

    public function actionDummy($mollie)
    {
        $payment = $this->setupMolliePayments()->get($mollie);

        return $this->render('dummy', [
            'module' => $this->module,
            'payment' => $payment,
        ]);
    }

    public function actionThanks($id)
    {
        $model = $this->findModel($id);
        $payment = $this->setupMolliePayments()->get($model->mollie);
        $model->updateAttributes([ 'status' => $payment->status ]);

        $model->sendEmail(Yii::t('donate', 'Thanks'), 'thanks', $this->module->mailOptions);
        return $this->render('thanks', [
            'model' => $model,
            'module' => $this->module,
        ]);
    }

    public function actionCancel($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        return $this->render('cancel', [
            'model' => $model,
            'module' => $this->module,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Donation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('donate', 'The requested donation does not exist.'));
        }
    }

    protected function setupMolliePayments()
    {
        $apiClient = new MollieApiClient();
        $apiClient->setApiKey($this->module->mollieApiKey);
        return $apiClient->payments;
    }
}
