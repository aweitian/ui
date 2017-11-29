<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月12日
 * @Desc: 
 * 依赖:
 */
namespace Tian\Ui\PaginationWrap;

use Tian\Ui\Base\Element;
use Tian\Ui\base\TextNode;
use Tian\Ui\Pagination;

class ulWrap extends paginationWrap {
	/**
	 *
	 * @var Element
	 */
	public $ul;
	public function wrap(Pagination $pagination) {
		$this->initSelect ( $pagination );
		$this->ul = new Element ( "ul" );
		if ($pagination->pagination->hasPre ()) {
			$li = new Element ( "li" );
			$a = new Element ( "a" );
			$li->appendNode ( $a );
			$a->appendNode ( new TextNode ( $pagination->pagination->getPre () ) );
			$pagination->btnPriv = $li;
		}
		
		for($i = $pagination->pagination->getStartPage (); $i <= $pagination->pagination->getMaxPage (); $i ++) {
			$li = new Element ( "li" );
			$a = new Element ( "a" );
			$li->appendNode ( $a );
			$a->appendNode ( new TextNode ( $i ) );
			$pagination->btnGrp->appendNode ( $li );
		}
		
		if ($pagination->pagination->hasNext ()) {
			$li = new Element ( "li" );
			$a = new Element ( "a" );
			$li->appendNode ( $a );
			$pagination->btnNext = $li;
			$a->appendNode ( new TextNode ( $pagination->pagination->getNext () ) );
		}
		$pagination->btnGrp = $this->ul;
	}
	private function initSelect(pagination $pagination) {
		if ($pagination->selectEnabled) {
			$pagination->select = new Element ( "select" );
			for($i = 0; $i < $pagination->pagination->getMaxPage (); $i ++) {
				$option = new Element ( "option" );
				$option->setText ( $i + 1 );
				$pagination->select->appendNode ( $option );
			}
		}
	}
}