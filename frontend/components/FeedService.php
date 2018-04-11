<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 30.03.2018
 * Time: 0:01
 */

namespace frontend\components;

use frontend\models\Post;
use yii\base\Component;
use yii\base\Event;
use frontend\models\Feed;

class FeedService extends Component
{

    public function addToFeeds(Event $event)
    {
        $user = $event->getUser();
        $post = $event->getPost();

        $followers = $user->getFollowers();

        foreach ($followers as $follower) {
            $feedItem = new Feed();
            $feedItem->user_id = $follower['id'];
            $feedItem->author_id = $user->id;
            $feedItem->author_name = $user->username;
            $feedItem->author_nickname = $user->getNickname();
            $feedItem->author_picture = $user->getPicture();
            $feedItem->post_id = $post->id;
            $feedItem->post_filename = $post->filename;
            $feedItem->post_description = $post->description;
            $feedItem->post_created_at = $post->created_at;
            $feedItem->save();
        }
    }



    public function addPostsByUserToFeed(Event $event)
    {
        $user = $event->getUser();

        $currentUser = $event->getCurrentUser();

        $userPosts = Post::find()->where(['user_id' => $user->id])->all();


        foreach ($userPosts as $post) {
            $feedItem = new Feed();
            $feedItem->user_id = $currentUser->id;
            $feedItem->author_id = $user->id;
            $feedItem->author_name = $user->username;
            $feedItem->author_nickname = $user->getNickname();
            $feedItem->author_picture = $user->getPicture();
            $feedItem->post_id = $post->id;
            $feedItem->post_filename = $post->filename;
            $feedItem->post_description = $post->description;
            $feedItem->post_created_at = $post->created_at;
            $feedItem->save();
        }
    }
    
    
    public function deletePostsByUserFromFeed(Event $event)
    {
        $user = $event->getUser();
        
        $currentUser = $event->getCurrentUser();
        
        return Feed::deleteAll([
            'user_id' => $currentUser->id,
            'author_id' => $user->id,
        ]);
    }

}