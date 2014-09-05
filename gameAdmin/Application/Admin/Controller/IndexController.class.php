<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-7-22
// +----------------------------------------------------------------------
// | Describe: 
// +----------------------------------------------------------------------
namespace Admin\Controller;
class IndexController extends CommonController {
	public function index(){
		$this->assign('usercode',session('user_code'));
// 		pre(session('user_code'));
		$this->display();
	}
}