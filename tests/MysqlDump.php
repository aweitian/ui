<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/10
 * Time: 10:42
 */

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

$dump = new \Aw\Ui\Adapter\Mysql\Dump($connection);

print var_export($dump->dump(array(
    "admin","admin_site"
)));