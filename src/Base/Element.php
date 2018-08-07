<?php

/**
 * 含有子结点的元素
 * @Author: awei.tian
 * @Date: 2016年8月3日
 * @Desc:
 * 依赖:
 */

namespace Aw\Ui\Base;

class Element extends TagNode implements \IteratorAggregate
{
    protected $childNodes = array();

    /**
     * ATTR数据值为null表示只有属性名,如:readonly
     *
     * @param string $tag
     * @param array $attrs
     */
    public function __construct($tag, $attrs = array())
    {
        $this->tagName = $tag;
        $this->attributes = $attrs;
    }

    /**
     * (non-PHPdoc)
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new \ArrayIterator ($this->childNodes);
    }

    /**
     *
     * @param $pos
     * @return null|TagNode
     */
    public function getChildTagNode($pos)
    {
        if (isset ($this->childNodes [$pos]) && $this->childNodes [$pos] instanceof TagNode) {
            return $this->childNodes [$pos];
        }
        return null;
    }

    /**
     *
     * @return TextNode | null
     */
    public function getTextNode()
    {
        $child = $this->getChild(0);
        return $child instanceof Textnode ? $child : null;
    }

    /**
     *
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->clearNode();
        $this->appendNode(new Textnode ($text));
        $this->getChild(0)->setParent($this);
        return $this;
    }

    /**
     *
     * @param string $name
     * @param string $tag_name
     * @param bool $recur
     * @return TagNode
     */
    public function find($name = "*", $tag_name = "*", $recur = false)
    {
        foreach ($this->childNodes as $node) {
            $mask = 0;//name | tag
            if ($node instanceof TagNode) {
                if ($name != "*") {
                    if ($node->hasAttr("name") && $node->getAttr("name") == $name) {
                        $mask = 2;
                    }
                } else {
                    $mask = 2;
                }
                if ($tag_name != "*") {
                    if ($node->tagName == $tag_name) {
                        $mask |= 1;
                    }
                } else {
                    $mask |= 1;
                }
                if ($mask == 3)
                    return $node;
            }
            if ($recur) {
                if ($node instanceof Element) {
                    if (($ret = $node->find($name, $tag_name, $recur)) != null) {
                        return $ret;
                    }
                }
            }
        }
        return null;
    }

    /**
     *
     * @param int $pos 可正可负
     * @param Node | string $node
     * @return $this
     */
    public function insertNode($pos, $node)
    {
        if ($pos === 0) {
            if ($node instanceof Node) {
                $node->setParent($this);
            }
            array_unshift($this->childNodes, $node);
        } else if ($pos === count($this->childNodes)) {
            if ($node instanceof Node) {
                $node->setParent($this);
            }
            $this->childNodes [] = $node;
        } else {
            if ($node instanceof Node) {
                $node->setParent($this);
            }
            $arr = array_splice($this->childNodes, 0, $pos);
            $arr [] = $node;
            $this->childNodes = array_merge($arr, $this->childNodes);
        }
        return $this;
    }

    /**
     *
     * @param Node $node
     * @return $this
     */
    public function appendNode(Node $node)
    {
        if ($node instanceof Node) {
            $node->setParent($this);
        }
        $this->childNodes [] = $node;
        return $this;
    }

    /**
     *
     * @param Node $node
     * @return $this
     */
    public function prependNode(Node $node)
    {
        if ($node instanceof Node) {
            $node->setParent($this);
        }
        array_unshift($this->childNodes, $node);
        return $this;
    }

    /**
     *
     * @param int $index
     * @return Node
     */
    public function getChild($index = 0)
    {
        if ($index < count($this->childNodes)) {
            return $this->childNodes [$index];
        }
        return null;
    }

    /**
     *
     * @return int
     */
    public function getChildCnt()
    {
        return count($this->childNodes);
    }

    /**
     * 支持负数
     *
     * @param int $pos
     * @return $this
     */
    public function removeNode($pos)
    {
        $len = count($this->childNodes);
        if ($pos < 0) {
            $pos = $pos + $len;
        }

        if (isset ($this->childNodes [$pos])) {
            unset ($this->childNodes [$pos]);
        }
        return $this;
    }

    /**
     *
     * return $this
     */
    public function clearNode()
    {
        $this->childNodes = array();
        return $this;
    }

