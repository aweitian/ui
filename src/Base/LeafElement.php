<?php
/**
 * 不含有子结点的元素
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29
 * Time: 16:12
 */

namespace Aw\Ui\Base;


class LeafElement extends TagNode
{
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
     *
     * @param Element $parent
     * @return $this
     */
    public function setParent(Element $parent)
    {
        return $this;
    }
}