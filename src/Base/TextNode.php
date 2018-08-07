<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月3日
 * @Desc: 
 * 依赖:
 */
namespace Aw\Ui\base;

class TextNode extends Node {
	private $textContent;
	public function __construct($text) {
		$this->setText ( $text );
	}

    /**
     * @param $text
     */
	public function setText($text) {
		$this->textContent = $text;
	}

    /**
     * @return mixed
     */
	public function getNodeHtml() {
		return $this->textContent;
	}
}