<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月7日
 * @Desc: 
 * 依赖:
 */
namespace Tian\Ui\FormWrap;

class TbWrap extends FormWrap
{
	/**
	 *
	 * @var \Tian\Ui\Form
	 */
	public $form;
	/**
	 *
	 * @var \Tian\Ui\Base\Node
	 */
	public $table;
	/**
	 *
	 * @var \Tian\Ui\Base\Node
	 */
	public $thead;
	/**
	 *
	 * @var \Tian\Ui\Base\Node
	 */
	public $tbody;
	/**
	 *
	 * @var \Tian\Ui\Base\Node
	 */
	public $tfoot;
	/**
	 *
	 * @var \Tian\Ui\Base\Node
	 */
	public $caption;
	public $alias;
	public function __construct() {
	}
	public function wrap(\Tian\Ui\Form $form) {
		$this->form = $form;
		$this->alias = $form->alias ();
		$this->table = new \Tian\Ui\Base\Node ( "table" );
		$this->caption = new \Tian\Ui\Base\Node ( "caption" );
		$this->thead = new \Tian\Ui\Base\Node ( "thead" );
		$this->tbody = new \Tian\Ui\Base\Node ( "tbody" );
		$this->tfoot = new \Tian\Ui\Base\Node ( "tfoot" );
		$this->table->appendNode ( $this->caption );
		$this->table->appendNode ( $this->thead );
		$this->table->appendNode ( $this->tbody );
		$this->table->appendNode ( $this->tfoot );
		foreach ( $this->alias as $field => $alias ) {
			if (! $this->form->hasElem ( $field ))
				continue;
			$tr = new \Tian\Ui\Base\Node ( "tr" );
			if ($alias) {
				$td1 = new \Tian\Ui\Base\Node ( "td" );
				$td1->appendNode ( new \Tian\Ui\Base\textnode ( $alias ) );
				$td2 = new \Tian\Ui\Base\Node ( "td" );
				$node = $this->form->getNode ( $field );
				$td2->appendNode ( $node );
				$tr->appendNode ( $td1 );
				$tr->appendNode ( $td2 );
				$this->tbody->appendNode ( $tr );
			} else {
				$td2 = new \Tian\Ui\Base\Node ( "td", array (
						"colspan" => 2 
				) );
				$node = $this->form->getNode ( $field );
				$td2->appendNode ( $node );
				$tr->appendNode ( $td2 );
				$this->tbody->appendNode ( $tr );
			}
		}
		$this->form->getFormElement ()->clearNode ();
		$this->form->getFormElement ()->appendNode ( $this->table );
	}
}