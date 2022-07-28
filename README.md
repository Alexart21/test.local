В БД создал 2 таблицы auth и products. Поскольку в задании не было про миграции то миграции не писал.
SQL дамп базы в корне сайта(test.).

В таблице auth храниться только хеш ключа от строки '1234'

В таблице products поле products_id - id товара
поле prices - JSON в виде текста.Цены для всех 20 регионов.(MYSQL использовал а не Postgress.При выводе придеться парсить).

Модели соответственно /models/Auth.php и /models/Products.php
Контроллер controllers/TestController.php
Обработка пришедших данных в экшене index
Вид views/test/index.php

Собственно вот основной PHP код:
public function actionIndex()
  {
    set_time_limit(0);
    $this->enableCsrfValidation = false;
    $model = new DataForm();
//    var_dump($model);
    if ($model->load(Yii::$app->request->post())) {
      $response = Yii::$app->response;
      $response->format = \yii\web\Response::FORMAT_JSON;

      $auth = Auth::findOne(['id' => 1]); // там в таблице одна запись где храниться хеш ключа (валидный ключ 1234)
      // сравним введенный ключ с хэшем в таблице auth
      if (!Yii::$app->security->validatePassword($model->key, $auth->hash)) {
        $response->data = ['isOk' => false, 'err' => 'Неверный ключ!'];
        return json_encode($response->data);
      }
      if ($_FILES) {
//          var_dump($_FILES['DataForm']["tmp_name"]['data']);
        $data = file_get_contents($_FILES['DataForm']["tmp_name"]['data']);
        try {
          $data = json_decode($data);
        } catch (Exception $e) {
          $response->data = ['isOk' => false, 'err' => 'Некорректный JSON файл'];
          return json_encode($response->data);
        }
        if ($data) {
          $all = count($data); // всего товаров обнаружено в JSON
          $upd = 0; // обновлено
          $ins = 0; // добавлено

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
    }

    return $this->render('index', compact('model'));
  }

  Отправка данных в виде из формы асинхронно(JS fetch).
  Проверено.Работает.
