<?php
// хост БД
define('db_host', 'localhost');

// Имя БД
define('db_name', '');

// Пользователь БД
define('db_user', '');

// Пароль БД
define('db_pass', '');

include_once 'classes/db.class.php';
include_once "functions.php";

$db = new DB_class(db_host, db_name, db_user, db_pass);

?>