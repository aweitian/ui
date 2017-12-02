<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30
 * Time: 17:10
 */

namespace Tian\Ui\Adapter\Mysql;


use Tian\Data\Component;
use Tian\Ui\Base\FormInput;
use Tian\Ui\Base\Input\Button;
use Tian\Ui\Base\Input\CheckboxGrp;
use Tian\Ui\Base\Input\Date;
use Tian\Ui\Base\Input\Datetime;
use Tian\Ui\Base\Input\file;
use Tian\Ui\Base\Input\Image;
use Tian\Ui\Base\Input\Password;
use Tian\Ui\Base\Input\RadioGrp;
use Tian\Ui\Base\Input\reset;
use Tian\Ui\Base\Input\Select;
use Tian\Ui\Base\Input\Submit;
use Tian\Ui\Base\Input\Text;
use Tian\Ui\Base\Input\Textarea;

class FieldUI
{
    /**
     *
     * @var Component
     */
    public $component;

    /**
     *
     * @var FormInput
     */
    public $element;
    /**
     * 数据格式为:
     * 字段名 => element类型名
     * element类型名(\tian\ui\base\input类型)
     *
     * @var array
     */
    public $uiTypeMap = array ();
    public function __construct(Component $component = NULL) {
        if (! is_null ( $component )) {
            $this->setComponent ( $component );
        }
    }
    public function setComponent(Component $component) {
        $this->component = $component;
        return $this;
    }

    /**
     * 对于类型为enum 或者 set时有用
     *
     * @param array $domain
     * @return $this
     */
    public function setDomain(array $domain) {
        $this->component->domain = $domain;
        return $this;
    }

    /**
     * 可用的类型有:
     * button checkboxGrp date datetime
     * file image image password
     * radioGrp reset select submit
     * text textarea
     *
     * @param array $map
     * @return $this
     */
    public function setUiTypeMap(array $map) {
        $this->uiTypeMap = $map;
        return $this;
    }
    /**
     * 可用的类型有:
     * button checkboxGrp date datetime
     * file image image password
     * radioGrp reset select submit
     * text textarea
     *
     * @param string $field
     * @param string $type
     * @return $this
     */
    public function addUiTypeMap($field, $type) {
        $this->uiTypeMap [$field] = $type;
        return $this;
    }
    /**
     *
     * @param string $field
     * @return $this
     */
    public function rmUiTypeMap($field) {
        if (isset ( $this->uiTypeMap [$field] ))
            unset ( $this->uiTypeMap [$field] );
        return $this;
    }

    /**
     * 调用match过后，成员element可用
     *
     * @return boolean
     */
    public function match() {
        $name = $this->component->name;
        if (! array_key_exists ( $name, $this->uiTypeMap ))
            return $this->defMatch ();
        $elementType = $this->uiTypeMap [$name];

        switch ($elementType) {
            case "button" :
                $this->element = new Button ( $this->component->default );
                return true;
            case "checkboxGrp" :
                $this->element = new CheckboxGrp ( $this->component->name, $this->component->domain, $this->component->default );
                return true;
            case "date" :
                $this->element = new Date ( $this->component->name, $this->component->default );
                return true;
            case "datetime" :
                $this->element = new Datetime ( $this->component->name, $this->component->default );
                return true;
            case "file" :
                $this->element = new File ( $this->component->name, $this->component->default );
                return true;
            case "image" :
                $this->element = new Image ( $this->component->default );
                return true;
            case "password" :
                $this->element = new Password ( $this->component->name, $this->component->default );
                return true;
            case "radioGrp" :
                $this->element = new RadioGrp ( $this->component->name, $this->component->domain, $this->component->default );
                return true;
            case "reset" :
                $this->element = new Reset ( $this->component->default );
                return true;
            case "select" :
                $this->element = new Select ( $this->component->name, $this->component->domain, $this->component->default );
                return true;
            case "submit" :
                $this->element = new Submit ( $this->component->default );
                return true;
            case "text" :
                $this->element = new Text ( $this->component->name, $this->component->default );
                return true;
            case "textarea" :
                $this->element = new Textarea ( $this->component->name, $this->component->default );
                return true;
        }
        return false;
    }
    /**
     *
     * @return boolean
     */
    private function defMatch() {
        switch ($this->component->dataType) {
            case "tinyint" :
            case "smallint" :
            case "int" :
            case "decimal" :
            case "mediumint" :
            case "float" :
            case "double" :
            case "tinyblob" :
            case "varchar" :
            case "char" :
            case "binary" :
            case "varbinary" :
            case "time" :
            case "year" :
                $this->element = new Text ( $this->component->name, $this->component->default );
                return true;
            case "tinytext" :
            case "blob" :
            case "mediumblob" :
            case "mediumtext" :
            case "longblob" :
            case "longtext" :
            case "text" :
                $this->element = new Textarea ( $this->component->name, $this->component->default );
                return true;
            case "datetime" :
            case "timestamp" :
                $this->element = new Datetime ( $this->component->name, $this->component->default );
                return true;
            case "date" :
                $this->element = new Date ( $this->component->name, $this->component->default );
                return true;
            case "enum" :
                $this->element = new Select ( $this->component->name, $this->component->domain, $this->component->default );
                return true;
            case "set" :
                $this->element = new CheckboxGrp ( $this->component->name, $this->component->domain, $this->component->default );
                return true;
        }
        return false;
    }
}