<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29
 * Time: 12:25
 */

namespace Tian\Ui\Base\Input;
use Tian\Ui\Base\Node;

class Checkbox extends Node {
    public $label = "";

    /**
     *
     * @param string $name
     * @param string $value
     */
    public function __construct($name = "", $value = NULL) {
        parent::__construct("input",array (
            "type" => "checkbox",
            "value" => $value
        ),true);
        if ($name) {
            $this->setName ( $name );
        }
    }
    /**
     *
     * @return $this
     */
    public function setChecked() {
        $this->setAttr ( "checked" );
        return $this;
    }
    /**
     *
     * @return $this
     */
    public function rmChecked() {
        $this->rmAttr ( "checked" );
        return $this;
    }

    /**
     * @return $this
     */
    public function setRequire() {
        $this->setAttr ( "require" );
        return $this;
    }

    /**
     * @return $this
     */
    public function rmRequire() {
        $this->rmAttr ( "require" );
        return $this;
    }
    /**
     *
     * @param string $label
     * @return $this
     */
    public function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function html() {
        return $this->wrapBegin . $this->getNodeHtml () . $this->label . $this->wrapEnd;
    }
}