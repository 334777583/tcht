<?php
/**
   * FileName		: dailyreport.class.php
   * Description	: 元宝消费统计查询
   * Author	    : zwy
   * Date			: 2014-6-6
   * Version		: 1.00
   */
class dailyreport{
	/**
	 * 登录用户信息
	 */
	private $user;

	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00100700', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取数据
	 */
	public function getDailyReport(){
		$date = get_var_value('date');
		$ip = get_var_value('ip');
		if (!$ip){
			echo 1;exit;
		}
		$obj = D('chongzhi');
		//每日首次充值统计
		$firstSql  = "SELECT count(*) as num,sum(c_price*c_num) as money,sum(c_amt) as RMB,sum(payamt_coins) as coins,sum(pubacct_payamt_coins) as pubacce,c_sid,left(c_time,10) as date from chongzhi ";
		$firstSql .= " WHERE left(c_time,7)='{$date}' and  c_sid={$ip} and c_state=2 and c_times=1";
		$firstSql .= " group by left(c_time,10)";
		$first = $obj->fquery($firstSql);
		
		$login = D('game'.$ip)->fquery("SELECT count(num) as num,date from (SELECT count(*) as num,left(d_date,10) as date from detail_login WHERE left(d_date,7)='{$date}' GROUP BY left(d_date,10),d_user) as a GROUP BY date");
		$loginAccount = array();
		foreach ($login as $k=>$v){
			$loginAccount[$v['date']] = $v['num'];
		}
		
		$allSql = "SELECT c_openid,c_price,c_num,c_amt,payamt_coins,pubacct_payamt_coins,left(c_time,10) as date from chongzhi where c_sid={$ip} and c_state=2 and left(c_time,7)='{$date}'";
		$all = $obj->fquery($allSql);
		$allList = array();
		$accountTem = array();
		foreach ($all as $k=>$v){
			$allList[$v['date']]['date'] = $v['date'];
			//登录人数
			if (isset( $loginAccount[$v['date']])){
				$allList[$v['date']]['loginnum'] = $loginAccount[$v['date']];
			}else {
				$allList[$v['date']]['loginnum'] = 0;
			}
			//充值次数
			if (isset($allList[$v['date']]['paynum'])){
				$allList[$v['date']]['paynum'] ++;
			}else {
				$allList[$v['date']]['paynum'] = 1;
			}
			//充值人数
			if (!isset($accountTem[$v['date']])){
				$accountTem[$v['date']] = array();
			}
			if (!in_array($v['c_openid'], $accountTem[$v['date']])){
				if (isset($allList[$v['date']]['paypeoplenum'])){
					$allList[$v['date']]['paypeoplenum'] ++;
				}else {
					$allList[$v['date']]['paypeoplenum'] = 1;
				}
				$accountTem[$v['date']][] = $v['c_openid'];
			}
			//元宝
			if (isset($allList[$v['date']]['money'])){
				$allList[$v['date']]['money'] += $v['c_price']*$v['c_num'];
			}else {
				$allList[$v['date']]['money'] = $v['c_price']*$v['c_num'];
			}
			//q点
			if (isset($allList[$v['date']]['c_amt'])){
				$allList[$v['date']]['c_amt'] += $v['c_amt'];
			}else {
				$allList[$v['date']]['c_amt'] = $v['c_amt'];
			}
			//游戏币
			if (isset($allList[$v['date']]['payamt_coins'])){
				$allList[$v['date']]['payamt_coins'] += $v['payamt_coins'];
			}else {
				$allList[$v['date']]['payamt_coins'] = $v['payamt_coins'];
			}
			//金、银、铜券
			if (isset($allList[$v['date']]['pubacct_payamt_coins'])){
				$allList[$v['date']]['pubacct_payamt_coins'] += $v['pubacct_payamt_coins'];
			}else {
				$allList[$v['date']]['pubacct_payamt_coins'] = $v['pubacct_payamt_coins'];
			}
		}
		sort($allList);
// 		echo json_encode($allList);exit;
// 		echo $firstSql;exit;
		$list = array(
				'first'=>$first,
				'allList'=>$allList
		);
		echo json_encode($list);
	}
}

 