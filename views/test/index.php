<?php
use app\models\DataForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// Здесь хэш в поле key_hash таблицы auth
// echo Yii::$app->getSecurity()->generatePasswordHash('1234');
//  die;
?>

<h1>Test</h1>

<form onsubmit="send();return false" id="data-form" action="/web/index.php?r=test" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="aR-XklKCfCSHz9fXkb1ISuMueUuIFPuCoatdjMHeXIQ2VqLFE_QNFOqc5Y3HjzgHvFYMMvF5orb1kgvejacu7g==">
    <div class="form-group field-dataform-key required">
        <label class="control-label" for="dataform-key">Key</label>
        <input type="text" id="dataform-key" class="form-control" name="DataForm[key]" autofocus aria-required="true">

        <div class="help-block"></div>
    </div>
    <div class="form-group field-dataform-data required">
        <label class="control-label" for="dataform-data">Data</label>
        <input type="hidden" name="DataForm[data]" value=""><input type="file" id="dataform-data" name="DataForm[data]" aria-required="true">

        <div class="help-block"></div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Отправить</button></div>
</form>
<script>
    let form = document.forms[0];
    async function send(){
      let formData = new FormData(form);
      let url = '/web/index.php?r=test';
      let response = await fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
        },
      });
      let result = await response.json(); // с сервера json вида {status: true, msg:{}}
      result = JSON.parse(result);
      if(response.ok){
        if(result.isOk){
          console.log(result);
          /*
          чего то делаем с JSON вида
           {"isOk": true,"update": 2,"insert": 0,"all": 2}
          */
        }else{ // rollback транзакции или иная ошибка
          console.log(result.err);
        }
      }else{
        console.log(response)
      }
    }
</script>
