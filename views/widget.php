<?php
/**
 * @var \yii\db\ActiveRecord $model
 * @var \alexander777hub\crop\Widget $widget
 *
 */


use yii\helpers\Html;

/* <?= Html::activeHiddenInput($model, $widget->attribute, ['class' => 'photo-field']); ?>
    <?= Html::hiddenInput('width', $widget->width, ['class' => 'width-input']); ?>
    <?= Html::hiddenInput('height', $widget->height, ['class' => 'height-input']); ?>
    <?= Html::img(
        $model->{$widget->attribute} != ''
            ? $model->{$widget->attribute}
            : $widget->noPhotoImage,
        [
            'style' => 'max-height: ' . $widget->thumbnailHeight . 'px; max-width: ' . $widget->thumbnailWidth . 'px',
            'class' => 'thumbnail',
            'data-no-photo' => $widget->noPhotoImage
        ]
    ); ?>*/

//$this->registerJsFile(  'yii2-crop/web/js/upload_photo.js?t=' . time(), ['depends' => [\alexander777hub\crop\assets\CropperAsset::className()]]);

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="bg-light js-upload-item" id="new_public_photo">
            <img style="margin-bottom: 10px;" id="icon" src=<?= $model->{$widget->attribute} ? $model->{$widget->attribute} : $widget->noPhotoImage ?> >

            <!-- Button trigger modal -->
        </div>
        <button  type="button" data-id=<?= 0 ?> data-type=<?= \app\models\File::TYPE_ICON  ?> class="btn ml-3 mb-3 btn-primary js-show-upload-icon" data-toggle="modal" data-target="#exampleModal">
            Добавить фото
        </button>
        <button type="button" data-id=<?= 0 ?> data-type=<?= \app\models\File::TYPE_ICON  ?> class="btn ml-3 mb-3 btn-danger delete" data-target="#exampleModal">
            Удалить
        </button>
    </div>
    <div class="col-12 col-sm-6">
        <div data-interval="false" id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <?php foreach($widget->items->all() as $key=>$photo){ ?>
                    <div class="carousel-item">
                        <div class="card-body">
                            <button class="btn btn-danger redo">Редактировать</button>
                        </div>
                        <div class="card">
                            <img class="img-fluid" id=<?= $photo->id  ?> class="d-block w-100" src=<?=  $photo->url   ?> alt="First slide">
                        </div>
                    </div>
                <?php } ?>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

</div>








<!-- Modal -->
<div data-keyboard="false" data-backdrop="static" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="crop_icon" src= ''>
            </div>
            <div class="modal-footer">
                <div>
                    <input type="hidden" name="<?= $widget->parent_table . '[' . $widget->photo_field . ']'  ?>" value="<?= $model->{$widget->attribute} ? $model->{$widget->attribute} : $widget->noPhotoImage   ?>" id="link">
                    <input type="hidden" name="<?= $widget->parent_table . '[' . $widget->obj_id_field . ']'  ?>" value="<?= 0   ?>" id="photo_id">
                    <input type="hidden" id="controller_id" value="<?= $widget->controller_id ?>">
                    <input type="hidden" id="upload_user_id" value="<?= Yii::$app->user->id ?>">
                    <input type="hidden" id="obj_id" value=<?=  0 ?>>
                    <input type="hidden" id="type">
                    <input type="file" data-type=<?= \app\models\File::TYPE_ICON  ?>  id="btn_upload" accept="image/*" />
                </div>
                <div>
                    <button type="button" id="btn-close" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" id="btn_crop">Обрезать</button>
                </div>
            </div>
        </div>
    </div>
</div>
