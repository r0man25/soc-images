<?php

namespace frontend\modules\user\models\forms;

use function MongoDB\BSON\toJSON;
use yii\base\Model;
use Yii;
use Intervention\Image\ImageManager;
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 22.03.2018
 * Time: 22:14
 */
class PictureForm extends Model
{
    public $picture;

    public function __construct()
    {
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizePicture']);
    }

    /**
    +     * Resize image if needed
    +     */
    public function resizePicture()
    {
        if ($this->picture->tempName) {

            $width = Yii::$app->params['profilePicture']['maxWidth'];
            $height = Yii::$app->params['profilePicture']['maxHeight'];

            $manager = new ImageManager(array('driver' => 'imagick'));

            $image = $manager->make($this->picture->tempName);

            // 3-й аргумент - органичения - специальные настройки при изменении размера
            $image->resize($width, $height, function ($constraint) {

                // Пропорции изображений оставлять такими же (например, для избежания широких или вытянутых лиц)
                $constraint->aspectRatio();

                // Изображения, размером меньше заданных $width, $height не будут изменены:
                $constraint->upsize();

            })->save();
        }
        return ['success' => false, 'errors' => $this->getErrors()];
               
    }

    public function rules()
    {
        return [
            [['picture'], 'file',
                'extensions' => ['jpg'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize(),
            ],
        ];
    }


    public function getMaxFileSize()
    {
        return Yii::$app->params['maxFileSize'];
    }
}