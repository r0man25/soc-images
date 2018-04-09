<?php

namespace common\components;

use yii\web\UploadedFile;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 22.03.2018
 * Time: 23:28
 */
interface StorageInterface
{
    public function saveUploadedFile(UploadedFile $file);

    public function getFile(string $filename);
}