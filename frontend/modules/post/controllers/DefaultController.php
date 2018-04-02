<?php

namespace frontend\modules\post\controllers;

use frontend\models\Comment;
use frontend\models\Post;
use frontend\modules\post\models\forms\CommentForm;
use frontend\modules\post\models\forms\PostForm;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\web\Response;

/**
 * Default controller for the `post` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $model = new PostForm(Yii::$app->user->identity);

        if ($model->load(Yii::$app->request->post())) {
            $model->picture = UploadedFile::getInstance($model, 'picture');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Post added');
                return $this->goHome();
            }
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionView($id)
    {
        $currentUser = Yii::$app->user->identity;

        $post = $this->findPost($id);

        $comments = Comment::getCommentsByPost($post->id);
        $commentForm = new CommentForm($currentUser, $post);

        return $this->render('view', [
            'post' => $post,
            'currentUser' => $currentUser,
            'commentForm' => $commentForm,
            'comments' => $comments,
        ]);
    }

    public function actionComment($id)
    {
        $currentUser = Yii::$app->user->identity;

        $post = $this->findPost($id);

        $model = new CommentForm($currentUser, $post);

        if ($model->load(Yii::$app->request->post()) && $model->saveComment()){
            Yii::$app->session->setFlash('success', 'Your comment added.');
            return $this->redirect(['/post/default/view', 'id' => $id]);
        }


    }

    public function actionUpdatecomment($id)
    {
        $currentUser = Yii::$app->user->identity;

        $comment = $this->findComment($id);

        if ($currentUser->getId() !== $comment->user_id){
            throw new NotFoundHttpException();
        }

        $model = new CommentForm();

        if ($model->load(Yii::$app->request->post()) && $model->updateComment($comment)){
            Yii::$app->session->setFlash('success', 'Your comment updated.');
            return $this->redirect(['/post/default/view', 'id' => $comment->post_id]);
        }


    }
    
    
    public function actionDeleteComment($id)
    {
        $comment = $this->findComment($id);
        $postAuthorId = $comment->getPostAuthor();
        $currentUser = Yii::$app->user->identity;
        if (!$currentUser || $postAuthorId !== $currentUser->getId()){
            throw new NotFoundHttpException();
        }
        if ($comment->delete()) {
            return $this->redirect(['/post/default/view', 'id' => $comment->post_id]);
        }

    }


    public function findPost($id)
    {
        if ($post = Post::findOne($id)) {
            return $post;
        }
        throw new NotFoundHttpException();
    }

    public function findComment($id)
    {
        if ($comment = Comment::findOne($id)) {
            return $comment;
        }
        throw new NotFoundHttpException();
    }


    public function actionLike()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $post = $this->findPost($id);
        $currentUser = Yii::$app->user->identity;

        $post->like($currentUser);

        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }


    public function actionUnlike()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $post = $this->findPost($id);
        $currentUser = Yii::$app->user->identity;

        $post->unlike($currentUser);

        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }
}
