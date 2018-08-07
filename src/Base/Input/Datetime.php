<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc:
 * 依赖:
 */

namespace Aw\Ui\Base\Input;


use Aw\Ui\Base\LeafElement;

class Datetime extends LeafElement
{
    public function __construct($name = "", $value = "")
    {
        parent::__construct("input", array(
            "type" => "datetime",
            "value" => $value
        ));
        if ($name) {
            $this->setName($name);
        }
    }
}