<?php

namespace frontend\modules\post\models\forms;

use frontend\models\Post;
use Yii;
use yii\base\Model;
use frontend\models\Comment;
use frontend\models\User;

class CommentForm extends Model
{
    public $text;

    private $user;
    private $post;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'required'],
            [['text'], 'string', 'length' => [3,250]]
        ];
    }

    /**
     * @param User $user
     */
    public function __construct(User $user = null, Post $post = null)
    {
        $this->user = $user;
        $this->post = $post;
    }


    public function saveComment()
    {
        if ($this->validate()){
            $comment = new Comment();

            $comment->text = $this->text;
            $comment->post_id = $this->post->id;
            $comment->user_id = $this->user->getId();
            $comment->created_at = time();

            return $comment->save();
        }
    }


    public function updateComment(Comment $comment)
    {
        if ($this->validate()){
            $comment->text = $this->text;
            return $comment->save();
        }
    }


}