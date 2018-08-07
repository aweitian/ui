<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc:
 * 依赖:
 */

namespace Aw\Ui\Base\Input;

use Aw\Ui\Base\LeafElement;

class file extends LeafElement
{
    public function __construct($name = "", $value = "")
    {
        parent::__construct("input", array(
            "type" => "file",
            "value" => $value
        ));
        if ($name) {
            $this->setName($name);
        }
    }

    /**
     * @param $val
     * @return $this
     */
    public function setValue($val)
    {
        $this->setAttr("value", $val);
        return $this;
    }

    /**
     * @return $this
     */
    public function setRequire()
    {
        $this->setAttr("require");
        return $this;
    }

    /**
     * @return $this
     */
    public function rmRequire()
    {
        $this->rmAttr("require");
        return $this;
    }
}