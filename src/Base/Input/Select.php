<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace Tian\Ui\Base\Input;

use Tian\Ui\Base\FormInput;

class Select extends FormInput {
	public $domain = array ();
	
	/**
	 *
	 * @param string $name
	 * @param array $domain
	 * @param array $def
	 * @param bool $muti
	 */
	public function __construct($name, $domain = array(), $def = array(), $muti = false) {
        parent::__construct("select");
		foreach ( $domain as $dk => $dv ) {
			$r = new Option ( $dv, $dk );
			if ($muti) {
				$this->setMuti ();
				if (is_array ( $def ) && in_array ( $dk, $def )) {
					$r->setSelected ();
				}
			} else {
				if (is_string ( $def ) && $def == $dk) {
					$r->setSelected ();
				}
			}
			$this->appendNode ( $r );
		}
		$this->domain = $domain;
		$this->setName ( $name );
	}

    /**
     * @return $this
     */
	public function setMuti() {
		$this->setAttr ( "multiple", "multiple" );
		return $this;
	}

    /**
     * @param $size
     * @return $this
     */
	public function setSize($size) {
		$this->setAttr ( "size", $size );
		return $this;
	}
	
	/**
	 * 传递参数两个参数
	 * item,类型为\Tian\Ui\Base\option
	 * index 孩子中顺序
	 *
	 * @param callback $callback        	
	 * @return $this
	 */
	public function map($callback) {
		if (is_callable ( $callback )) {
			$i = 0;
			foreach ( $this->childNodes as $item ) {
				call_user_func_array ( $callback, array (
						"index" => $i,
						"item" => $item 
				) );
				$i ++;
			}
		}
		return $this;
	}
}

