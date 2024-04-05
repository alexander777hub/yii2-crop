<?php

namespace alexander777hub\crop\assets;

use yii\web\AssetBundle;

/**
 * Widget asset bundle
 */
class CropperAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@alexander777hub/crop/web/';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/cropper.css',
        'css/photo_upload.css',
        'gallery/src/css/swipebox.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/cropper.js',
        'js/upload_photo.js',
        'gallery/src/js/jquery.swipebox.js'

    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
