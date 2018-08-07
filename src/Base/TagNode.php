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


    /**
     *
     * @return string
     */
    public function getAttrHtml()
    {
        $attr = "";
        if (!is_array($this->attributes))
            return $attr;
        foreach ($this->attributes as $ak => $av) {
            if (is_null($av)) {
                $attr .= " " . $ak;
            } else {
                $attr .= " " . $ak . "=\"" . htmlspecialchars($av, ENT_QUOTES) . "\"";
            }
        }
        return $attr;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Aw\Ui\Base\Node::getNodeHtml()
     */
    public function getNodeHtml()
    {
        if (!$this->tagName)
            return "";

        $html = "<" . $this->tagName . ":attr>";

        return strtr($html, array(
            ":attr" => $this->getAttrHtml()
        ));
    }

    /**
     *
     * @param string $ak
     * @param string $av
     * @return $this
     */
    public function setAttr($ak, $av = NULL)
    {
        $this->attributes [$ak] = $av;
        return $this;
    }

    /**
     *
     * @param string $ak
     * @param string $def
     * @return string
     */
    public function getAttr($ak, $def = "")
    {
        if ($this->hasAttr($ak)) {
            return $this->attributes [$ak];
        }
        return $def;
    }

    /**
     *
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setAttr("id", $id);
    }

    /**
     *
     * @param string | array $cls
     * @return $this
     */
    public function setClass($cls)
    {
        if (is_array($cls))
            $cls = implode(" ", $cls);
        return $this->setAttr("class", $cls);
    }

    /**
     *
     * @param string | array $cls
     * @return $this
     */
    public function setStyle($cls)
    {
        if (is_array($cls))
            $cls = implode(";", $cls);
        return $this->setAttr("style", $cls);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getAttr("id");
    }

    /**
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->setAttr("name", $name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getAttr("name");
    }

    /**
     *
     * @param string $ak
     * @param string $av
     * @return $this
     */
    public function addAttr($ak, $av = NULL)
    {
        return $this->setAttr($ak, $av);
    }

    /**
     *
     * @param string $ak
     * @return $this
     */
    public function rmAttr($ak)
    {
        if (array_key_exists($ak, $this->attributes)) {
            unset ($this->attributes);
        }
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

    protected function addSetAttr($attr, $value, $separator = " ")
    {
        if ($this->hasAttr($attr)) {
            $c = $this->attributes [$attr];
            $cArr = explode($separator, $c);
            if (!in_array($value, $cArr)) {
                $cArr [] = $value;
            }
            $this->setAttr($attr, join(" ", $cArr));
        } else {
            $this->addAttr($attr, $value);
        }
        return $this;
    }

    protected function rmSetAttr($attr, $value, $separator = " ")
    {
        if ($this->hasAttr($attr)) {
            $c = $this->attributes [$attr];
            $cArr = explode($separator, $c);
            $newCls = array();
            foreach ($cArr as $ci) {
                if ($ci == $value) {
                    continue;
                }
                $newCls [] = $ci;
            }
            $this->setAttr($attr, join($separator, $newCls));
        }
        return $this;
    }

    /**
     *
     * @param string $cls
     * @return $this
     */
    public function addClass($cls)
    {
        return $this->addSetAttr("class", $cls);
    }

    public function addStyle($style)
    {
        return $this->addSetAttr("style", $style, ";");
    }

    /**
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
        return $this->rmSetAttr("class", $cls, " ");
    }

    /**
     *
     * @param string $style
     * @return $this
     */
    public function rmStyle($style)
    {
        return $this->rmSetAttr("style", $style, ";");
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
     *
     * @param $ph
     * @return $this
     */
    public function setWrapPlaceHolder($ph)
    {
        $this->wrapPlaceHolder = $ph;
        return $this;
    }


}