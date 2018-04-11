<?php

namespace frontend\models\events;

use yii\base\Event;
use frontend\models\User;
use frontend\models\Post;

class FollowUserEvent extends Event
{

    /**
     * @var User
     */
    public $user;


    /**
     * @var User
     */
    public $currentUser;


    public function getUser(): User
    {
        return $this->user;
    }


    public function getCurrentUser(): User
    {
        return $this->currentUser;
    }

}