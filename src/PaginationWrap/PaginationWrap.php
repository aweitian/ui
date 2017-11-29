<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月12日
 * @Desc: 
 * 依赖:
 */
namespace Tian\Ui\paginationWrap;

use Tian\Ui\Pagination;

abstract class paginationWrap {
	abstract public function wrap(Pagination $pagination);
}