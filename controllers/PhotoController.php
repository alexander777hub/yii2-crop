<?php
namespace alexander777hub\crop\controllers;

use alexander777hub\crop\models\File;
use app\models\Profile;
use Yii;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;
use alexander777hub\crop\models\PhotoEntity;


 class PhotoController extends Controller
{
     public function beforeAction($action)
     {
         $this->enableCsrfValidation = false;
         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
         return parent::beforeAction($action);
     }

     public function actionDestroy(){
         $p = Json::decode(Yii::$app->request->getRawBody());
         $filepath = $p['file'];
         $file = new File();
         if($filepath && !$file->wasSaved($filepath)){
             unlink(Yii::getAlias('@webroot') . $filepath);
         }

         exit;

     }
     public function actionUpload()
     {
         if (\Yii::$app->request->isAjax || \Yii::$app->request->isPost) {
             $file = new File();
             if (isset($_FILES['file'])) {
                 $result       = $file->uploadPhotoAdv($_FILES, $_POST);
                 $path_to_save = $result['path_to_save'];
                 $filename_ext = $result['filename_ext'];
                 $type         = $result['type'];
                 $is_new_photo = $result['is_new_photo'];
                 
                 \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                 $link_for_cropper            = explode('web',$path_to_save . $filename_ext)[1];
                    
                 return [
                     'result'       => 'success',
                     'id'           => 0,
                     'file_full'    =>  $link_for_cropper,
                     'file_prev'    => $link_for_cropper,
                     'type'         => $type,
                     'is_new_photo' => $is_new_photo,
                 ];
             }
         }
     }
    
     public function actionCrop()
     {
         if (\Yii::$app->request->isAjax || \Yii::$app->request->isPost) {
             if (isset($_FILES['file'])){
                 $photo_id = $_POST['obj_id'];
                 $result = File::cropPhotoAdv($_FILES, $_POST);
                 $path_to_save = $result['path_to_save'];
                  $filename = $result['filename'];

                 $filepath = '/' . explode('/web/',$result['path_to_save'])[1] . $filename;
                 $type = $result['type'];
                 $is_new_photo = $result['is_new_photo'];
                // $tmp_file = $result['files']['file']['tmp_name'] . '.'. explode('/', $result['files']['file']['type'])[1];
                 //$file = '/' . explode('/web/',$result['path_to_delete'])[1];
                 /*if ($result['ppc_file']['bind_obj_id']){
                     $query      = (new Query())->select(['id'])
                         ->from([$table])
                         ->where(['IN', $entity_name, $ids]);
                 } else {
                     $photo       = new PhotoEntity();
                     $photo->type = $result["type"];
                     $photo->link = $file;
                 } */

                /* $profile = Profile::find()->where(['user_id'=>$result['ppc_file']['bind_obj_id']])->one();
                 $profile->photo = $file;
                 $profile->save(false); */
                 \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

                 return [
                     'result' => 'success',
                   //  'file' => $file,
                     'filename_real' => $filename,
                     'is_new_photo' => $is_new_photo,
                     'type'    => $type,
                     'ppc_file' => $result['ppc_file'],
                     'filepath' => $filepath,
                     'photo_id' => $photo_id
                     //'tmp_file' => $tmp_file,
                    
                 ];
             }
         }
     }
     public function actionDelete()
     {
         if (\Yii::$app->request->isAjax || \Yii::$app->request->isPost) {
             $post = $_POST;
             if($post['obj_id']){
                 $filepath = File::getDirectory($post['type']);
                 $f = Yii::getAlias('@webroot') . $post['file'];
                 if(File::MODEL_ENTITY){
                     $namespace = File::MODEL_ENTITY;
                     $photo_class = new $namespace();
                     $photo = $photo_class::findOne(1);
                     if($photo){
                         $photo->delete();
                     }
                 }
             }
             if (file_exists(Yii::getAlias('@webroot') . $post['file'])){
                 unlink(Yii::getAlias('@webroot') . $post['file']);
             }

             \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
             return [
                 'status' => 'success',
                 'code' => 301,
             ];
         }


     }
}