<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月3日
 * @Desc: 
 * 依赖:
 */
namespace Aw\Ui\Base;

use Aw\Data\Filter;

class Element extends TagNode implements \IteratorAggregate {
	protected $childNodes = array ();

	/**
	 * ATTR数据值为null表示只有属性名,如:readonly
	 *
	 * @param string $tag        	
	 * @param array $attrs        	
	 */
	public function __construct($tag, $attrs = array()) {
		$this->tagName = $tag;
		$this->attributes = $attrs;
	}
	/**
	 * (non-PHPdoc)
	 *
	 * @see IteratorAggregate::getIterator()
	 */
	public function getIterator() {
		return new \ArrayIterator ( $this->childNodes );
	}

    /**
     *
     * @param $pos
     * @return null|TagNode
     */
	public function getChildTagNode($pos) {
		if (isset ( $this->childNodes [$pos] ) && $this->childNodes [$pos] instanceof TagNode) {
			return $this->childNodes [$pos];
		}
		return null;
	}
	
	/**
	 *
	 * @return Textnode | null
	 */
	public function getTextNode() {
		$child = $this->getChild ( 0 );
		return $child instanceof Textnode ? $child : null;
	}

    /**
     *
     * @param string $text
     * @return $this
     */
	public function setText($text) {
		$this->clearNode ();
		$this->appendNode ( new Textnode ( $text ) );
		$this->getChild ( 0 )->setParent ( $this );
		return $this;
	}

