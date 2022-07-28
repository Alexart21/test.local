В БД создал 2 таблицы auth и products. Поскольку в задании не было про миграции то миграции не писал.
SQL дамп базы в корне сайта(test.).

В таблице auth храниться только хеш ключа от строки '1234'

В таблице products поле products_id - id товара
поле prices - JSON в виде текста.Цены для всех 20 регионов.(MYSQL использовал а не Postgress.При выводе придеться парсить).

Модели соответственно /models/Auth.php и /models/Products.php
Контроллер controllers/TestController.php
Обработка пришедших данных в экшене index
Вид views/test/index.php

Собственно  основной PHP код в controllers/TestController.php

Отправка данных в виде из формы асинхронно(JS fetch).
Проверено.Работает.
