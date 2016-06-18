<?php
 
$config = array(

'file_name' => 'adm.php', //Название Админки
	
'path' => '/cabinet/', //Путь к скрипту c слешемв начале и конце

'path_skin' => 'cabinet/skins/', //Путь к скинам c слешем на конце

'path_cloak' => 'cabinet/cloaks/', //Путь к плащам c слешем на конце

'sum_skin' => '0', //Цена загрузки скина

'sum_cloak' => '10', //Цена загрузки плаща

'kurs_game_money' => '800', //Курс обмена (на сколько игровых меняется 1 реальный рублб)
	
'limit_googs' => '50', //Количество товаров на странице
	
'templates' => 'default', //Шаблон

'admins' => array('admin', 'notch', 'itakdalee'),  //Ники админов через запятую, маленькими буквами

'charset' => 'UTF-8', //Кодирока страниц

'payment_inter' => '1', //Оплата при помощи интеркасса

'payment_robox' => '0', //Оплата при помощи робокассы

'ik_shop_id' => '',// Интеркасса id_shop_id

'secret_key' => '',// Интеркасса secret key

'robox_login' => '',//Логин робокассы

'robox_pass' => '',//Пароль робокассы

//Настройки группы по умолчанию

'group_name' => 'Игрок', //Названия группы по умолчанию 

'group_upload_skin' => '1', //Разрешить загрузку скинов (free), 1 - да, 0 - нет

'group_upload_hd_skin' => '1', //Разрешить загрузку HD скинов (free), 1 - да, 0 - нет

'group_upload_cloak' => '1', //Разрешить загрузку плащей (free), 1 - да, 0 - нет

'background' => '',  // Адрес к фону сайта вида http://вашсайт.ру/путь.../файл_фона.jpg

//END Настройки группы по умолчанию

'db_host' => 'localhost', //Адрес БД (По умолчанию localhost)

'db_name' => 'shopbase', // Название БД

'db_user' => 'root', // Пользователь БД

'db_pass' => '', // Пароль к БД

'db_pref' => '', // Префикс таблиц для магазина с '_'(нижний слэш) на конце

'db_charset' => 'utf8', //Кодировка БД

//Если у вас dle, то настройки ниже можно не менять

'db_users_table' => 'dle_users', //Таблица с логинами пользователей(ОТ CMS!) с префиксом

'db_username_column' => 'name', //Колонка с логинами пользователей

'db_password_column' => 'password', //Колонка с паролями пользователей

//Стили для тэга <body>
'body_style' => 'no-repeat; -moz-background-size: 100%; /* Firefox 3.6+ */ -webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */ -o-background-size: 100%; /* Opera 9.6+ */ background-size: 100%; background-attachment: fixed;',
	
);
	
?>