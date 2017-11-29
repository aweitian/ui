<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月12日
 * @Desc: 
 * 依赖:
 */
namespace Tian\Ui\paginationWrap;

class ulWrap extends paginationWrap {
	/**
	 *
	 * @var \Tian\Ui\Base\Node
	 */
	public $ul;
	public function wrap(\Tian\Ui\pagination $pagination) {
		$this->initSelect ( $pagination );
		$this->ul = new \Tian\Ui\Base\Node ( "ul" );
		if ($pagination->pagination->hasPre ()) {
			$li = new \Tian\Ui\Base\Node ( "li" );
			$a = new \Tian\Ui\Base\Node ( "a" );
			$li->appendNode ( $a );
			$a->appendNode ( new \Tian\Ui\Base\textnode ( $pagination->pagination->getPre () ) );
			$pagination->btnPriv = $li;
		}
		
		for($i = $pagination->getStartPage (); $i <= $pagination->getMaxPage (); $i ++) {
			$li = new \Tian\Ui\Base\Node ( "li" );
			$a = new \Tian\Ui\Base\Node ( "a" );
			$li->appendNode ( $a );
			$a->appendNode ( new \Tian\Ui\Base\textnode ( $i ) );
			$pagination->btnGrp->appendNode ( $li );
		}
		
		if ($pagination->pagination->hasNext ()) {
			$li = new \Tian\Ui\Base\Node ( "li" );
			$a = new \Tian\Ui\Base\Node ( "a" );
			$li->appendNode ( $a );
			$pagination->btnNext = $li;
			$a->appendNode ( new \Tian\Ui\Base\textnode ( $pagination->pagination->getNext () ) );
		}
		$pagination->btnGrp = $this->ul;
	}
	private function initSelect(\Tian\Ui\pagination $pagination) {
		if ($pagination->selectEnabled) {
			$pagination->select = new \Tian\Ui\Base\Node ( "select" );
			for($i = 0; $i < $pagination->pagination->getMaxPage (); $i ++) {
				$option = new \Tian\Ui\Base\Node ( "option" );
				$option->setText ( $i + 1 );
				$pagination->select->appendNode ( $option );
			}
		}
	}
}