<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace Tian\Ui\Base\Input;

use Tian\Ui\Base\FormInput;

class Datetime extends FormInput {
	public function __construct($name = "", $value = "") {
	    parent::__construct("input",array (
            "type" => "datetime",
            "value" => $value
        ),true);
		if ($name) {
			$this->setName ( $name );
		}
	}
}