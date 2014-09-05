<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆ All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-9-3
// +----------------------------------------------------------------------
// | Describe: 元宝消费比例查询控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;

class GoldConsumeController extends CommonController {
	public function _initialize() {
		// 判断用户权限
		if (! in_array ( '00200500', session ( 'user_code_arr' ) )) {
			$this->display ( 'Public/noauth' );
			exit ();
		}
	}
	
	//查询静态页面
	public function index() {
		$this->assign ( 'serverList', session ( 'server_list' ) ); // 服务器列表
		$this->assign ( 'today', date ( 'Y-m-d', strtotime("-1 day") ) ); // 默认显示今天的数据
		$this->display ();
	}
	
	//获取数据
	public function getChartData(){
		$serverId = I("serverId",1,'intval');//服务器
		$startdate = I('startdate');//查询开始时间
		$enddate = I('enddate');//查询结束时间
		
		$startdate = $startdate." 00:00:00";
		$enddate = $enddate." 23:59:59";
		
		if (!$serverId){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));exit;
		}
		
		$where = array();
		$where['date'] = array('between',array($startdate,$enddate));
		$data = D('GetObj')->getObj($serverId)->table("wing")->where($where)->select();
		
		$list = array(
				'shop'=>array('country'=>'商城消费','litres'=>0),
				'mystery_shop'=>array('country'=>'神秘商店','litres'=>0),
				'bargain'=>array('country'=>'聚划算','litres'=>0),
				'touch_of_gold'=>array('country'=>'摸金','litres'=>0),
				'bank'=>array('country'=>'诸葛钱庄','litres'=>0),
		);
		foreach ($data as $k=>$v){
			$list['shop']['litres'] += $v['shop'];
			$list['mystery_shop']['litres'] += $v['mystery_shop'];
			$list['bargain']['litres'] += $v['bargain'];
			$list['touch_of_gold']['litres'] += $v['touch_of_gold'];
			$list['bank']['litres'] += $v['bank'];
		}
		sort($list);
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$list));exit;
	}
}