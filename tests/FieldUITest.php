<?php
require __DIR__ . "/../vendor/autoload.php";

//name,alias,dataType,domain,default,comment,isUnsiged,allowNull,isPk,isAutoIncrement
$field = new \Aw\Ui\Adapter\Mysql\Field(array(
    "name" => "local",
    "id" => "local",
    /*
     * MYSQL可用data type:
     * ----------------------------------------------------------------
     * tinyint,smallint,int,decimal,mediumint,float,double,
     * tinyblob,varchar,char,binary,varbinary,time,year,tinytext,
     * blob,mediumblob,mediumtext,longblob,longtext,text,
     * datetime,timestamp,date,enum,set
    */
    "dataType" => "enum",
    'domain' => array('aaa', 'bbb', 'ccc'),
    'default' => 'bbb',
    'comment' => 'address of home',
    'isUnsiged' => true,
    'allowNull' => false,
    'isPk' => false,
    'isAutoIncrement' => false
));

$ui = new \Aw\Ui\Adapter\Mysql\FieldUI($field);
$ui->match();
var_dump($ui->element);

//====================================================================================

$field = new \Aw\Ui\Adapter\Mysql\Field(array(
    "name" => "age",
    "id" => "age",
    /*
     * MYSQL可用data type:
     * ----------------------------------------------------------------
     * tinyint,smallint,int,decimal,mediumint,float,double,
     * tinyblob,varchar,char,binary,varbinary,time,year,tinytext,
     * blob,mediumblob,mediumtext,longblob,longtext,text,
     * datetime,timestamp,date,enum,set
    */
    "dataType" => "int",
    'default' => 5,
    'comment' => 'number of age',
    'isUnsiged' => true,
    'allowNull' => false,
    'isPk' => false,
    'isAutoIncrement' => false
));

if (!$field->domainChk('ccc')) {
    print "OK.\n";
} else {
    print "Fail.\n";
}

$ui = new \Aw\Ui\Adapter\Mysql\FieldUI($field);
$ui->match();
var_dump($ui->element);

if ($field->domainChk(2)) {
    print "OK.\n";
} else {
    print "Fail.\n";
}


if ($field->domainChk('20')) {
    print "OK.\n";
} else {
    print "Fail.\n";
}


if (!$field->domainChk(-20)) {
    print "OK.\n";
} else {
    print "Fail.\n";
}
