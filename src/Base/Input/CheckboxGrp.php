<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc:
 * 依赖:
 */

namespace Aw\Ui\Base\Input;


use Aw\Ui\Base\Element;

class CheckboxGrp extends Element
{
    public $domain;

    /**
     *
     * @param string $name
     * @param array $domain
     * @param array $def
     * @param string $tagname
     * @param array|string $attr
     *            如果存在组标签，如果DIV，这是DIV的属性
     */
    public function __construct($name, $domain = array(), $def = array(), $tagname = "", $attr = array())
    {
        parent::__construct($tagname, $attr);
        foreach ($domain as $dk => $dv) {
            $r = new Checkbox ($name, $dk);
            $this->childNodes [] = $r;
            if (is_array($def) && in_array($dk, $def)) {
                $r->setChecked();
            }
            if (!is_null($dv)) {
                $r->setLabel($dv);
            } else {
                $r->setLabel($dk);
            }
        }
        $this->domain = $domain;
    }

    private function getRandChar($length = 4)
    {
        $str = "";
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol [rand(0, $max)];
        }

        return $str;
    }

    /**
     * 参数数组可设置三个参数
     * label 里面可用两个占位符,:for,:label
     * element 一个占位符 :element
     * id 两个占位符,:id,:index
     *
     * @param array $tpl
     * @id string 用于替换ID 占位符,:id
     * @param string $id
     *            留空随机产生
     * @return $this
     */
    public function setLabel($tpl = array(), $id = '')
    {
        $def = array(
            "label" => '<label for=":for">:label</label>',
            "element" => '<span>:element</span>',
            "id" => ':id_:index'
        );
        if ($id == "") {
            $id = $this->getRandChar(6);
        }
        $arg = array_merge($def, $tpl);
        $this->map(function ($index, checkbox $cb) use ($arg, $id) {
            $cb->setId(strtr($arg ["id"], array(
                ":id" => $id,
                ":index" => $index
            )));
            $cb->wrap($arg ["element"], ":element");
            $cb->label = strtr($arg ["label"], array(
                ":for" => $cb->getId(),
                ":label" => $cb->label
            ));
        });
        return $this;
    }

    /**
     * 传递参数两个参数
     * item,类型为\Aw\Ui\Base\checkbox
     * index 孩子中顺序
     *
     * @param callback $callback
     * @return $this
     */
    public function map($callback)
    {
        if (is_callable($callback)) {
            $i = 0;
            foreach ($this->childNodes as $item) {
                call_user_func_array($callback, array(
                    "index" => $i,
                    "item" => $item
                ));
                $i++;
            }
        }
        return $this;
    }
}

