<?php


namespace alexander777hub\crop\models;

use app\models\Profile;


use Yii;

/**
 * Class File
 * @package app\models
 */
class File
{

    public $path_to_save;
    public const TYPE_ICON = 7;
    public const TYPE_IMAGE = 8;
    //public const DIR_SAVE = '/uploads/profile/saved/';
    public const DIR_SAVE = '/uploads/profile/saved/';
    public const DIR_PUBLIC = '/uploads/profile/public/';
    public const BINDED_MODEL = 'app\models\Profile';
    public const MODEL_ENTITY = 'alexander777hub\crop\models\PhotoEntity';
    public static $public_types = [
        7 => 'icon',
        8 => 'image',
    ];

    public static function typeIsPublic($type)
    {
        if(isset(self::$public_types[intval($type)])){
            return true;
        }
        return false;
    }

    public function wasSaved($filepath)
    {
        $profile = Profile::find()->where(['photo' => $filepath])->asArray()->one();
        if($profile){
            return true;
        }
        return false;
    }

    public  function uploadPhotoAdv(array $files, array $post)
    {
        //$alias = Yii::getAlias('@webroot') . '/uploads/profile/public/';
        $photo_id = (int)$post['upload_photo_id'] ?? 0;
        $type     = (int)$post['type'] ?? 0;
        $obj_id   = (int)$post['obj_id'] ?? 0;
        $size     = (int)$files['file']['size'];
        if ($photo_id === 0) {
            $is_new_photo = 1;
        } else {
            $is_new_photo = 0;

        }
        $filename = $files['file']['name'];
        if (!isset($files['file']['type'])) {
            \Yii::error('fatal error: no support type', 'common');
            exit;
        }
        $mime_type          = $files['file']['type'];
        $allowed_file_types = ['image/png', 'image/jpeg'];
        if (!in_array($mime_type, $allowed_file_types)) {
            \Yii::error('Not allowed type ' . $mime_type, 'common');
            exit;
        }
        $e            = explode('.', $filename);
        $ext          = end($e);
        $hashname     = md5(microtime() . $filename);
        $filename_ext = $hashname . '.' . $ext;
        $path_to_save = Yii::getAlias('@webroot') . '/uploads/profile/saved/';
        /*if (self::typeIsPublic($type)) {
            $path_to_save = Yii::getAlias('@webroot') . '/uploads/profile/public/';
        } else {
            $path_to_save = Yii::getAlias('@webroot') . '/uploads/profile/private/';
        } */
        if (file_exists($path_to_save . $filename_ext)) {
            unlink($path_to_save . $filename_ext);
        }
        try {
            if (is_uploaded_file($files ['file'] ['tmp_name'])) {
                move_uploaded_file($files ['file'] ['tmp_name'],
                    $path_to_save . $filename_ext);
            };
        } catch (Exception $error) {
            \Yii::error( 'Error:', $error->getMessage() . PHP_EOL, 'common');
        }
        if  ($type === self::TYPE_ICON) {
            if ($ext == 'png') {
                if (file_exists($path_to_save. $filename_ext)) {
                    $im  = imagecreatefrompng($path_to_save . $filename_ext);
                    $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => 400, 'height' => 400]);
                    $im2 = imagescale($im, 400, 400);
                    if ($im2 !== FALSE) {
                        imagepng($im2, $path_to_save . $filename_ext);
                        imagedestroy($im2);
                    }
                    imagedestroy($im);

                } else {
                    \Yii::error( 'File did not save', 'common');
                    exit;
                }

            } else {
                if (file_exists($path_to_save . $filename_ext)) {

                    $im  = imagecreatefromjpeg($path_to_save . $filename_ext);
                    $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => 400, 'height' => 400]);
                    $im2 = imagescale($im, 400, 400);
                    if ($im2 !== FALSE) {
                        imagejpeg($im2, $path_to_save . $filename_ext);
                        imagedestroy($im2);
                    }
                    imagedestroy($im);
                } else {
                    \Yii::error( 'File did not save', 'common');
                    exit;
                }
            }
        }
        if  ($type === self::TYPE_IMAGE) {
            if ($ext == 'png') {
                if (file_exists($path_to_save . $filename_ext)) {
                    $im  = imagecreatefrompng($path_to_save . $filename_ext);
                    $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => 500, 'height' => 400]);
                    $im2 = imagescale($im, 500, 400);
                    if ($im2 !== FALSE) {
                        imagepng($im2, $path_to_save . $filename_ext);
                        imagedestroy($im2);
                    }
                    imagedestroy($im);

                } else {
                    \Yii::error( 'File did not save', 'common');
                    exit;
                }

            } else {
                if (file_exists($path_to_save. $filename_ext)) {

                    $im  = imagecreatefromjpeg($path_to_save . $filename_ext);
                    $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => 500, 'height' => 400]);
                    $im2 = imagescale($im, 500, 400);
                    if ($im2 !== FALSE) {
                        imagejpeg($im2, $path_to_save . $filename_ext);
                        imagedestroy($im2);
                    }
                    imagedestroy($im);
                } else {
                    \Yii::error( 'File did not save', 'common');
                    exit;
                }
            }
        }
        $result                 = [];
        $result['path_to_save'] = $path_to_save;
        $result['filename_ext'] = $filename_ext;
        $result['type']         = $type;
        $result['is_new_photo'] = $is_new_photo;
        return $result;
    }
    public static function cropPhotoAdv(array $files, array $post)
    {
        $is_new_photo = false;
        $photo_id = (int)$post['upload_photo_id'] ?? 0;
        $type     = (int)$post['type'] ?? 0;
        $obj_id   = (int)$post['obj_id'] ?? 0;
        $size     = (int)$files['file']['size'];
        /*$profile     = Profile::find()->where(['user_id' => $obj_id])->one();
        if($profile){
            $is_new_photo = $profile->photo ? 0 : 1;
        }
        if(!$is_new_photo){
            $old_file = explode('/', $profile->photo)[5];
            if ($old_file) {
                $directory_to_delete = self::getDirectory($type, $obj_id);
                $path_to_file_delete = $directory_to_delete . $old_file;
                if ($path_to_file_delete && file_exists($path_to_file_delete)) {
                    unlink($path_to_file_delete);
                }
            }
        }*/

        $filename = $files['file']['name'];
        if (!isset($files['file']['type'])) {
            \Yii::error('fatal error: no support type', 'common');
            exit;
        }
        $mime_type          = $files['file']['type'];
        $allowed_file_types = ['image/png', 'image/jpeg'];
        if (!in_array($mime_type, $allowed_file_types)) {
            \Yii::error('Not allowed type ' . $mime_type, 'common');
            exit;
        }
        $e   = explode('.', $filename);
        $ext = end($e);
        $path_to_save = self::getDirectory($type, $obj_id); // куда сохранить, было getDirectory но теперь надо чтоб не знал про папки
        $path_to_save = self::getPathToSaved();
        if (!file_exists($path_to_save . $filename)) {
            \Yii::error('Not allowed type ' . $mime_type, 'common');
            exit;
            // unlink($path_to_save . $filename);раньше удалял теперь делаем из одного и того же файла
        }
        try {
            $fact_type = explode('/', $mime_type)[1];
            if ($ext != $fact_type) {
                $filename = explode($ext, $filename)[0];
                $filename = $filename . $fact_type;
            }
            move_uploaded_file($files ['file'] ['tmp_name'],
                $path_to_save . $filename);
            $f = Yii::getAlias('@webroot') .'/uploads/profile/saved/'. $files ['file'] ['name'];
            if (file_exists(Yii::getAlias('@webroot') .'/uploads/profile/saved/'. $files ['file'] ['name'])) {
                unlink(Yii::getAlias('@webroot') .'/uploads/profile/saved/'. $files ['file'] ['name']);
            }

        } catch (Exception $error) {
            \Yii::error('Error:', $error->getMessage() . PHP_EOL, 'common');
        }
        if ($type === self::TYPE_IMAGE) {
            $file = $path_to_save . $filename;
            if ($fact_type == 'png') {
                if (file_exists($path_to_save . $files['file']['name'])) {

                    $image = imagecreatefrompng($file);

                    $image2 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => 492, 'height' => 328]);

                    $image2 = imagescale($image, 492, 328);
                    if ($image2 !== FALSE) {
                        imagepng($image2, $file);
                        imagedestroy($image2);
                    }
                    imagedestroy($image);
                } else {
                    \Yii::error('File did not save', 'common');
                    exit;
                }
            } else {
                if (file_exists($path_to_save . $filename)) {
                    $image  = imagecreatefrompng($file);
                    $image2 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => 492, 'height' => 328]);
                    $image2 = imagescale($image, 492, 328);
                    if ($image2 !== FALSE) {
                        imagepng($image2, $file);
                        imagedestroy($image2);
                    }
                    imagedestroy($image);
                } else {
                    \Yii::error('File did not save', 'common');
                    exit;
                }
            }
            $hash = explode('.', $filename)[0];
            $name = $files['file']['name'];
        }
        if ($type === self::TYPE_ICON) {
            $file = $path_to_save . $filename;
            if (file_exists($file)) {
                $image  = imagecreatefrompng($file);
                $image2 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => 192, 'height' => 192]);
                $image2 = imagescale($image, 192, 192);
                if ($image2 !== FALSE) {
                    imagepng($image2, $file);
                    imagedestroy($image2);
                }
                imagedestroy($image);
            } else {
                \Yii::error('File did not save', 'common');
                exit;
            }
            //$file = $_FILES['file']['tmp_name'];
            /* if ($fact_type == 'png') {
                 if (file_exists($file)) {
                     $image  = imagecreatefrompng($file);
                     $image2 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => 192, 'height' => 192]);
                     $image2 = imagescale($image, 192, 192);
                     if ($image2 !== FALSE) {
                         imagepng($image2, $file);
                         imagedestroy($image2);
                     }
                     imagedestroy($image);
                 } else {
                     \Yii::error('File did not save', 'common');
                     exit;
                 }
             } else {
                 if (file_exists($path_to_save . $filename)) {
                     $image  = imagecreatefrompng($file);
                     $image2 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => 192, 'height' => 192]);
                     $image2 = imagescale($image, 192, 192);
                     if ($image2 !== FALSE) {
                         imagepng($image2, $file);
                         imagedestroy($image2);
                     }
                     imagedestroy($image);
                 } else {
                     \Yii::error('File did not save', 'common');
                     exit;
                 }
             } */
            $hash = explode('.', $filename)[0];
            $name = $files['file']['name'];
        }

        $result                 = [];
        $result['path_to_save'] = $path_to_save;
        $result['path_to_delete'] = Yii::getAlias('@webroot') .'/uploads/profile/saved/'. $files ['file'] ['name'];
        $result['filename']     = $filename;
        $result['files']         = $files;
        $result['type']         = $type;
        $result['is_new_photo'] = $is_new_photo;
        $result['ppc_file']     = [
            'hash_name'   => $hash,
            'extension'   => $fact_type,
            'origin_name' => $name,
            'size'        => $size,
            'type'        => $type,
            'bind_obj_id' => $obj_id,
        ];
        return $result;
    }

    public static function getDirectory()
    {

        $path = Yii::getAlias('@webroot') . self::DIR_PUBLIC;

        if (!file_exists($path)) {
            mkdir($path, 0755);
        }
        return $path;

    }
    public static function getPathToSaved()
    {

        $path = Yii::getAlias('@webroot') . self::DIR_SAVE;

        if (!file_exists($path)) {
            mkdir($path, 0755);
        }
        return $path;

    }
}