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


/*
CREATE TABLE `admin` (
  `admin_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `pass` varchar(32) DEFAULT '',
  `real_name` varchar(32) DEFAULT NULL,
  `pid` int(10) unsigned DEFAULT NULL COMMENT 'editor的operator',
  `role` enum('admin','operator','editor') DEFAULT NULL,
  `status` enum('normal','block') DEFAULT 'normal',
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

CREATE TABLE `admin_site` (
  `admin_id` int(10) unsigned DEFAULT NULL,
  `site_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `site` (
  `site_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(16) DEFAULT NULL,
  `root_path` varchar(1024) DEFAULT NULL COMMENT '服务器上站点根目录文件路径',
  `domain` varchar(64) DEFAULT NULL,
  `https` tinyint(1) DEFAULT '0',
  `code_mgr` varchar(1024) DEFAULT NULL COMMENT '代码管理连接信息',
  `db_host` varchar(32) DEFAULT '127.0.0.1',
  `db_name` varchar(32) DEFAULT NULL,
  `db_user` varchar(32) DEFAULT NULL,
  `db_pass` varchar(32) DEFAULT NULL,
  `db_port` int(11) DEFAULT '3306',
  `table_prefix` varchar(16) DEFAULT 'db_',
  `template_dir` varchar(16) DEFAULT '0',
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`site_id`),
  UNIQUE KEY `domain` (`domain`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8




 */



$tuple = new \Aw\Ui\Adapter\Mysql\Table2Tuple($connection);
$tuple->setTbName("admin");
$ret = $tuple->init();
if ($ret) {
    print "OK\n";

    $form = new \Aw\Ui\Form();
    $form->form->appendNode(new \Aw\Ui\base\TextNode("<table>"));
    $form->setTuple($tuple->getResult());
    $form->init();

    $form->form->map(function ($index, $node) use ($tuple) {

        if ($node instanceof \Aw\Ui\Base\TagNode) {
            $field = $tuple->tuple->get($index);
            if ($field instanceof \Aw\Ui\Adapter\Mysql\Field) {
                $node->setWrap("<tr><td>{$field->name}</td><td>:element</td></tr>");
            }

        }
    }, false);
    //$form->form->prependNode(new \Aw\Ui\base\TextNode("<table>"));
    $form->form->appendNode(new \Aw\Ui\base\TextNode("</table>"));
    var_dump($form->form->dumpHtml());


    $html = '';
    foreach ($form as $form_name => $item)
    {
        $html .= "<tr><td>{$form->getAlias($form_name)}</td><td>{$item->dumpHtml()}</td></tr>\n";
    }
    var_dump($html);

    var_dump($form->get("admin_id")->dumpHtml());
} else {
    print "fail\n";
}


