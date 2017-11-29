<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月7日
 * @Desc: 
 * 依赖:
 */
namespace Tian\Ui\tableWrap;

class tbWrap extends \Tian\Ui\tableWrap\tableWrap {
	/**
	 *
	 * @var \Tian\Ui\table
	 */
	public $tb;
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
	public function __construct() {
	}
	/**
	 *
	 * @return number
	 */
	public function getColspan() {
		return isset ( $this->tb->data [0] ) ? count ( $this->tb->data [0] ) : 0;
	}
	public function wrap(\Tian\Ui\table $table) {
		$this->tb = $table;
		$this->table = new \Tian\Ui\Base\Node ( "table" );
		$this->caption = new \Tian\Ui\Base\Node ( "caption" );
		$this->thead = new \Tian\Ui\Base\Node ( "thead" );
		$tr = new \Tian\Ui\Base\Node ( "tr" );
		foreach ( $this->tb->alias () as $field => $alias ) {
			$th = new \Tian\Ui\Base\Node ( "th" );
			$th->appendNode ( new \Tian\Ui\Base\textnode ( $alias ) );
			$tr->appendNode ( $th );
		}
		$this->thead->appendNode ( $tr );
		
		$this->tbody = new \Tian\Ui\Base\Node ( "tbody" );
		$this->tfoot = new \Tian\Ui\Base\Node ( "tfoot" );
		
		foreach ( $this->tb->data as $key => $item ) {
			$tr = new \Tian\Ui\Base\Node ( "tr" );
			// 这一层循环代替TD层循环
			foreach ( $this->tb->alias () as $field => $alias ) {
				$content = $alias;
				$td = new \Tian\Ui\Base\Node ( "td" );
				if (array_key_exists ( $field, $item )) {
					$content = $item [$field];
				}
				
				if (array_key_exists ( $field, $this->tb->dataFilter ) && is_callable ( $this->tb->dataFilter [$field] )) {
					$content = call_user_func_array ( $this->tb->dataFilter [$field], array (
							$this,
							$tr,
							$td,
							$item,
							$content 
					) );
				}
				
				// domain
				if (array_key_exists ( $field, $this->tb->domain )) {
					$ret = array ();
					$set = explode ( ",", $content );
					foreach ( $set as $s ) {
						if (array_key_exists ( $s, $this->tb->domain [$field] )) {
							$ret [] = $this->tb->domain [$field] [$s];
						}
					}
					$content = join ( ",", $ret );
				}
				$td->appendNode ( new \Tian\Ui\Base\textnode ( $content ) );
				$tr->appendNode ( $td );
			}
			$this->tbody->appendNode ( $tr );
		}
		$this->table->appendNode ( $this->caption );
		$this->table->appendNode ( $this->thead );
		$this->table->appendNode ( $this->tbody );
		$this->table->appendNode ( $this->tfoot );
		$table->table = $this->table;
	}
}