<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月3日
 * @Desc:
 * 依赖:
 */

namespace Aw\Ui\Base;

abstract class Node
{
    const DEFAULT_WRAP = ':element';
    const DEFAULT_PLACEHOLDER = ':element';
    public $wrap = self::DEFAULT_WRAP;
    public $wrapPlaceHolder = self::DEFAULT_PLACEHOLDER;

    /**
     *
     * @var Element
     */
    protected $parent = NULL;

    /**
     * 获取NODE自身的HTML,不包含孩子结点内容
     * SELECT会返回<select name="sel"></select>
     */
    abstract public function getNodeHtml();

    /**
     *
     * @param Element $parent
     * @return $this
     */
    public function setParent(Element $parent)
    {
        $this->parent = $parent;
        return $this;
    }
//    /**
//     * <div class="test"> :hd </div>
//     * <div class="ui"><span></span></div>
//     *
//     * @param string $html
//     * @param string|callback $placeHolder
//     *        	如果是CALLBACK，参数为$placeHolder为ELEMENT的内容，$html为包裹HTML代码
//     * @param string $replacement
//     * @return string
//     */
//    public static function wrapHtml($html, $placeHolder, $replacement) {
//        if ($placeHolder === "") {
//            return preg_replace_callback ( '/<([a-zA-Z\d]+)(\s+[\w-]+\s*(?:=(?:"[^"]*?"|\'[^\']*?\'|[\w-]+?))?)*\s*>\s*<\/\1>/', function ($matches) use($replacement) {
//                return preg_replace ( '/>\s*</', '>' . $replacement . '<', $matches [0] );
//            }, $html );
//        } else if (is_string ( $placeHolder ) && strlen ( $placeHolder ) > 0) {
//            return strtr ( $html, array (
//                $placeHolder => $replacement
//            ) );
//        } else if (is_callable ( $placeHolder )) {
//            return call_user_func_array ( $placeHolder, array (
//                "placeHolder" => $replacement,
//                "html" => $html
//            ) );
//        }
//        return "";
//    }
    /**
     *
     * @param string $html
     * @param string $placeHolder
     *            默认值 ></ 找到第一个，然后分割成两个
     * @return \Aw\ui\base\node
     */
    public function setWrap($html, $placeHolder = self::DEFAULT_PLACEHOLDER)
    {
        $this->wrap = $html;
        $this->wrapPlaceHolder = $placeHolder;
        return $this;
    }

    /**
     * 可以设置wrap.它是对每个元素的包装，
     * 占位符默认为:element(可以设置wrapPlaceHolder来修改)
     *
     * @param node $node
     * @return string
     */
    public function dumpHtml($node = null)
    {
        if ($node == null) {
            $node = $this;
        }
        return strtr($this->wrap, array(
            $node->wrapPlaceHolder => $node->getNodeHtml()
        ));
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