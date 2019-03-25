<?php

namespace common\modules\blog;

/**
 * blog module definition class
 */
class Blog extends \yii\base\Module
{

    public $controllerNamespace = 'common\modules\blog\controllers';
    public $defaultRoute = 'blog';  // Задаем дефаулт контроллер http://yii2.blondy/yii2/backend/web/blog

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
