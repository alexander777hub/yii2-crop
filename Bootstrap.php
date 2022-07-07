<?php
namespace alexander777hub\crop;
use Yii;
use yii\base\BootstrapInterface;


class Bootstrap implements BootstrapInterface{
    //Метод, который вызывается автоматически при каждом запросе
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            'crop/upload'  => 'crop/photo/upload',
            'crop/crop'    => 'crop/photo/crop',
            'crop/destroy' => 'crop/photo/destroy',
            'crop/delete' => 'crop/photo/delete',
        ], false);
        /*
         * Регистрация модуля в приложении
         * (вместо указания в файле frontend/config/main.php
         */
        
        $app->setModule('crop', 'alexander777hub\crop\Module');
       // Yii::setAlias('@webcrop', Yii::$app->getBasePath() . '/crop/');
        //var_dump(Yii::getAlias('@webcrop'));

    }
}