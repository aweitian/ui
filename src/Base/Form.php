<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月5日
 * @Desc: 
 * 依赖:
 */
namespace Aw\Ui\Base;



class Form extends Element {
	public $method = "get";
	public $action = "";
	public $isUpload = false;
	public function __construct() {
		parent::__construct("form");
	}
	
	/**
	 *
	 * @return $this
	 */
	public function setUploadForm() {
		$this->setAttr ( "enctype", "multipart/form-data" );
		return $this;
	}
	/**
	 * _blank,_self,_parent,_top,framename
	 *
	 * @param string $tar        	
	 * @return $this
	 */
	public function setTarget($tar = "_blank") {
		$this->setAttr ( "target", $tar );
		return $this;
	}
	
	/**
	 * application/x-www-form-urlencoded,multipart/form-data,text/plain
	 *
	 * @param string $enc        	
	 * @return $this
	 */
	public function setEnctype($enc = "application/x-www-form-urlencoded") {
		$this->setAttr ( "enctype", $enc );
		return $this;
	}
	/**
	 * UTF-8,ISO-8859-1,gb2312
	 *
	 * @param string $cs        	
	 * @return $this
	 */
	public function setCharset($cs = "UTF-8") {
		$this->setAttr ( "accept-charset", $cs );
		return $this;
	}
	/**
	 *
	 * @param string $act
	 * @return $this
	 */
	public function setAction($act) {
		$this->setAttr ( "action", $act );
		return $this;
	}
	/**
	 *
	 * @param string $m        	
	 * @return $this
	 */
	public function setMethod($m) {
		$this->setAttr ( "method", $m );
		return $this;
	}
}