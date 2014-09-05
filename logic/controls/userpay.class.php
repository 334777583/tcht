<?php
/**
   * FileName		: userpay.class.php
   * Description	: 付费分析
   * Author	    : zwy
   * Date			: 2014-6-6
   * Version		: 1.00
   */
class userpay{
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
			if(!in_array('00400600', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取数据
	 */
	public function getData(){
		$endDate = get_var_value('endDate');
		$dbList = D('game_base')->fquery("select g_id,g_name from gamedb where g_flag=1");
		$endDateTime = strtotime($endDate)+24*60*60;
		$startDateTime = $endDateTime-15*24*60*60;
		
		foreach ($dbList as $k=>$v){
			$result = array(
					'serverid'=>$v['g_name'],
					'date'=>'0000-00-00',
					'roleNum'=>0,//开服至统计截至日期的全部创建用户
					'payNum'=>0,//开服至统计截至日期的全部付费用户
					'activeNum'=>0,//截止统计日期前15天的全服付费用户
					'activeLoginNum'=>0,//活跃用户数
					'payMore'=>0,//截至统计日期付费次数2次或以上的全部付费用户
					'payPer'=>0,//注册用户付费率：付费用户/注册数
					'onlinePer'=>0,//平均在线付费率：总充值/总平均在线时间
					'avtivePayPer'=>0,//活跃用户付费率：活跃付费用户数/活跃用户数
					'dPer'=>0,//日ARPU：总ARPU/开服天数
					'mPer'=>0//月ARPU：总ARPU/开服月数,未满1个月的算0.5个月，如8月份只到8-7号 也算0.5个月
			);
			global $t_conf;
			$sever = 's'.$v['g_id'];
			$Server = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
			$sql = "SELECT a.id,a.account,b.GUID,b.RoleName,b.CreateTime,b.LoginTime,b.RMB,b.ServerId from player_table as b LEFT JOIN game_user as a on a.id=b.AccountId where b.CreateTime<={$endDateTime}";
			$play = $Server->fquery($sql);
			
			$activeNum = 0;//活跃用户数 截至统计时间前15天有登入的用户
			if (!empty($play)){
				foreach ($play as $pk=>$pv){
					if ($pv['LoginTime']>$startDateTime){
						$activeNum ++;
					}
				}
			}
			$result['activeLoginNum'] = $activeNum;
			
			$ChongZhiObj = D('chongzhi');
			$chongzhi = $ChongZhiObj->fquery("SELECT c_openid,c_pid,c_ts,c_times,c_price,c_num,c_ts from chongzhi WHERE c_sid=".$v['g_id']." and c_state=2 and c_ts<={$endDateTime}");
			
			if (empty($chongzhi)){
				continue;
			}
			
			$payAll = 0;
			$tem0 = array();
			$tem1 = array();
			$tem2 = array();
			foreach ($chongzhi as $ck=>$cv){
				if (!in_array($cv['c_pid'], $tem0)){
					$result['payNum'] ++;
					$tem0[] = $cv['c_pid'];
				}
				
				if (!in_array($cv['c_pid'], $tem1)&&($cv['c_ts']>$startDateTime)){
					$result['activeNum'] ++;
					$tem1[] = $cv['c_pid'];
				}
				
				if (!in_array($cv['c_pid'], $tem2)&&($cv['c_times']>1)){
					$result['payMore'] ++;
					$tem2[] = $cv['c_pid'];
				}
				
				$payAll += $cv['c_price']*$cv['c_num'];
			}
			
			$result['date'] = date('Y-m-d',$chongzhi[0]['c_ts']);
			$result['avtivePayPer'] = $activeNum==0?0:round($result['activeNum']*100/$activeNum,2);
			$result['roleNum'] = count($play);
// 			$result['payNum'] = count($chongzhi);
			$result['payPer'] = round($result['payNum']*100/$result['roleNum'],2);
			
			$Game = D('game'.$v['g_id']);
			$online = $Game->fquery("SELECT sum(line) as onlinetime,count(line) as number from (SELECT sum(o_second) as line from online_sec where o_date<='{$endDate}'  GROUP BY o_userid) as a");
			$totalAvgOnlineTime = empty($online)?0:$online[0]['onlinetime']/$online[0]['number']/60;
			
			$result['onlinePer'] = $totalAvgOnlineTime==0?0:round($payAll/$totalAvgOnlineTime,2);
			
			//$day = round((time()-$chongzhi[0]['c_ts'])/(24*60*60));
			$day = (strtotime(date('Y-m-d'))-strtotime(date('Y-m-d',$chongzhi[0]['c_ts'])))/(24*60*60)+1;
			
			$result['dPer'] = round($payAll/$day,2);
			$month = floor((time()-$chongzhi[0]['c_ts'])/(30*24*60*60))+0.5;
			if ($month<1){
				$month = 1;
			}
			$result['mPer'] = round($payAll/$month,2);
			
			$list[] = $result;
		}
		echo json_encode($list);exit;
	}
}

 