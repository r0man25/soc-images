<?php
/* @var $this yii\web\View */
/* @var $post frontend\models\Post */

use yii\helpers\Html;
use yii\helpers\Url;
?>
    <div class="post-default-index">

        <div class="row">

            <div class="col-md-12">
                <?php if ($post->user): ?>
                    <?php echo $post->user->username; ?>
                <?php endif; ?>
            </div>

            <div class="col-md-12">
                <img width="300" src="<?php echo $post->getImage(); ?>" />
            </div>

            <div class="col-md-12">
                <?php echo Html::encode($post->description); ?>
            </div>

        </div>
    </div>

<div class="col-md-12">
    Likes: <span class="likes-count"><?php echo $post->countLikes(); ?></span>
    <a href="#" data-id="<?= $post->id ?>"
       class="btn btn-primary button-like
           <?= ($currentUser && $post->isLikedBy($currentUser)) ? "display-none" : "" ?>
       ">
        Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
    </a>
    <a href="#" data-id="<?= $post->id ?>"
       class="btn btn-primary button-unlike
            <?= ($currentUser && $post->isLikedBy($currentUser)) ? "" : "display-none" ?>
       ">
        Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
    </a>
</div><hr>

<h3>Comments:</h3>
<?php foreach ($comments as $comment): ?>

    <div class="bottom-comment"><!--bottom comment-->
        <div class="comment-img">
            <img width="50" height="50" class="img-circle" src="<?= $comment->getPhoto() ?>" alt=""> <!--TODO: Add user photo uploud-->
        </div>

        <div class="comment-text">
            <?php if ($currentUser && $currentUser->getId() === $comment->getPostAuthor()) : ?>
                <a href="<?php echo Url::to(['/post/default/delete-comment', 'id' => $comment->id]); ?>" class="pull-right btn btn-danger">Delete</a>
            <?php endif; ?>
<!--            <a href="#" class="replay btn pull-right"> Replay</a>-->
            <?php if ($currentUser && $currentUser->getId() === $comment->user_id) : ?>
                <button type="button" class="pull-right btn btn-primary" data-toggle="modal" data-target="#myReplay<?= $comment->id; ?>">
                    Change
                </button>
            <?php endif; ?>

            <h5><?= $comment->user->username; ?></h5>

            <p class="comment-date">
                <?= $comment->getDate() ?>
            </p>


            <p class="para"><?= $comment->text ?></p>
        </div>
    </div>
    <!-- end bottom comment-->


    <!-- Modal -->
    <div class="modal fade" id="myReplay<?= $comment->id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Change comment</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <?php $form = \yii\widgets\ActiveForm::begin([
                            'action'=>['/post/default/updatecomment', 'id'=>$comment->id],
//            'options'=>['class'=>'form-horizontal contact-form', 'role'=>'form']
                        ])?>
                        <div class="form-group">
                            <div class="col-md-12">
                                <?= $form->field($commentForm, 'text')->textarea(['class'=>'form-control','placeholder'=>'Write Message', 'value' => $comment->text])->label(false)?>
                            </div>
                        </div>
                        <button type="submit" class="btn send-btn">Update comment</button>
                        <?php \yii\widgets\ActiveForm::end();?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>





<?php if (!Yii::$app->user->isGuest): ?>

    <div><!--leave comment-->
        <h4>Leave a reply</h4>

        <?php $form = \yii\widgets\ActiveForm::begin([
            'action'=>['/post/default/comment', 'id'=>$post->id],
//            'options'=>['class'=>'form-horizontal contact-form', 'role'=>'form']
        ])?>
        <div class="form-group">
            <div class="col-md-12">
                <?= $form->field($commentForm, 'text')->textarea(['class'=>'form-control','placeholder'=>'Write Message'])->label(false)?>
            </div>
        </div>
        <button type="submit" class="btn send-btn">Post Comment</button>
        <?php \yii\widgets\ActiveForm::end();?>

    </div><!--end leave comment-->
    <br><br>
<?php endif; ?>


<?php
$this->registerJsFile('@web/js/likes.js', [
        'depends' => \yii\web\JqueryAsset::className(),
]);
?>