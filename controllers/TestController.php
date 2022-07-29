<?php

namespace app\controllers;

use app\models\Auth;
use yii\web\Controller;
use Yii;
use app\models\DataForm;
use app\models\Products;

class TestController extends Controller
{

  public function actionIndex()
  {
    set_time_limit(0);
    $this->enableCsrfValidation = false;
    $model = new DataForm();
//    var_dump($model);
    if (Yii::$app->request->post()) {
//      var_dump($_POST['data']);
//      die;
      $key = $_POST['key'];
      $data = $_POST['data'];

      $response = Yii::$app->response;
      $response->format = \yii\web\Response::FORMAT_JSON;

      $auth = Auth::findOne(['id' => 1]); // там в таблице одна запись где храниться хеш ключа (валидный ключ 1234)
      // сравним введенный ключ с хэшем в таблице auth
      if (!Yii::$app->security->validatePassword($key, $auth->hash)) {
        $response->data = ['isOk' => false, 'err' => 'Неверный ключ!'];
        return json_encode($response->data);
      }
      if ($data) {
        try {
          $data = json_decode($data); // здесь может быть null или false при невалидном json
          if(!$data){
            $response->data = ['isOk' => false, 'err' => 'Что-то не так с JSON данными!'];
            return json_encode($response->data);
          }
//          var_dump($data);
//          die;
        } catch (Exception $e) {
          $response->data = ['isOk' => false, 'err' => 'Ошибка при обработке JSON данных!'];
          return json_encode($response->data);
        }

        $all = count($data); // всего товаров обнаружено в JSON
        $upd = 0; // кол-во обновленных полей
        $ins = 0; // кол-во добавленных полей

        // обернем в транзакцию
        $transaction = Products::getDb()->beginTransaction();
        try {
          foreach ($data as $product) {
            $is_product = Products::findOne(['product_id' => $product->product_id]);

            if ($is_product) { // товар с таким product_id уже есть - меняем ценник
              $is_product->prices = json_encode($product->prices);
              $res = $is_product->save();
              if ($res) {
                $upd++;
              }
            } else { // новый товар - добавляем
              $products = new Products();
              $products->product_id = $product->product_id;
              $products->prices = json_encode($product->prices);
              $res = $products->save();
              if ($res) {
                $ins++;
              }
            }
          }
          //
          $transaction->commit();
          $response->data = ['isOk' => true, 'update' => $upd, 'insert' => $ins, 'all' => $all];
          return json_encode($response->data);
        } catch (\Throwable $e) {
          $transaction->rollBack();
          $response->data = ['isOk' => false, 'err' => $e];
          return json_encode($response->data);
        }
      }
    }

    return $this->render('index', compact('model'));
  }
}