    /**
     *
     * @param string $attr
     * @return boolean
     */
    public function hasAttr($attr)
    {
        return array_key_exists($attr, $this->attributes);
    }

    /**
     *
     * @param string $cls
     * @return $this
     */
    public function addClass($cls)
    {
        if ($this->hasAttr("class")) {
            $c = $this->attributes ["class"];
            $cArr = explode(" ", $c);
            $cArr [] = $cls;
            $this->setAttr("class", join(" ", $cArr));
        } else {
            $this->addAttr("class", $cls);
        }
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->getAttr("class");
    }

    /**
     *
     * @param string $cls
     * @return $this
     */
    public function rmClass($cls)
    {
        if ($this->hasAttr("class")) {
            $c = $this->attributes ["class"];
            $cArr = explode(" ", $c);
            $newCls = array();
            foreach ($cArr as $ci) {
                if ($ci == $cls) {
                    continue;
                }
                $newCls [] = $ci;
            }
            $this->setAttr("class", join(" ", $newCls));
        }
        return $this;
    }

    /**
     * glue:胶；胶水；胶粘物
     * @param string $glue
     * @return $this
     */
    public function setGlue($glue = "\r\n")
    {
        $this->glue = $glue;
        return $this;
    }

    /**
     * 可以设置wrap.它是对每个元素的包装，
     * 占位符默认为:element(可以设置wrapPlaceHolder来修改)
     *
     * @param Node $node
     * @return string
     */
    public function dumpHtml($node = null)
    {
        if ($node == null) {
            $node = $this;
        }
        $html = "";
        if ($node instanceof TagNode) {
            if ($node instanceof LeafElement) {
                $html .= $node->dumpHtml();
            } else if ($node instanceof Element) {
                $i = 0;
                $wrap_arr = explode($node->wrapPlaceHolder, $node->wrap);
                if (count($wrap_arr) == 2) {
                    $wrap_begin = $wrap_arr[0];
                    $wrap_end = $wrap_arr[1];
                } else {
                    $wrap_begin = '';
                    $wrap_end = '';
                }
                $html .= $wrap_begin;
                if ($node->tagName)
                    $html .= "<" . $node->tagName . $node->getAttrHtml() . ">";
                /**
                 * @var Node $child
                 */
                foreach ($node->childNodes as $child) {
                    if ($i)
                        $html .= $this->glue;
                    $html .= $child->dumpHtml();
                    $i++;
                }
                if ($node->tagName)
                    $html .= "</" . $node->tagName . ">";
                $html .= $wrap_end;
            }
        } else if ($node instanceof TextNode) {
            $html .= $node->dumpHtml();
        } else if (is_string($node)) {
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
     * @param bool $recur 是否递归
     * @return $this
     */
    public function map($callback, $recur = true)
    {
        if (is_callable($callback)) {
            $i = 0;
            foreach ($this->childNodes as $item) {
                call_user_func_array($callback, array(
                    "index" => $i,
                    "item" => $item
                ));
                if ($recur) {
                    if ($item instanceof Element) {
                        $item->map($callback);
                    }
                }
                $i++;
            }
        }
        return $this;
    }
}

/**
 *
 *
 *
 * XML_ELEMENT_NODE (integer)    1    Node is a DOMElement
 * XML_ATTRIBUTE_NODE (integer)    2    Node is a DOMAttr
 * XML_TEXT_NODE (integer)    3    Node is a DOMText
 * XML_CDATA_SECTION_NODE (integer)    4    Node is a DOMCharacterData
 * XML_ENTITY_REF_NODE (integer)    5    Node is a DOMEntityReference
 * XML_ENTITY_NODE (integer)    6    Node is a DOMEntity
 * XML_PI_NODE (integer)    7    Node is a DOMProcessingInstruction
 * XML_COMMENT_NODE (integer)    8    Node is a DOMComment
 * XML_DOCUMENT_NODE (integer)    9    Node is a DOMDocument
 * XML_DOCUMENT_TYPE_NODE (integer)    10    Node is a DOMDocumentType
 * XML_DOCUMENT_FRAG_NODE (integer)    11    Node is a DOMDocumentFragment
 * XML_NOTATION_NODE (integer)    12    Node is a DOMNotation
 *
 *
 */