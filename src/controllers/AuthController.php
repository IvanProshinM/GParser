<?php

namespace app\controllers;

use app\models\loginModel;
use app\services\AuthorizationService;
use Yii;

class AuthController extends \yii\web\Controller
{

    /**
     * @var \app\services\AuthorizationService
     */

    private $authorizationService;

    public function __construct($id,
        $module,
                                AuthorizationService $authorizationService,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->authorizationService = $authorizationService;

    }

    public function actionLogin()
    {
        $model = new loginModel();
        $session = Yii::$app->session;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $authorization = $this->authorizationService->authorization($model);
            if (!$authorization) {
                $session->setFlash('error', 'Ошибка авторизации');
            }
            $session->setFlash('success', 'Вы успешно авторизовались как' . ' ' . $authorization->userName);
        }
        return $this->render('authorization', ['model' => $model]);
    }


    public function actionCheck()
    {
        if (Yii::$app->user->isGuest) {
            echo "гость";
        }
        else {
            echo Yii::$app->user->identity->login;
        }
    }
}