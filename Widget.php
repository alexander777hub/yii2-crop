<?php


namespace alexander777hub\crop;

use alexander777hub\crop\assets\CropperAsset;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use Yii;

/**
 * Class Widget
 * @package alexander777hub\crop
 */
class Widget extends InputWidget
{
    public $uploadParameter = 'file';
    public $width = 200;
    public $height = 200;
    public $label = '';
    public $uploadUrl;
    public $noPhotoImage = '';
    public $maxSize = 2097152;
    public $thumbnailWidth = 300;
    public $thumbnailHeight = 300;
    public $cropAreaWidth = 300;
    public $cropAreaHeight = 300;
    public $extensions = 'jpeg, jpg, png, gif';
    public $onCompleteJcrop;
    public $pluginOptions = [];
    public $aspectRatio = null;
    public $default_controller_id = "/crop";
    public $controller_id;
    public $parent_table;
    public $photo_field;
    public $items;
    public $obj_id_field;
    

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        //self::registerTranslations();
        if($this->parent_table === null) {
            throw new InvalidConfigException("missing" ,['attribute' => 'parent_table']);
        } else {
            $this->parent_table = rtrim(Yii::getAlias($this->parent_table), '{{%}}');
        }
        if($this->photo_field === null) {
            throw new InvalidConfigException("missing" ,['attribute' => 'photo_field']);
        }
        if ($this->uploadUrl === null) {
            throw new InvalidConfigException("missing" ,['attribute' => 'uploadUrl']);
        } else {
            $this->uploadUrl = rtrim(Yii::getAlias($this->uploadUrl), '/') . '/';
        }
        if($this->controller_id === null){
            $this->controller_id = $this->default_controller_id;
        }
       // return true;

       /* if ($this->label == '') {
            $this->label = Yii::t('cropper', 'DEFAULT_LABEL');
        } */
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientAssets();
       //var_dump(Yii::$app->getModule("crop"));
      // exit;
        return $this->render('widget', [
            'model' => $this->model,
            'widget' => $this
        ]);
    }

    /**
     * Register widget asset.
     */
    public function registerClientAssets()
    {
        $view = $this->getView();
        $assets = CropperAsset::register($view);
        //var_dump(Yii::getAlias("@alexander777hub/crop") . '/assets/img/default.png');
        //exit;
        if ($this->noPhotoImage == '') {
            $this->noPhotoImage = $assets->baseUrl . '/img/default.png';
        }
        $settings = array_merge([
            'url' => $this->uploadUrl,
            'name' => $this->uploadParameter,
            'maxSize' => $this->maxSize / 1024,
            'allowedExtensions' => explode(', ', $this->extensions),
            'size_error_text' =>  ['size' => $this->maxSize / (1024 * 1024)],
            'ext_error_text' =>  ['formats' => $this->extensions],
            'accept' => 'image/*',
        ], $this->pluginOptions);

        if(is_numeric($this->aspectRatio)) {
            $settings['aspectRatio'] = $this->aspectRatio;
        }

        if ($this->onCompleteJcrop)
            $settings['onCompleteJcrop'] = $this->onCompleteJcrop;

        /*$view->registerJs(
            'jQuery("#' . $this->options['id'] . '").parent().find(".new-photo-area").cropper(' . Json::encode($settings) . ', ' . $this->width . ', ' . $this->height . ');',
            $view::POS_READY
        ); */
    }
    /**
     * Register widget translations.
     */
    public static function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['cropper']) && !isset(Yii::$app->i18n->translations['cropper/*'])) {
            Yii::$app->i18n->translations['cropper'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@alexander777hub/crop/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'cropper' => 'cropper.php'
                ]
            ];
        }
    }

}