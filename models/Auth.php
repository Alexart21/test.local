<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "auth".
 *
 * @property int $id
 * @property string $key_hash
 * @property string $auth_key
 */
class Auth extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key_hash'], 'required'],
            [['key_hash'], 'string', 'max' => 100],
            [['auth_key'], 'string', 'max' => 100],
        ];
    }

  /**
   * @inheritdoc
   */
  public function getHash()
  {
    return $this->key_hash;
  }

}
