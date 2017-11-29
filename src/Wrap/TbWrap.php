<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月7日
 * @Desc: 
 * 依赖:
 */
namespace Tian\Ui\Wrap;
use Tian\Ui\Base\Element;
use Tian\Ui\base\TextNode;
use Tian\Ui\Form;

class TbWrap extends Wrap
{
	/**
	 *
	 * @var Element
	 */
	public $form;
	/**
	 *
	 * @var Element
	 */
	public $table;
	/**
	 *
	 * @var Element
	 */
	public $thead;
	/**
	 *
	 * @var Element
	 */
	public $tbody;
	/**
	 *
	 * @var Element
	 */
	public $tfoot;
	/**
	 *
	 * @var Element
	 */
	public $caption;
	public $alias;
	public function __construct() {
	}
	public function wrap(Form $form) {
		$this->form = $form;
		$this->alias = $form->alias ();
		$this->table = new Element ( "table" );
		$this->caption = new Element ( "caption" );
		$this->thead = new Element ( "thead" );
		$this->tbody = new Element ( "tbody" );
		$this->tfoot = new Element ( "tfoot" );
		$this->table->appendNode ( $this->caption );
		$this->table->appendNode ( $this->thead );
		$this->table->appendNode ( $this->tbody );
		$this->table->appendNode ( $this->tfoot );
		foreach ( $this->alias as $field => $alias ) {
			if (! $this->form->hasElem ( $field ))
				continue;
			$tr = new Element ( "tr" );
			if ($alias) {
				$td1 = new Element ( "td" );
				$td1->appendNode ( new Textnode ( $alias ) );
				$td2 = new Element ( "td" );
				$node = $this->form->getNode ( $field );
				$td2->appendNode ( $node );
				$tr->appendNode ( $td1 );
				$tr->appendNode ( $td2 );
				$this->tbody->appendNode ( $tr );
			} else {
				$td2 = new Element ( "td", array (
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