<?php
namespace app\models;

//use Yii;
use yii\base\Model;

class DataForm extends Model
{

  public $key;
  public $data;

  public function rules()
  {
    return [
      [['key', 'data'], 'required'],
      ['key', 'string'],
      ['data', 'file'],
      ['data', 'safe'],
//      [['data'], 'file', 'skipOnEmpty' => false, 'extensions' => 'json', 'maxSize' => 1024 * 1024 * 100],
    ];
  }

}