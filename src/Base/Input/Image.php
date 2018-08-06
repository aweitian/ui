<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace Aw\Ui\Base\Input;

use Aw\Ui\Base\FormInput;

class Image extends FormInput {
	public function __construct($src = "") {
        parent::__construct("input",array (
            "type" => "image",
            "src" => $src
        ),true);
	}

    /**
     * @param $src
     * @return $this
     */
	public function setSrc($src) {
		$this->setAttr ( "src", $src );
		return $this;
	}
}