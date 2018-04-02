<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;
?>


<h3><?= Html::encode($user->username) ?></h3>
<p><?= HtmlPurifier::process($user->about) ?></p>
<hr>

<img id="profile-picture" width="200" height="200" class="img-circle" src="<?= $user->getPicture() ?>" alt="">

<?php if ($currentUser && $user->equals($currentUser)): ?>
    <div class="alert alert-success display-none" id="profile-image-success">Profile image updated</div>
    <div class="alert alert-danger display-none" id="profile-image-fail"></div>
    
    <?= FileUpload::widget([
        'model' => $modelPicture,
        'attribute' => 'picture',
        'url' => ['/user/profile/upload-picture'], // your url, this is just for demo purposes,
        'options' => ['accept' => 'image/*'],
        'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
                    if (data.result.success) {
                        $("#profile-image-success").show();
                        $("#profile-image-fail").hide();
                        $("#profile-picture").attr("src", data.result.pictureUri);
                    } else {
                        $("#profile-image-fail").html(data.result.errors.picture).show();
                        $("#profile-image-success").hide();
                    }
            }',
    
        ],
    ]); ?>

    <a href="<?php echo Url::to(['/user/profile/delete-picture']); ?>" class="btn btn-danger">Delete picture</a>
<?php endif; ?>

<?php if ($currentUser && !$user->equals($currentUser)): ?>

    <?php if (!$currentUser->isFollowing($user)): ?>
        <a href="<?= Url::to(['/user/profile/subscribe', 'id' => $user->getId()]) ?>"
           class="btn btn-info">
            Subscribe
        </a>
    <?php else: ?>
        <a href="<?= Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]) ?>"
           class="btn btn-info">
            Unsubscribe
        </a>
    <?php endif; ?>

    <hr>
    <?php if ($mutualSubscriptions = $currentUser->getMutualSubscriptionsTo($user)): ?>
        

        <h5>Friends, who are also following <?= Html::encode($user->username) ?>:</h5>
    
        <div class="row">
            <?php foreach ($mutualSubscriptions as $item): ?>
                <div class="col-md-12">
                    <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($item['nickname']) ? $item['nickname'] : $item['id']]); ?>">
                        <?php echo Html::encode($item['username']); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        
    <?php endif; ?>
<?php endif; ?>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal1">
        Subscriptions (<?= $user->countSubscriptions() ?>)
    </button>
    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal2">
        Followers (<?= $user->countFollowers() ?>)
    </button>


<h2>My posts:</h2>
<?php foreach ($currentUserPosts as $post): ?>
    <div class="col-md-12">
        <h4>
            <a href="<?= Url::to(['/post/default/view', 'id' => $post->id]) ?>">ID: <?= $post->id ?></a>
        </h4>
        <img width="600" src="<?php echo Yii::$app->storage->getFile($post->filename); ?>" />
        <div class="col-md-12">
            <?php echo HtmlPurifier::process($post->description); ?>
        </div>

        <div class="col-md-12">
            <?php echo Yii::$app->formatter->asDatetime($post->created_at); ?>
        </div>

        <div class="col-md-12">
            <h4>
                Likes: <span class="likes-count"><?php echo $post->countLikes(); ?></span>
            </h4>
        </div>
    </div>
    <div class="col-md-12"><br><hr/><br></div>
<?php endforeach; ?>


    <!-- Modal -->
    <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Subscriptions</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <?php foreach ($user->getSubscriptions() as $subscription): ?>
                            <div class="col-md-12">
                                <a href="<?php echo Url::to(['/user/profile/view',
                                    'nickname' => ($subscription['nickname']) ? $subscription['nickname'] : $subscription['id']]); ?>">
                                    <?php echo Html::encode($subscription['username']); ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Subscriptions</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <?php foreach ($user->getFollowers() as $follower): ?>
                            <div class="col-md-12">
                                <a href="<?php echo Url::to(['/user/profile/view',
                                    'nickname' => ($follower['nickname']) ? $follower['nickname'] : $follower['id']]); ?>">
                                    <?php echo Html::encode($follower['username']); ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

