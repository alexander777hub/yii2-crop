<?php
/**
 * @var \yii\db\ActiveRecord $model
 * @var \alexander777hub\crop\Widget $widget
 *
 */


use yii\helpers\Html;


?>
<script

        src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>




<style>
    .modal-body {

        padding: 0rem !important;
    }
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $(".clear").on("click", function(e){
            e.preventDefault();
            var def = "<?= $widget->noPhotoImage   ?>";
            console.log(def, "D");
            var div =  $(this).parent();
            $("#icon").attr("src", def);
            $("#link").val(def);
            $("#obj_id").val(' ');
            $("#photo_id").val(' ');
        });
        $('.swipebox').swipebox();

    });


</script>

<div data-lightgallery="group" class="row row-12 row-x-12 d-md-flex flex-md-equal w-100">
    <div class="col-xs-12 col-md-4 bg-light text-center overflow-hidden js-upload-item">
        <img  width="<?=  $widget->width ?>" style="margin-bottom: 10px;" id="icon" src=<?= $model->{$widget->attribute} ? $model->{$widget->attribute} : $widget->noPhotoImage ?> >

        <button  type="button" data-id=<?= 0 ?> data-type=<?= \app\models\File::TYPE_ICON  ?> class="btn ml-3 mb-3 btn-primary js-show-upload-icon" data-toggle="modal" data-target="#exampleModal">
            Добавить фото
        </button>
        <button type="button" data-id=<?= 0 ?> data-type=<?= \app\models\File::TYPE_ICON  ?> class="btn ml-3 mb-3 btn-danger clear" data-target="#exampleModal">
            Очистить
        </button>
    </div>
    <?php foreach($widget->items->all() as $key=>$photo){ ?>
        <div class="col-xs-12 col-md-4 bg-light text-center overflow-hidden js-upload-item">
            <a rel=<?=  'gallery-'. $model->id   ?> class="swipebox bg-dark box-shadow mx-auto" href="<?= $photo->url ?>" data-lightgallery="item" style="width: 100%; height: 400px; border-radius: 21px;">
            <img style="margin-bottom: 10px;" id="<?= $photo->id  ?>" width="<?=  $widget->width ?>" class="thumbnail-light-image" src="<?= $photo->url  ?>" alt="">
            </a>
            <button  type="button" data-id=<?= $photo->id ?> data-type=<?= \app\models\File::TYPE_ICON  ?> class="btn ml-3 mb-3 btn-primary btn btn-danger redo" data-toggle="modal" data-target="#exampleModal">
                Добавить фото
            </button>
            <button type="button" data-id=<?= $photo->id ?> data-type=<?= \app\models\File::TYPE_ICON  ?> class="btn ml-3 mb-3 btn-danger rem" data-target="#exampleModal">
                Удалить
            </button>
        </div>

    <?php } ?>

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
                    <input type="hidden" id="obj_id" value=<?=  $model->id ?>>
                    <input type="hidden" id="options" data-height="<?= $widget->height?>" data-width="<?= $widget->width?>">
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
