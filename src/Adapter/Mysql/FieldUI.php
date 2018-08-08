<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30
 * Time: 17:10
 */

namespace Aw\Ui\Adapter\Mysql;


use Aw\Data\Component;
use Aw\Ui\Base\Element;
use Aw\Ui\Base\Input\Button;
use Aw\Ui\Base\Input\CheckboxGrp;
use Aw\Ui\Base\Input\Date;
use Aw\Ui\Base\Input\Datetime;
use Aw\Ui\Base\Input\file;
use Aw\Ui\Base\Input\Image;
use Aw\Ui\Base\Input\Password;
use Aw\Ui\Base\Input\RadioGrp;
use Aw\Ui\Base\Input\reset;
use Aw\Ui\Base\Input\Select;
use Aw\Ui\Base\Input\Submit;
use Aw\Ui\Base\Input\Text;
use Aw\Ui\Base\Input\Textarea;

/**
 * Class FieldUI
 * @package Aw\Ui\Adapter\Mysql
 * 职责:
 * 根据Field匹配一个适合的Element
 */
class FieldUI
{
    public $error;
    /**
     *
     * @var Component
     */
    public $component;

    /**
     *
     * @var Element
     */
    public $element;
    /**
     * 数据格式为:
     * 字段名 => element类型名
     * element类型名(\Aw\ui\base\input类型)
     *
     * @var array
     */
    public $uiTypeMap = array();

    public function __construct(Component $component = NULL)
    {
        if (!is_null($component)) {
            $this->setComponent($component);
        }
    }

    public function setComponent(Component $component)
    {
        $this->component = $component;
        return $this;
    }

    /**
     * 对于类型为enum 或者 set时有用
     *
     * @param array $domain
     * @return $this
     */
    public function setDomain(array $domain)
    {
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
    public function setUiTypeMap(array $map)
    {
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
    public function addUiTypeMap($field, $type)
    {
        $this->uiTypeMap [$field] = $type;
        return $this;
    }

    /**
     *
     * @param string $field
     * @return $this
     */
    public function rmUiTypeMap($field)
    {
        if (isset ($this->uiTypeMap [$field]))
            unset ($this->uiTypeMap [$field]);
        return $this;
    }

    /**
     * 调用match过后，成员element可用
     *
     * @return bool
     */
    public function match()
    {
        $name = $this->component->name;
        if (!array_key_exists($name, $this->uiTypeMap)) {
            if ($this->defMatch()) {
                return true;
            }
            $this->error = "$name matched fail.";
            return false;
        }
        $elementType = $this->uiTypeMap [$name];

        switch ($elementType) {
            case "button" :
                $this->element = new Button ($this->component->default);
                break;
            case "checkboxGrp" :
                $this->element = new CheckboxGrp ($this->component->name, $this->component->domain, $this->component->default);
                break;
            case "date" :
                $this->element = new Date ($this->component->name, $this->component->default);
                break;
            case "datetime" :
                $this->element = new Datetime ($this->component->name, $this->component->default);
                break;
            case "file" :
                $this->element = new File ($this->component->name, $this->component->default);
                break;
            case "image" :
                $this->element = new Image ($this->component->default);
                break;
            case "password" :
                $this->element = new Password ($this->component->name, $this->component->default);
                break;
            case "radioGrp" :
                $this->element = new RadioGrp ($this->component->name, $this->component->domain, $this->component->default);
                break;
            case "reset" :
                $this->element = new Reset ($this->component->default);
                break;
            case "select" :
                $this->element = new Select ($this->component->name, $this->component->domain, $this->component->default);
                break;
            case "submit" :
                $this->element = new Submit ($this->component->default);
                break;
            case "text" :
                $this->element = new Text ($this->component->name, $this->component->default);
                break;
            case "textarea" :
                $this->element = new Textarea ($this->component->name, $this->component->default);
                break;
            default:
                $this->error = "$elementType not found.";
                $this->element = null;
                return false;
        }
        return true;
    }

    /**
     *
     * @return boolean
     */
    private function defMatch()
    {
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
                $this->element = new Text ($this->component->name, $this->component->default);
                return true;
            case "tinytext" :
            case "blob" :
            case "mediumblob" :
            case "mediumtext" :
            case "longblob" :
            case "longtext" :
            case "text" :
                $this->element = new Textarea ($this->component->name, $this->component->default);
                return true;
            case "datetime" :
            case "timestamp" :
                $this->element = new Datetime ($this->component->name, $this->component->default);
                return true;
            case "date" :
                $this->element = new Date ($this->component->name, $this->component->default);
                return true;
            case "enum" :
                $this->element = new Select ($this->component->name, $this->component->domain, $this->component->default);
                return true;
            case "set" :
                $this->element = new CheckboxGrp ($this->component->name, $this->component->domain, $this->component->default);
                return true;
        }
        return false;
    }
}