<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆ All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-9-4
// +----------------------------------------------------------------------
// | Describe: 货币收支概况查询控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;

class CurrencyController extends CommonController {
	public function _initialize() {
		// 判断用户权限
		if (! in_array ( '00200300', session ( 'user_code_arr' ) )) {
			$this->display ( 'Public/noauth' );
			exit ();
		}
	}
	
	//查询静态页面
	public function index() {
		$this->assign ( 'serverList', session ( 'server_list' ) ); // 服务器列表
		$this->assign ( 'today', date ( 'Y-m-d', NOW_TIME ) ); // 默认显示今天的数据
		$this->display ();
	}
	
	//获取数据
	public function getChartData(){
		$serverId = I("serverId",1,'intval');//服务器
		$startdate = I('startdate');//查询开始时间
		$enddate = I('enddate');//查询结束时间
		
		if (!$serverId){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));exit;
		}
		
		if (empty($startdate)){
			$tem =  D('GetObj')->getObj($serverId)->table("payments")->order('p_id asc')->find();
			$startdate = $tem['p_date'];
			unset($tem);
		}
		$enddate = date('Y-m-d',strtotime($enddate)+24*3600);//用于查询当天
		
		//获取历史数据
		$where = array();
		$where['p_date'] = array('between',array($startdate,$enddate));
		$data = D('GetObj')->getObj($serverId)->table("payments")->where($where)->select();
		
		$sumList = D('GetObj')->getObj($serverId)->table("payments")
		-> field('sum(p_tong) as sum_tong,sum(p_yuan) as sum_yuan,sum(p_yin) as sum_btong,sum(p_byuan) as sum_byuan,sum(p_coupon1) as sum_coupon ,p_type')
		-> where($where)
		-> group('p_type')
		-> select();
		
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$data,'sumlist'=>$sumList,'startdata'=>$startdate));exit;
	}
}