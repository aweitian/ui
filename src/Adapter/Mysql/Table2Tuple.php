<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月5日
 * @Desc:
 * 依赖:
 */

namespace Aw\Ui\Adapter\Mysql;

use Aw\Data\tuple;
use Aw\Db\Connection\Mysql;

class Table2Tuple
{
    /**
     *
     * @var Mysql
     */
    public $connection;
    public $tbname;

    /**
     *
     * @var tuple
     */
    public $tuple;
    private $alias = array();
    /**
     *
     * @var array 元素为要保留的字段名
     */
    public $mask = null;
    public $skipAutoincrement = false;
    /**
     * 为真，返回的NAME格式为 表名.字段名
     * 为假，返回的NAME格式为 字段名
     *
     * @var bool
     */
    public $namespaceName = false;

    /**
     *
     * @param Mysql $connection
     * @param bool $namespaceName
     */
    public function __construct(Mysql $connection, $namespaceName = false)
    {
        $this->connection = $connection;
        $this->tuple = new tuple ();
        $this->namespaceName = $namespaceName;
    }

    /**
     *
     * @param string $tbname
     * @return $this
     */
    public function setTbName($tbname)
    {
        $this->tbname = $tbname;
        return $this;
    }

    /**
     * 统一起见，数组的KEY为namespaceName成员有关
     *
     * @param array $alias
     * @return $this
     */
    public function setAlias(array $alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * 统一起见，数组的KEY和namespaceName成员有关
     *
     * @param array $mask
     * @return $this
     */
    public function setMask(array $mask)
    {
        $this->mask = $mask;
        return $this;
    }

    /**
     *
     * @param bool $flag
     * @return $this
     */
    public function setSkipAutoIncrement($flag)
    {
        $this->skipAutoincrement = !!$flag;
        return $this;
    }

    /**
     * code message data
     * @return array
     */
    public function initTuple()
    {
        if (!is_string($this->tbname))
            return array('code' => 500, 'message' => "缺少表名");
        return $this->initFields();
    }

    private function initFields()
    {
        $res = $this->connection->fetchAll("SHOW FULL COLUMNS FROM `$this->tbname`");

        // var_dump($this->mask);

        // | Field | Type | Collation | Null | Key | Default | Extra | Privileges | Comment |
        foreach ($res as $v) {
            // name,alias,dataType,domain,default,comment
            // ,isUnsiged,allowNull,isPk,isAutoIncrement
            if ($this->namespaceName) {
                $fieldname = $this->tbname . "." . $v ["Field"];
            } else {
                $fieldname = $v ["Field"];
            }

            if (is_array($this->mask) && !in_array($fieldname, $this->mask))
                continue;
            if ($this->skipAutoincrement === true && $v ["Extra"] === 'auto_increment')
                continue;
            $tlu = $this->_parseType($v ["Type"]);
            $data = array(
                "name" => $fieldname,
                "alias" => (array_key_exists($fieldname, $this->alias)) ? ($this->alias [$fieldname]) : ($v ["Comment"] ? $v ["Comment"] : $fieldname),
                "dataType" => $tlu ["type"],
                "domain" => $tlu ["len"],
                "default" => $v ["Default"],
                "comment" => $v ["Comment"],
                "allowNull" => $v ["Null"] === "YES",
                "isUnsiged" => $tlu ["unsiged"],
                "isPk" => $v ["Key"] == "PRI",
                "isAutoIncrement" => $v ["Extra"] === 'auto_increment'
            );
            $field = new Field($data);
            $this->tuple->append($field);
        }
        return array('code' => 200, 'data' => array(), 'message' => 'OK');
    }

    private function _parseType($t)
    {
        if (preg_match("/^[a-z]+$/", $t)) {
            return array(
                'type' => $t,
                'len' => null,
                'unsiged' => null
            );
        } else if (preg_match("/^(set|enum)\\(((?:'\\w+',)*'\w+')\\)$/", $t, $matches)) {
            $arr = explode(",", str_replace("'", "", $matches [2]));
            return array(
                'type' => $matches [1],
                'len' => array_combine($arr, $arr),
                'unsiged' => null
            );
        } else if (preg_match("/^([a-z]+)\(([0-9]+)\)( unsigned)?$/", $t, $matches)) {
            return array(
                'type' => $matches [1],
                'len' => $matches [2],
                'unsiged' => (isset ($matches [3])) ? true : false
            );
        } else {
            return array(
                'type' => null,
                'len' => null,
                'unsiged' => null
            );
        }
    }
}