<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月5日
 * @Desc:
 *        tuple是component的容器
 *        \Aw\Data\FORM类为每个component设置合适的ELEMENT
 *
 *        数据和包装器
 * ===========================================================
 *        alias
 *            formname => alias
 *        defaultValue
 *            formname => value
 *        dataFilter
 *            formname => filter(callback)
 *        uiType
 *            formname => type
 *        domain
 *            formname => domain
 *        nameMap
 *            componentname => formname
 * ============================================================
 *
 * FORM类需要TUPLE类来设置数据
 * WRAP类型来生成UI
 * TUPLE类由COMPONENT类型组成，实现了COMPONENT类的有\Aw\MYSQL\FIELD
 *        FIELD类有NAME,DATATYPE属性，其中DATATYPE属性为MYSQL列属性
 *        NAME有 【列名】 和【表名.列名】 两种(有时需要多表同时操作)
 * \Aw\MYSQL\FIELDUI类型把\Aw\MYSQL\FIELD变成\Aw\Ui\ELEMENT
 * TB2TUPLE是MYSQL表名到TUPLE的转换类
 *        MYSQL表名到FIELD
 * 依赖:
 */

namespace Aw\Ui;

use Aw\Data\Tuple;
use Aw\Ui\Adapter\Mysql\FieldUI;
use Aw\Ui\Base\Node;
use Aw\Ui\Base\Form as BaseForm;
use Traversable;

class Form implements \IteratorAggregate
{
    /**
     * 数据源
     * @var Tuple
     */
    protected $tuple;
    /**
     *
     * @var BaseForm
     */
    public $form;
    protected $children = array();

    /**
     * 默认值
     * @var array
     */
    protected $default = array();
    /**
     * key 为 form_name
     * 字段区间约束
     * @var array
     */
    protected $domain = array();
    /***
     * key 为 form_name
     * 字段UI类型
     * @var array
     */
    protected $uiType = array();
    /**
     * component的NAME到FORM的NAME的映射
     *
     * @var array
     */
    protected $nameMap = array();
    /**
     * key 为 form_name
     * name => alias
     * 表单字段的先后顺序为此为准
     *
     * @var array
     */
    protected $alias = array();
    /***
     * 从数据源数据到UI显示数据过滤
     * callback 参数顺序 this,this->tuple,component->default
     * @var array
     */
    protected $dataFilter = array();

    /**
     * @var FieldUI
     */
    private $ui;

    public function __construct()
    {
        $this->form = new BaseForm();
        $this->ui = new FieldUI();
    }

    /**
     *
     * @param array $filter
     * @return $this
     */
    public function setDataFilter(array $filter)
    {
        $this->dataFilter = $filter;
        return $this;
    }

    /**
     * @param $form_name
     * @param $callback
     * @return $this
     */
    public function setItemDataFilter($form_name, $callback)
    {
        $this->dataFilter[$form_name] = $callback;
        return $this;
    }

    /**
     *
     * @param array $def
     * @return $this
     */
    public function setDefaultValue(array $def)
    {
        $this->default = $def;
        return $this;
    }

    /**
     * @param $form_name
     * @param $value
     * @return $this
     */
    public function setItemDefaultValue($form_name, $value)
    {
        $this->default[$form_name] = $value;
        return $this;
    }

