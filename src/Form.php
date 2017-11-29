<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月5日
 * @Desc: 
 * 		tuple是component的容器
 * 		\TIAN\DATA\FORM类为每个component设置合适的ELEMENT
 * 
 * 		数据和包装器
 * ===========================================================
 * 		alias
 * 			formname => alias
 * 		defaultValue
 * 			formname => value
 * 		dataFilter
 * 			formname => filter(callback)
 * 		uiType
 * 			formname => type
 * 		domain
 * 			formname => domain
 * 		nameMap
 * 			componentname => formname
 * ============================================================
 * 		
 * FORM类需要TUPLE类来设置数据
 * WRAP类型来生成UI
 * TUPLE类由COMPONENT类型组成，实现了COMPONENT类的有\TIAN\MYSQL\FIELD
 * 		FIELD类有NAME,DATATYPE属性，其中DATATYPE属性为MYSQL列属性
 * 		NAME有 【列名】 和【表名.列名】 两种(有时需要多表同时操作)
 * \TIAN\MYSQL\FIELDUI类型把\TIAN\MYSQL\FIELD变成\Tian\Ui\ELEMENT
 * TB2TUPLE是MYSQL表名到TUPLE的转换类
 * 		MYSQL表名到FIELD	
 * 依赖:
 */
namespace Tian\Ui;
use Tian\Data\Tuple;
use Tian\Ui\Base\Form as BaseForm;
class Form {
	/**
	 *
	 * @var Tuple
	 */
	public $tuple;
	/**
	 *
	 * @var BaseForm
	 */
	protected $form;
	protected $children = array ();
	public $default = array ();
	public $domain = array ();
	public $uiType = array ();
	/**
	 * component的NAME到FORM的NAME的映射
	 *
	 * @var array
	 */
	public $nameMap = array ();
	/**
	 * name => alias
	 * 表单字段的先后顺序为此为准
	 *
	 * @var array
	 */
	protected $alias = array ();
	public $dataFilter = array ();
	public function __construct() {
		$this->form = new BaseForm();
	}
	/**
	 *
	 * @param array $filter        	
	 * @return $this
	 */
	public function setDataFilter(array $filter) {
		$this->dataFilter = $filter;
		return $this;
	}
	
	/**
	 *
	 * @param array $def        	
	 * @return $this
	 */
	public function setDefaultValue(array $def) {
		$this->default = $def;
		return $this;
	}
	/**
	 *
	 * @param array $domain        	
	 * @return $this
	 */
	public function setDomain(array $domain) {
		$this->domain = $domain;
		return $this;
	}
	/**
	 *
	 * @param array $map        	
	 * @return $this
	 */
	public function setNameMap(array $map) {
		$this->nameMap = $map;
		return $this;
	}
	
	/**
	 *
	 * @param Tuple $tuple        	
	 * @return $this
	 */
	public function setTuple(Tuple $tuple) {
		$this->tuple = $tuple;
		return $this;
	}
	/**
	 *
	 * @param string $formName        	
	 * @param string $alias        	
	 * @return $this
	 */
	public function setAlias($formName, $alias) {
		$this->alias [$formName] = $alias;
		return $this;
	}
	/**
	 *
	 * @param string $formName        	
	 * @return $this
	 */
	public function rmAlias($formName) {
		if (isset ( $this->alias [$formName] )) {
			unset ( $this->alias [$formName] );
		}
		return $this;
	}
	
	/**
	 *
	 * @param array $alias        	
	 * @return $this
	 */
	public function mergeAlias(array $alias) {
		$this->alias = array_merge ( $this->alias, $alias );
		return $this;
	}
	/**
	 *
	 * @param string $formName        	
	 * @param string $def        	
	 * @return string
	 */
	public function getAlias($formName, $def = "") {
		return array_key_exists ( $formName, $this->alias ) ? $this->alias [$formName] : $def;
	}
	
	/**
	 *
	 * @return array:
	 */
	public function alias() {
		return $this->alias;
	}

    /**
     * 可用的类型有:
     * button checkboxGrp date datetime
     * file image image password
     * radioGrp reset select submit
     * text textarea
     *
     * @param array $type
     * @return $this
     */
	public function setUiType(array $type) {
		$this->uiType = $type;
		return $this;
	}

    /**
     *
     * @param $formName
     * @return bool
     */
	public function hasElem($formName) {
		return array_key_exists ( $formName, $this->children );
	}

    /**
     *
     * @param $formName
     * @param Base\Node $node
     * @return $this
     */
	public function appendNode($formName, Base\Node $node) {
		$this->children [$formName] = $this->form->getChildCnt ();
		$this->form->appendNode ( $node );
		return $this;
	}
	/**
	 *
	 * @return BaseForm
	 */
	public function getFormElement() {
		return $this->form;
	}

    /**
     *
     * @param $formName
     * @param int $pos
     * @param Base\Node $node
     */
	public function insertNode($formName, $pos, Base\Node $node) {
		$this->children [$formName] = $this->form->getChildCnt ();
		$this->form->insertNode ( $pos, $node );
	}

    /**
     *
     * @param $formName
     * @param Base\Node $node
     */
	public function prependNode($formName, Base\Node $node) {
		$this->children [$formName] = $this->form->getChildCnt ();
		$this->form->prependNode ( $node );
	}

    /**
     *
     * @param $formName
     * @return NULL|Base\Node
     */
	public function getNode($formName) {
		if (! array_key_exists ( $formName, $this->children ))
			return null;
		return $this->form->getChild ( $this->children [$formName] );
	}

    /**
     *
     * @param $formName
     * @return $this
     */
	public function removeNode($formName) {
		if (! array_key_exists ( $formName, $this->children ))
			return $this;
		$this->form->removeNode ( $this->children [$formName] );
		return $this;
	}
	/**
	 *
	 * @return $this
	 */
	public function initFormElement() {
		$ui = new \tian\mysql\fieldUI ();
		if (is_array ( $this->uiType )) {
			$ui->setUiTypeMap ( $this->uiType );
		}
		if (is_null ( $this->tuple ))
			return $this;
			// var_dump($this->tuple);
		foreach ( $this->tuple as $component ) {
			$formName = array_key_exists ( $component->name, $this->nameMap ) ? $this->nameMap [$component->name] : $component->name;
			if (array_key_exists ( $formName, $this->default )) {
				$component->default = $this->default [$formName];
			}
			if (array_key_exists ( $formName, $this->dataFilter )) {
				if (is_callable ( $this->dataFilter [$formName] )) {
					$component->default = call_user_func_array ( $this->dataFilter [$formName], array (
							$this,
							$this->tuple,
							$component->default 
					) );
				}
			}
			if (array_key_exists ( $formName, $this->domain )) {
				$component->domain = $this->domain [$formName];
			}
			
			if ($ui->setComponent ( $component )->match ()) {
				$this->appendNode ( $formName, $ui->element );
				if (array_key_exists ( $formName, $this->alias )) {
					$component->alias = $this->alias [$formName];
				}
				$this->setAlias ( $formName, $component->alias );
				
				$ui->element->alias = $component->alias;
				$ui->element->setName ( $formName );
			}
			// else{
			// var_dump($t)
			// ;exit;
			// }
		}
		return $this;
	}
	/**
	 *
	 * @param \Tian\Ui\FormWrap\formWrap $wrap
	 * @return $this
	 */
	public function wrapForm(\Tian\Ui\FormWrap\formWrap $wrap) {
		$wrap->wrap ( $this );
		return $this;
	}
}