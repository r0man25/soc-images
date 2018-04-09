<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
 * @property string $filename
 * @property string $description
 * @property int $created_at
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'filename' => 'Filename',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    public function getImage()
    {
        return Yii::$app->storage->getFile($this->filename);
    }

    public function like(User $user)
    {
        $redis = Yii::$app->redis;
        $redis->sadd("post:{$this->id}:likes", $user->id);
        $redis->sadd("user:{$user->id}:likes", $this->id);
    }

    public function unlike(User $user)
    {
        $redis = Yii::$app->redis;
        $redis->srem("post:{$this->id}:likes", $user->id);
        $redis->srem("user:{$user->id}:likes", $this->id);
    }

    public function countLikes()
    {
        $redis = Yii::$app->redis;
        return $redis->scard("post:{$this->id}:likes");
    }

    public function isLikedBy(User $user)
    {
        $redis = Yii::$app->redis;
        return $redis->sismember("post:{$this->id}:likes", $user->id);
    }

    public function getCountComments()
    {
        return $this->hasMany(Comment::className(),['post_id' => 'id'])
            ->count();
    }

    public function complain(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key = "post:{$this->id}:complaints";

        if (!$redis->sismember($key, $user->id)) {
            $redis->sadd($key, $user->id);
            $this->complaints++;
            return $this->save(false, ['complaints']);
        }
    }
}
