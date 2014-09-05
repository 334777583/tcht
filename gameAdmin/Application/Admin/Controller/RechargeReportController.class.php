<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆ All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-26
// +----------------------------------------------------------------------
// | Describe: 每日报表控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;

class RechargeReportController extends CommonController {
	public function _initialize() {
		// 判断用户权限
		if (! in_array ( '00100700', session ( 'user_code_arr' ) )) {
			$this->display ( 'Public/noauth' );
			exit ();
		}
	}
	public function index() {
		$this->assign ( 'serverList', session ( 'server_list' ) ); // 服务器列表
		$this->assign ( 'today', date ( 'Y-m-d', NOW_TIME ) ); // 默认显示今天的数据
		$this->display ();
	}
	
	/**
	 * 获取数据
	 */
	public function getReportData(){
		$serverId = I('serverId',1,'intval');//服务器id
		$year = I('year',2014,'intval');//年份
		$month = I('month',1,'intval');//月份
		
		if ($month<10){
			$date = $year.'-0'.$month;
		}else {
			$date = $year.'-'.$month;
		}
		
		$where = array();//查询条件
		$where['c_state'] = 2;//充值成功
		$where['left(c_time,7)'] = $date; //时间
		if ($serverId){
			$where['c_sid'] = $serverId;//指定服务器，如果$serverId为0则查询所有服务器的数据
		}
		
		$chongzhi = D('ChongZhi')->getAll($where);
		if (empty($chongzhi)){
			$this->ajaxReturn(array('status'=>0,'info'=>'没有数据'));exit;
		}
		
		//登录用户查询
		$loginAccount = array();
		if ($serverId){
			$sql = "SELECT count(num) as num,date from (SELECT count(*) as num,left(d_date,10) as date from ";
			$sql.= "detail_login WHERE left(d_date,7)='{$date}' GROUP BY left(d_date,10),d_user) as a GROUP BY date";
			$login = D('GetObj')->getObj($serverId)->query($sql);
			foreach ($login as $k=>$v){
				$loginAccount[$v['date']] = $v['num'];
			}
		}
		
		$list = array();//结果数组
		foreach ($chongzhi as $k=>$v){
			$dateTem = substr($v['c_time'], 0,10);
			if (!isset($list[$dateTem])){
				$list[$dateTem] = array();
			}
			$list[$dateTem]['date'] = $dateTem;//日期
			//登录人数
			if (isset( $loginAccount[$dateTem])){
				$list[$dateTem]['loginnum'] = $loginAccount[$dateTem];
			}else {
				$list[$dateTem]['loginnum'] = 0;
			}
			//充值次数
			if (isset($list[$dateTem]['paynum'])){
				$list[$dateTem]['paynum'] ++;
			}else {
				$list[$dateTem]['paynum'] = 1;
			}
			//充值人数
			$list[$dateTem]['paypeoplenum'][] = $v['c_openid'];
			//元宝
			if (isset($list[$dateTem]['gold'])){
				$list[$dateTem]['gold'] += $v['c_price']*$v['c_num'];
			}else {
				$list[$dateTem]['gold'] = $v['c_price']*$v['c_num'];
			}
			//q点
			if (isset($list[$dateTem]['c_amt'])){
				$list[$dateTem]['c_amt'] += $v['c_amt'];
			}else {
				$list[$dateTem]['c_amt'] = $v['c_amt'];
			}
			//游戏币
			if (isset($list[$dateTem]['payamt_coins'])){
				$list[$dateTem]['payamt_coins'] += $v['payamt_coins'];
			}else {
				$list[$dateTem]['payamt_coins'] = $v['payamt_coins'];
			}
			//金、银、铜券
			if (isset($list[$dateTem]['pubacct_payamt_coins'])){
				$list[$dateTem]['pubacct_payamt_coins'] += $v['pubacct_payamt_coins'];
			}else {
				$list[$dateTem]['pubacct_payamt_coins'] = $v['pubacct_payamt_coins'];
			}
			
			//处理首次充值用户
			if (!isset($list[$dateTem]['first_paypeopelnum'])){
				$list[$dateTem]['first_paypeopelnum'] = 0;
			}
			if (!isset($list[$dateTem]['first_gold'])){
				$list[$dateTem]['first_gold'] = 0;
			}
			if (!isset($list[$dateTem]['first_c_amt'])){
				$list[$dateTem]['first_c_amt'] = 0;
			}
			if (!isset($list[$dateTem]['first_payamt_coins'])){
				$list[$dateTem]['first_payamt_coins'] = 0;
			}
			if (!isset($list[$dateTem]['first_pubacct_payamt_coins'])){
				$list[$dateTem]['first_pubacct_payamt_coins'] = 0;
			}
			if ($v['c_times']==1){
				$list[$dateTem]['first_paypeopelnum']++;
				$list[$dateTem]['first_gold'] += $v['c_price']*$v['c_num'];
				$list[$dateTem]['first_c_amt'] += $v['c_amt'];
				$list[$dateTem]['first_payamt_coins'] += $v['payamt_coins'];
				$list[$dateTem]['first_pubacct_payamt_coins'] += $v['pubacct_payamt_coins'];
			}
			
		}
		
		foreach ($list as $k=>$v){
			$list[$k]['paypeoplenum'] = count(array_unique($list[$k]['paypeoplenum']));
		}
		sort($list);
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$list));exit;
	}
}
 