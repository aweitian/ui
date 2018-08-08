<?php
require __DIR__ . "/../vendor/autoload.php";

$connection = new \Aw\Db\Connection\Mysql(array(
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'm11',
        'user' => 'root',
        'password' => 'root',
        'charset' => 'utf8'
    )
);

$tuple = new \Aw\Ui\Adapter\Mysql\Table2Tuple($connection,true);
$tuple->setTbName("admin");
$tuple->initTuple();

//component的NAME中都有.格式为 表名.字段名
var_dump($tuple->tuple);
