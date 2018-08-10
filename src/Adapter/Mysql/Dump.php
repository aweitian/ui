<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月5日
 * @Desc:
 * 依赖:
 */

namespace Aw\Ui\Adapter\Mysql;

use Aw\Data\Tuple;
use Aw\Db\Connection\Mysql;

/**
 * Class Dump
 * @package Aw\Ui\Adapter\Mysql
 * 职责:
 * 把表导出
 */
class Dump
{
    public $error;
    /**
     *
     * @var Mysql
     */
    public $connection;

    public function __construct(Mysql $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param $tab
     * @param string $ns
     * @return array
     */
    public function dump($tab, $ns = 'auto')
    {
        $tuple = new Tuple();
        if (is_array($tab)) {
            if ($ns == 'auto') {
                $ns = true;
            }
            $t2t = new Table2Tuple($this->connection, $ns);
            foreach ($tab as $tb) {
                $t2t->setTbName($tb);
                $t2t->init();
                $tuple->appendTuple($t2t->getResult());
            }
        } else if (is_string($tab)) {
            if ($ns == 'auto') {
                $ns = false;
            }
            $t2t = new Table2Tuple($this->connection, $ns);
            $t2t->setTbName($tab);
            $t2t->init();
            $tuple = $t2t->getResult();
        } else {
            $this->error = "invalid tab arg";
            return array();
        }
        return $tuple->dump();
    }
}