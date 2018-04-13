<?php

namespace frontend\models\forms;

use frontend\models\TestRoute;
use Yii;
use yii\base\Model;

class TestRouteForm extends Model
{
    public $from;
    public $to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from'], 'required'],
            [['to'], 'required'],
        ];
    }


    public function getRoutes()
    {
        return TestRoute::getFindRoutes($this->from, $this->to);
    }

}