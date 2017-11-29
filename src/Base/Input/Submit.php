<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace Tian\Ui\Base\Input;

use Tian\Ui\Base\FormInput;

class Submit extends FormInput {
	public function __construct($value = "") {
        parent::__construct("input",array (
            "type" => "submit",
            "value" => $value
        ),true);
	}
}