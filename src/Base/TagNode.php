<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29
 * Time: 16:36
 */

namespace Aw\Ui\Base;


abstract class TagNode extends Node
{
    public $tagName;
    protected $attributes;
    public $glue;
    public $wrap = ":element";
    public $wrapPlaceHolder = ":element";

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
                $attr .= " " . $ak . "=\"" . htmlspecialchars ( $av, ENT_QUOTES ) . "\"";
            }
        }
        return $attr;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Aw\Ui\Base\Node::getNodeHtml()
     */
    public function getNodeHtml() {
        if (! $this->tagName)
            return "";

        $html = "<" . $this->tagName . ":attr>";

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
     * @param $ph
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
     * @param node $node
     * @return string
     */
    public function dumpHtml($node = null) {
        if ($node == null) {
            $node = $this;
        }
        return $node->html ();
    }
}