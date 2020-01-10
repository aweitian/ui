<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/9
 * Time: 14:24
 * secret
 */
require __DIR__ . "/../vendor/autoload.php";

$cg = new \Aw\Ui\Base\Input\CheckboxGrp("sex", array(
    1 => "male",
    2 => "female"
));
$cg->setLabel();
print $cg->dumpHtml();


print "\n===========================\n";

$cg = new \Aw\Ui\Base\Input\CheckboxGrp("sex", array(
    1 => "male",
    2 => "female"
), array('2'), 'div');
$cg->setLabel();
print $cg->dumpHtml();