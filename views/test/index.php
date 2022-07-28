<?php

use app\models\DataForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

//use Yii;

// Здесь хэш в поле key_hash таблицы auth
// echo Yii::$app->getSecurity()->generatePasswordHash('1234');
//  die;
$csrf_token = Yii::$app->request->csrfToken;
?>

<h1>Test</h1>

<form onsubmit="return false" id="data-form" action="/web/index.php?r=test" method="post" enctype="multipart/form-data">
    <div class="form-group field-dataform-key required">
        <label class="control-label" for="dataform-key">Ключ авторизации</label>
        <input type="text" id="dataform-key" class="form-control" name="DataForm[key]" autofocus aria-required="true"
               required="true">

        <div class="help-block"></div>
    </div>
    <div class="form-group field-dataform-data required">
        <label class="control-label" for="dataform-data">Файл с JSON данными</label>
        <input type="hidden" name="DataForm[data]" value="">
        <input id="fileInp" type="file" id="dataform-data" name="DataForm[data]" aria-required="true" required="true">

        <div class="help-block"></div>
    </div>
    <div class="form-group">
        <button id="send-btn" type="button" class="btn btn-primary">Отправить</button>
    </div>
</form>
<script>
  let form = document.forms[0];
  let btn = document.getElementById('send-btn');
  btn.addEventListener('click', () => {
    let fileInp = document.getElementById('fileInp');
    let file = fileInp.files[0];
    let key = document.getElementById('dataform-key').value;
    if (!file || !key) {
      alert('Введите ключ и выберите файл!');
      return;
    }
    let reader = new FileReader();
    reader.readAsText(file);
    reader.onload = function () {
      let formData = new FormData();
      formData.append('key', key);
      formData.append('data', reader.result);
      formData.append('_csrf', '<?= $csrf_token ?>');
      let url = '/web/index.php?r=test';
      //
      send(url, formData);
    };
  });

  async function send(url, data) {
    // return;
    let response = await fetch(url, {
      method: 'POST',
      body: data,
      headers: {},
    });
    let result = await response.json(); // с сервера json вида "{"isOk":true,"update":2,"insert":0,"all":2}"
    result = JSON.parse(result);
    if (response.ok) {
      if (result.isOk) {
        console.log(result);
        /*
        чего то делаем с JSON вида
         {"isOk": true,"update": 2,"insert": 0,"all": 2}
        */
      } else { // неверный ключ, rollback транзакции или иная ошибка
        console.log(result.err);
      }
    } else {
      console.log(response)
    }
  }
</script>
