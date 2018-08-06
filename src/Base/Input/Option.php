<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29
 * Time: 12:53
 */

namespace Aw\Ui\Base\Input;


use Aw\Ui\Base\Node;
use Aw\Ui\Base\TextNode;

class Option extends Node {
    /**
     *
     * @var TextNode;
     */
    public $textNode;

    /**
     *
     * @param string $text
     * @param string $value
     */
    public function __construct($text = "", $value = NULL) {
        parent::__construct("input",array (
            "value" => "$value",
        ),true);
        $this->textNode = new TextNode ( $text );
        $this->appendNode ( $this->textNode );
    }

    /**
     * @return $this
     */
    public function setSelected() {
        $this->setAttr ( "selected" );
        return $this;
    }

    /**
     * @return $this
     */
    public function rmSelected() {
        $this->rmAttr ( "selected" );
        return $this;
    }

    /**
     *
     * @param string $text
     * @return Option
     */
    public function setText($text) {
        $this->textNode->setText ( $text );
        return $this;
    }
}