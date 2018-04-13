<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "test_route".
 *
 * @property int $id
 * @property string $from
 * @property string $to
 */
class TestRoute extends \yii\db\ActiveRecord
{
    public static $route = [];
    public static $findRoutes;
    public static $fromFirst;


    public static function getFindRoutes($from, $to)
    {
        if ($from != $to) {
            self::$fromFirst = $from;
            self::getAllRoutesByFrom($from, $to);
            return self::$findRoutes;
        }
        return "Error selected route!";

    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_route';
    }


    public static function getFroms()
    {
        $froms = TestRoute::find()->select(['from'])->distinct()->orderBy('from')->asArray()->all();
        return ArrayHelper::map($froms, 'from', 'from');
    }


    public static function getTos()
    {
        $tos = TestRoute::find()->select(['to'])->distinct()->orderBy('to')->asArray()->all();
        return ArrayHelper::map($tos, 'to', 'to');
    }


    public static function getAllRoutesByFrom($from, $to)
    {
        $routes = TestRoute::find()->where(['from' => $from])->all();

        foreach ($routes as $route) {
            $k = 0;
            if ($route->to == self::$fromFirst) {
                return;
            }

            if ($route->to == $to) {
                self::$route[] = $route->from.$route->to;
                self::$findRoutes[] = self::$route;
                array_splice(self::$route, count(self::$route)-1);
            }

            if ($route->to != $to && !in_array($route->from.$route->to, self::$route)) {
                self::$route[] = $route->from.$route->to;
                $k++;

                TestRoute::getAllRoutesByFrom($route->to, $to);

                array_splice(self::$route, count(self::$route)-$k);
            }
        }

    }


}
