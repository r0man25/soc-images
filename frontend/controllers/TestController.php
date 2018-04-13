<?php
namespace frontend\controllers;

use frontend\models\forms\TestRouteForm;
use frontend\models\TestRoute;
use yii\web\Controller;
use Yii;

/**
 * Site controller
 */
class TestController extends Controller
{
    public function actionRoute()
    {
        $froms = TestRoute::getFroms();
        $tos = TestRoute::getTos();

        $model = new TestRouteForm();

        $view = [
            'model' => $model,
            'froms' => $froms,
            'tos' => $tos,
        ];
        
        if ($model->load(Yii::$app->request->post())) {
            $findRoutes = $model->getRoutes();
            $view['findRoutes'] = $findRoutes;
        }

        return $this->render('route', $view);
    }

}
