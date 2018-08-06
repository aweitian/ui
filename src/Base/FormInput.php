<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月3日
 * @Desc: 
 * 依赖:
 */
namespace Aw\Ui\Base;

class FormInput extends Node
{
	public $alias;
	public $name;

    /**
     * @param string $name
     * @return Node
     */
	public function setName($name) {
		$this->name = $name;
		return parent::setName ( $name );
	}

    /**
     * @param string $ak
     * @param null $av
     * @return Node
     */
	public function setAttr($ak, $av = null) {
		if (strtolower ( $ak ) == "name") {
			$this->name = $av;
		}
		return parent::setAttr ( $ak, $av );
	}
}
