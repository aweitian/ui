<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace Aw\Ui\Base\Input;

use Aw\Ui\Base\Element;
use Aw\Ui\base\TextNode;

class Textarea extends Element {
	/**
	 *
	 * @var \Aw\Ui\Base\TextNode;
	 */
	public $textNode;
	public function __construct($name = "", $value = "") {
	    parent::__construct("textarea");
		if ($name) {
			$this->setName ( $name );
		}
		$this->textNode = new Textnode ( $value );
		$this->appendNode ( $this->textNode );
	}

    /**
     * @param $val
     * @return $this
     */
	public function setValue($val) {
		$this->textNode->setText ( $val );
		return $this;
	}

    /**
     * @return $this
     */
	public function setRequire() {
		$this->setAttr ( "require" );
		return $this;
	}

    /**
     * @return $this
     */
	public function rmRequire() {
		$this->rmAttr ( "require" );
		return $this;
	}

    /**
     * @param $placeholder
     * @return $this
     */
	public function setPlaceholder($placeholder) {
		$this->setAttr ( "placeholder", $placeholder );
		return $this;
	}

    /**
     * @return $this
     */
	public function rmPlaceholder() {
		$this->rmAttr ( "placeholder" );
		return $this;
	}
}