<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace Aw\Ui\Base\Input;

use Aw\Ui\Base\FormInput;

class Date extends FormInput {
	public function __construct($name = "", $value = "") {
	    parent::__construct("input",array (
            "type" => "date",
            "value" => $value
        ),true);
		if ($name) {
			$this->setName ( $name );
		}
	}
}