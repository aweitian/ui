<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月9日
 * @Desc: 
 * 依赖:
 */
namespace tian\ui;

class pagination {
	public $pagination;
	/**
	 *
	 * @var \Tian\Ui\Base\Node
	 */
	public $select;
	/**
	 *
	 * @var \Tian\Ui\Base\Node
	 */
	public $btnGrp;
	/**
	 *
	 * @var \Tian\Ui\Base\Node
	 */
	public $btnPriv;
	/**
	 *
	 * @var \Tian\Ui\Base\Node
	 */
	public $btnNext;
	public $selectEnabled = true;
	public function __construct(\tian\pagination $pagination) {
		$this->pagination = $pagination;
		$this->btnGrp = new \Tian\Ui\Base\Node ( "" );
		$this->btnPriv = null;
		$this->btnNext = null;
		$this->select = null;
	}
	public function setSelectEnabled($f) {
		$this->selectEnabled = $f;
		return $this;
	}
	public function wrap(\Tian\Ui\paginationWrap\paginationWrap $wrap) {
		$wrap->wrap ( $this );
		return $this;
	}
}