    /**
     *
     * @param array $domain
     * @return $this
     */
    public function setDomain(array $domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @param $form_name
     * @param $domain
     * @return $this
     */
    public function setItemDomain($form_name, $domain)
    {
        $this->domain[$form_name] = $domain;
        return $this;
    }

    /**
     *
     * @param array $map
     * @return $this
     */
    public function setNameMap(array $map)
    {
        $this->nameMap = $map;
        return $this;
    }

    /**
     * @param $component_name
     * @param $form_name
     * @return $this
     */
    public function setItemNameMap($component_name, $form_name)
    {
        $this->nameMap[$component_name] = $form_name;
        return $this;
    }

    /**
     *
     * @param Tuple $tuple
     * @return $this
     */
    public function setTuple(Tuple $tuple)
    {
        $this->tuple = $tuple;
        return $this;
    }

    /**
     *
     * @param string $formName
     * @param string $alias
     * @return $this
     */
    public function setAlias($formName, $alias)
    {
        $this->alias [$formName] = $alias;
        return $this;
    }

    /**
     *
     * @param string $formName
     * @return $this
     */
    public function rmAlias($formName)
    {
        if (isset ($this->alias [$formName])) {
            unset ($this->alias [$formName]);
        }
        return $this;
    }

    /**
     *
     * @param array $alias
     * @return $this
     */
    public function mergeAlias(array $alias)
    {
        $this->alias = array_merge($this->alias, $alias);
        return $this;
    }

    /**
     *
     * @param string $formName
     * @param string $def
     * @return string
     */
    public function getAlias($formName, $def = "")
    {
        return array_key_exists($formName, $this->alias) ? $this->alias [$formName] : $def;
    }

    /**
     *
     * @return array:
     */
    public function alias()
    {
        return $this->alias;
    }

    /**
     * 可用的类型有:
     * button checkboxGrp date datetime
     * file image image password
     * radioGrp reset select submit
     * text textarea
     *
     * @param array $type
     * @return $this
     */
    public function setUiType(array $type)
    {
        $this->uiType = $type;
        return $this;
    }

    /**
     * button checkboxGrp date datetime
     * file image image password
     * radioGrp reset select submit
     * text textarea
     * @param $form_name
     * @param $type
     * @return $this
     */
    public function setItemUiType($form_name, $type)
    {
        $this->uiType[$form_name] = $type;
        return $this;
    }

    /**
     *
     * @param $formName
     * @return bool
     */
    public function hasElem($formName)
    {
        return array_key_exists($formName, $this->children);
    }

    /**
     *
     * @param $formName
     * @param Node $node
     * @return $this
     */
    public function appendNode($formName, Node $node)
    {
        $this->children [$formName] = $node;
        $this->form->appendNode($node);
        return $this;
    }

    /**
     *
     * @return BaseForm
     */
    public function getFormElement()
    {
        return $this->form;
    }

    /**
     *
     * @param $formName
     * @param int $pos
     * @param Node $node
     */
    public function insertNode($formName, $pos, Node $node)
    {
        $this->children [$formName] = $node;
        $this->form->insertNode($pos, $node);
    }

    /**
     *
     * @param $formName
     * @param Node $node
     */
    public function prependNode($formName, Node $node)
    {
        $this->children [$formName] = $node;
        $this->form->prependNode($node);
    }

    public function get($form_name)
    {
        return isset($this->children[$form_name]) ? $this->children[$form_name] : null;
    }

    /**
     *
     * @param $formName
     * @return bool
     */
    public function removeNode($formName)
    {
        if (!array_key_exists($formName, $this->children))
            return false;
        return $this->form->remove($this->children[$formName]) > 0;
    }

    public function init()
    {
        if (is_null($this->tuple))
            return;
        // var_dump($this->tuple);
        foreach ($this->tuple as $component) {
            $formName = array_key_exists($component->name, $this->nameMap) ? $this->nameMap [$component->name] : $component->name;
            if (array_key_exists($formName, $this->default)) {
                $component->default = $this->default [$formName];
            }
            if (array_key_exists($formName, $this->dataFilter)) {
                if (is_callable($this->dataFilter [$formName])) {
                    $component->default = call_user_func_array($this->dataFilter [$formName], array(
                        $this,
                        $this->tuple,
                        $component->default
                    ));
                }
            }

            if (array_key_exists($formName, $this->domain)) {
                $component->domain = $this->domain [$formName];
            }

            if ($this->ui->setComponent($component)->match()) {
                $this->appendNode($formName, $this->ui->element);
                if (array_key_exists($formName, $this->alias)) {
                    $component->alias = $this->alias [$formName];
                }
                $this->setAlias($formName, $component->alias);
                $this->ui->element->setName($formName);
            }
        }
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator ($this->children);
    }
}