<?php

namespace backend\models;

use Codeception\Lib\Driver\PostgreSql;
use frontend\models\Feed;
use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
 * @property string $filename
 * @property string $description
 * @property int $created_at
 * @property int $complaints
 *
 * @property Comment[] $comments
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
            'complaints' => 'Complaints',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id']);
    }

    public static function  getComplaints()
    {
        return Post::find()->where('complaints > 0')->orderBy('complaints DESC');
    }

    public function getImage()
    {
        return Yii::$app->storage->getFile($this->filename);
    }


    /**
     * Approve post (delete complaints) if it looks ok
     * @return boolean
     */
    public function approve()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key = "post:{$this->id}:complaints";
        $redis->del($key);

        $this->complaints = 0;
        return $this->save(false, ['complaints']);
    }
    
    
    public function deletePostLinks()
    {
        Feed::deleteAll(["post_id" => $this->id]);

        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key1 = "post:{$this->id}:likes";
        $key2 = "post:{$this->id}:complaints";
        $redis->del($key1);
        $redis->del($key2);

        $this->delete();


    }
    
    
}