    /**
     *
     * @param $tagname
     * @return mixed|null
     */
	public function find($tagname) {
		foreach ( $this->childNodes as $node ) {
			if ($node instanceof TagNode && $node->tagName == $tagname) {
				return $node;
			}
		}
		return null;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getAttrHtml() {
		$attr = "";
		if (! is_array ( $this->attributes ))
			return $attr;
		foreach ( $this->attributes as $ak => $av ) {
			if (is_null ( $av )) {
				$attr .= " " . $ak;
			} else {
				$attr .= " " . $ak . "=\"" . Filter::filterOut ( $av ) . "\"";
			}
		}
		return $attr;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \Aw\ui\base\node::getNodeHtml()
	 */
	public function getNodeHtml() {
		if (! $this->tagName)
			return "";

		$html = "<" . $this->tagName . ":attr></" . $this->tagName . ">";

		return strtr ( $html, array (
				":attr" => $this->getAttrHtml () 
		) );
	}

    /**
     *
     * @param string $ak
     * @param string $av
     * @return $this
     */
	public function setAttr($ak, $av = NULL) {
		$this->attributes [$ak] = $av;
		return $this;
	}
	/**
	 *
	 * @param string $ak        	
	 * @param string $def        	
	 * @return string
	 */
	public function getAttr($ak, $def = "") {
		if ($this->hasAttr ( $ak )) {
			return $this->attributes [$ak];
		}
		return $def;
	}
	
	/**
	 *
	 * @param string $id        	
	 * @return $this
	 */
	public function setId($id) {
		return $this->setAttr ( "id", $id );
	}

    /**
     * @return string
     */
	public function getId() {
		return $this->getAttr ( "id" );
	}
	
	/**
	 *
	 * @param string $name        	
	 * @return $this
	 */
	public function setName($name) {
		return $this->setAttr ( "name", $name );
	}
	
	/**
	 *
	 * @return string
	 */
	public function getName() {
		return $this->getAttr ( "name" );
	}
	
	/**
	 *
	 * @param string $ak        	
	 * @param string $av        	
	 * @return $this
	 */
	public function addAttr($ak, $av = NULL) {
		return $this->setAttr ( $ak, $av );
	}
	
	/**
	 *
	 * @param string $ak        	
	 * @return $this
	 */
	public function rmAttr($ak) {
		if (array_key_exists ( $ak, $this->attributes )) {
			unset ( $this->attributes );
		}
		return $this;
	}
	
	/**
	 *
	 * @param int $pos 可正可负
	 * @param Node | string $node
	 * @return $this
	 */
	public function insertNode($pos, $node) {
		if ($pos === 0) {
			if ($node instanceof Node) {
				$node->setParent ( $this );
			}
			array_unshift ( $this->childNodes, $node );
		} else if ($pos === count ( $this->childNodes )) {
			if ($node instanceof Node) {
				$node->setParent ( $this );
			}
			$this->childNodes [] = $node;
		} else {
			if ($node instanceof Node) {
				$node->setParent ( $this );
			}
			$arr = array_splice ( $this->childNodes, 0, $pos );
			$arr [] = $node;
			$this->childNodes = array_merge ( $arr, $this->childNodes );
		}
		return $this;
	}
	
	/**
	 *
	 * @param Node | string $node
	 * @return $this
	 */
	public function appendNode(Node $node) {
		if ($node instanceof Node) {
			$node->setParent ( $this );
		}
		$this->childNodes [] = $node;
		return $this;
	}
	
	/**
	 *
	 * @param Node $node
	 * @return $this
	 */
	public function prependNode(Node $node) {
		if ($node instanceof Node) {
			$node->setParent ( $this );
		}
		array_unshift ( $this->childNodes, $node );
		return $this;
	}
	/**
	 *
	 * @param int $index
	 * @return Node
	 */
	public function getChild($index = 0) {
		if ($index < count ( $this->childNodes )) {
			return $this->childNodes [$index];
		}
		return null;
	}
	/**
	 *
	 * @return int
	 */
	public function getChildCnt() {
		return count ( $this->childNodes );
	}
	
	/**
	 * 支持负数
	 *
	 * @param int $pos        	
	 * @return $this
	 */
	public function removeNode($pos) {
		if ($pos < count ( $this->childNodes )) {
			$pos = $pos + count ( $this->childNodes );
		}
		if (isset ( $this->childNodes [$pos] )) {
			unset ( $this->childNodes [$pos] );
		}
		return $this;
	}
	/**
	 *
	 * return $this
	 */
	public function clearNode() {
		$this->childNodes = array ();
		return $this;
	}
	/**
	 *
	 * @param string $attr        	
	 * @return boolean
	 */
	public function hasAttr($attr) {
		return array_key_exists ( $attr, $this->attributes );
	}
	/**
	 *
	 * @param string $cls        	
	 * @return $this
	 */
	public function addClass($cls) {
		if ($this->hasAttr ( "class" )) {
			$c = $this->attributes ["class"];
			$cArr = explode ( " ", $c );
			$cArr [] = $cls;
			$this->setAttr ( "class", join ( " ", $cArr ) );
		} else {
			$this->addAttr ( "class", $cls );
		}
		return $this;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getClassName() {
		return $this->getAttr ( "class" );
	}
	/**
	 *
	 * @param string $cls        	
	 * @return $this
	 */
	public function rmClass($cls) {
		if ($this->hasAttr ( "class" )) {
			$c = $this->attributes ["class"];
			$cArr = explode ( " ", $c );
			$newCls = array ();
			foreach ( $cArr as $ci ) {
				if ($ci == $cls) {
					continue;
				}
				$newCls [] = $ci;
			}
			$this->setAttr ( "class", join ( " ", $newCls ) );
		}
		return $this;
	}

    /**
     * glue:胶；胶水；胶粘物
     * @param string $glue
     * @return $this
     */
	public function setGlue($glue = "\r\n") {
		$this->glue = $glue;
		return $this;
	}
	/**
	 *
	 * @param string $wrap
	 *        	占位符: :element将会被元素的HTML替代
	 * @return $this
	 */
	public function setWrap($wrap) {
		$this->wrap = $wrap;
		return $this;
	}
	/**
	 *
	 * @param string $ph 占位符: :element将会被元素的HTML替代
	 * @return $this
	 */
	public function setWrapPlaceHolder($ph) {
		$this->wrapPlaceHolder = $ph;
		return $this;
	}
	/**
	 * 可以设置wrap.它是对每个元素的包装，
	 * 占位符默认为:element(可以设置wrapPlaceHolder来修改)
	 *
	 * @param Node $node
	 * @return string
	 */
	public function dumpHtml($node = null) {
		if ($node == null) {
			$node = $this;
		}
		$html = "";
		if ($node instanceof TagNode) {
			if ($node instanceof LeafElement) {
				$html .= $node->html ();
			} else if ($node instanceof Element){
				$i = 0;
				$html .= $this->wrapBegin;
				if ($node->tagName)
					$html .= "<" . $node->tagName . $node->getAttrHtml () . ">";
				foreach ( $node->childNodes as $child ) {
					if ($i)
						$html .= $this->glue;
					$html .= strtr ( $this->wrap, array (
							$this->wrapPlaceHolder => $node->dumpHtml ( $child ) 
					) );
					$i ++;
				}
				if ($node->tagName)
					$html .= "</" . $node->tagName . ">";
				$html .= $this->wrapEnd;
			}
		} else if ($node instanceof TextNode) {
			$html .= $node->html ();
		} else if (is_string ( $node )) {
			$html .= $node;
		}
		
		return $html;
	}
	/**
	 * 传递参数两个参数
	 * index 孩子中顺序
	 * item,类型为\Aw\ui\base\node
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

/**
 * 
 * 
 * 
XML_ELEMENT_NODE (integer)	1	Node is a DOMElement
XML_ATTRIBUTE_NODE (integer)	2	Node is a DOMAttr
XML_TEXT_NODE (integer)	3	Node is a DOMText
XML_CDATA_SECTION_NODE (integer)	4	Node is a DOMCharacterData
XML_ENTITY_REF_NODE (integer)	5	Node is a DOMEntityReference
XML_ENTITY_NODE (integer)	6	Node is a DOMEntity
XML_PI_NODE (integer)	7	Node is a DOMProcessingInstruction
XML_COMMENT_NODE (integer)	8	Node is a DOMComment
XML_DOCUMENT_NODE (integer)	9	Node is a DOMDocument
XML_DOCUMENT_TYPE_NODE (integer)	10	Node is a DOMDocumentType
XML_DOCUMENT_FRAG_NODE (integer)	11	Node is a DOMDocumentFragment
XML_NOTATION_NODE (integer)	12	Node is a DOMNotation
 * 
 * 
 */