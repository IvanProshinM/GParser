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
                $session->setFlash('error', 'Такой пользователь не найден');
                return $this->redirect('auth/login');
            }
            Yii::$app->user->login($authorization, 3600);
            $session->setFlash('success', 'Вы успешно авторизовались как' . ' ' . $authorization->userName);
            Yii::$app->controller->redirect('/parser/json-page');
        }
        return $this->render('authorization', ['model' => $model]);
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();
        $session = Yii::$app->session;
        $session->setFlash('success', 'Вы успешно разлогинились.');
        return $this->redirect(['auth/login']);
    }
}