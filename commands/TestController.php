<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use Yii;
use app\models\Auth;


class TestController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($key)
    {
//        echo $key . "\n";
//       Здесь пароль в поле key_hash
//      echo Yii::$app->getSecurity()->generatePasswordHash('1234');
//      die;
//      $auth = new Auth();
      $auth =  Auth::findOne(['id' => 1]);
      var_dump($auth->hash);
      die;
      var_dump(Yii::$app->security->validatePassword($key, '$2y$13$ASSMu5Zhmj8Jio18sInqUO6BPskZ9PawZQTaat7IJYIxFMBjTkPdG'));
      return ExitCode::OK;
    }
}
