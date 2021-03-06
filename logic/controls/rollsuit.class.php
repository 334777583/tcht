<?php
/**
   * FileName		: rollsuit.class.php
   * Description	: 滚服统计
   * Author	    : zwy
   * Date			: 2014-7-12
   * Version		: 1.00
   */
class rollsuit{
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
			if(!in_array('00401800', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取数据
	 */
	public function getData(){
		$ip = get_var_value('ip');
		$pageSize = get_var_value('pageSize') == NULL ? 10 : get_var_value('pageSize');
		$curPage = get_var_value('curPage') == NULL ? 1 : get_var_value('curPage');
		
		if (empty($ip)){
			echo 1;exit;
		}
		
		//服务器名称
		$dbList = D('game_base')->fquery("select g_id,g_name from gamedb where g_flag=1");
		foreach ($dbList as $k=>$v){
			$serverList[$v['g_id']] = $v['g_name'];
		}
		
		$result = array(
				'sid'=>$serverList[$ip],
				'create_all'=>0,//总创角（去重）
				'create_all_1'=>0,
				'create'=>0,//滚服创角（去重）
				'create_1'=>0,
				'create_per'=>0,//滚服创角率
				'pay_count_all'=>0,//总付费数
				'pay_count'=>0,//滚服付费数
				'pay_count_per'=>0,//滚服付费率
				'pay_all'=>0,//总充值元宝
				'pay'=>0,//滚服充值元宝
				'pay_per'=>0//滚服付费比
		);
		
		//所有充值数据
		$chongzhi = D('chongzhi')->fquery("SELECT sum(c_price*c_num) as money,count(*) as num,c_pid,c_openid from chongzhi WHERE c_sid={$ip} and c_state=2 group by c_pid");
		$rmb = array();//玩家充值记录
		$payAccount = array();//所有充值账号
		foreach ($chongzhi as $k=>$v){
			$rmb[$v['c_pid']]['money'] = $v['money'];
			$rmb[$v['c_pid']]['num'] = $v['num'];
			
			$result['pay_all'] += $v['money'];//总充值元宝
			$payAccount[] = $v['c_openid'];//所有付费账号
		}
		$result['pay_count_all'] = count(array_unique($payAccount));
		
		global $account_list;
		$AccountListObj = F($account_list['db'], $account_list['ip'], $account_list['user'], $account_list['password'], $account_list['port']);
		
		//滚服==first_sid不为当前$ip，并且$ip在sids中
		$where = "first_sid!=$ip and find_in_set($ip,sids)";
		$rollsuitPlayer = $AccountListObj->table("account_list")->where($where)->select();
// 		echo $AccountListObj->return_sql();exit;
		foreach ($rollsuitPlayer as $k=>$v){
			$rollsuitAccount[] = $v['account'];//滚服玩家账号
			$rollsuitAccountServerNme[$v['account']] = $serverList[$v['first_sid']];
		}
		
		global $t_conf;
		$sever = 's'.$ip;
		$Server = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		//新服所有玩家信息
		$sql = "SELECT a.id,a.account,b.GUID,b.RoleName,b.CreateTime,b.LoginTime,b.RMB,b.ServerId,b.level ";
		$sql.= " from player_table as b LEFT JOIN game_user as a on a.id=b.AccountId where b.ServerId=$ip";
		$newServerPlayer = $Server->fquery($sql);
		
		$newAccount = array();//新服所有账号
		$oldAccount = array();//滚服所有角色账号
		$sollsuitPayAccount = array();//滚服付费账号
		
		foreach ($newServerPlayer as $k=>$v){
			$newAccount[] = $v['account'];
			
			if (in_array($v['account'], $rollsuitAccount)){
				$oldAccount[] = $v['account'];
				$tem = array();
				$tem = $v;
				if(isset($rmb[$v['GUID']])){
					$sollsuitPayAccount[] = $v['account'];//滚服付费账号
					$result['pay'] += $rmb[$v['GUID']]['money'];//滚服充值元宝
					
					$tem['paynum'] = $rmb[$v['GUID']]['num'];
					$tem['paymoney'] = $rmb[$v['GUID']]['money'];
				}else {
					$tem['paynum'] = 0;
					$tem['paymoney'] = 0;
				}
				$tem['oldServerId'] = $rollsuitAccountServerNme[$v['account']];
				$tem['CreateTime'] = date("Y-m-d H:i:s",$tem['CreateTime']);
				$tem['LoginTime'] = date("Y-m-d H:i:s",$tem['LoginTime']);
				$accountListTem[] = $tem;
			}
		}
		
		$result['create_all'] = count(array_unique($newAccount));//总创角（去重）
		$result['create_all_1'] = count($newAccount);
		$result['create'] = count(array_unique($oldAccount));//滚服创角（去重）
		$result['create_1'] = count($oldAccount);
		$result['pay_count'] = count(array_unique($sollsuitPayAccount));//滚服付费数
		
		$result['create_per'] = round($result['create']*100/$result['create_all'])."%";//滚服创角率
		$result['pay_count_per'] = round($result['pay_count']*100/$result['pay_count_all'])."%";//滚服付费率
		$result['pay_per'] = round($result['pay']*100/$result['pay_all'])."%";//滚服付费比
		
		//按充值金额降序排序
		$num = count($accountListTem);
		for($i = 1; $i < $num; $i ++) {
			for($j = $num - 1; $j >= $i; $j --){
				if ($accountListTem [$j]['paymoney'] > $accountListTem [$j - 1]['paymoney']) {
					$x = $accountListTem [$j];
					$accountListTem [$j] = $accountListTem [$j - 1];
					$accountListTem [$j - 1] = $x;
				}
			}
		}
		
		// 			$page = new autoAjaxPage($pageSize, $curPage, $total, "formAjax", "go","page");
		// 			$pageHtml = $page->getPageHtml();
		echo json_encode(array(
				'result' => $result,
				'accountList'=>$accountListTem
				// 					'pageHtml' => $pageHtml
		));exit;
	}
	
	/**
	 * 获取数据
	 */
	public function getData1(){
		$ip = get_var_value('ip');
		$pageSize = get_var_value('pageSize') == NULL ? 10 : get_var_value('pageSize');
		$curPage = get_var_value('curPage') == NULL ? 1 : get_var_value('curPage');
		if($ip>1) {
			//服务器名称
			$dbList = D('game_base')->fquery("select g_id,g_name from gamedb where g_flag=1");
			foreach ($dbList as $k=>$v){
				$serverList[$v['g_id']] = $v['g_name'];
			}
			
			global $t_conf;
			$sever = 's'.$ip;
			$Server = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
			
			$player = array();//某服游戏玩家账号信息
			$account = array();//游戏玩家账号一维数组
			$old = array();//老玩家（账号=》服务器id）
			$sql = "SELECT a.id,a.account,b.GUID,b.RoleName,b.CreateTime,b.LoginTime,b.RMB,b.ServerId from player_table as b LEFT JOIN game_user as a on a.id=b.AccountId";
			$play = $Server->fquery($sql);
			foreach ($play as $k=>$v){
				$account[$ip][] = $v['account'];//新服所有账号
				$accountList[$v['account']] = $v;//账号=》信息
			}
			
			//查找在老服有创角的玩家
			$oldAccount = array();
			for ($i=$ip-1;$i>0;$i--){
				$sever = 's'.$i;
				if (!isset($t_conf[$sever])){
					continue;
				}
				$ServerTem = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
				
				$player[$i] = $ServerTem->table('game_user')->field('account')->select();
				foreach ($player[$i] as $k=>$v){
					$account[$i][] = $v['account'];
				}
				$accountServerId[$i] = array_intersect($account[$i], $account[$ip]);
				foreach ($accountServerId[$i] as $k=>$v){
					$old[$v] = $i;
				}
				$oldAccount = array_merge($oldAccount,$accountServerId[$i]);
			}
			
			$oldAccount = array_unique($oldAccount);
			
			//创角不去重
			$oldAccountTem = '';
			foreach($oldAccount as $v){
				$oldAccountTem .= '"'.$v.'",';
			}
			$oldAccountTem = trim($oldAccountTem,',');
			$sqlTem  = "SELECT a.id,a.account,b.GUID,b.RoleName,b.CreateTime,b.LoginTime,b.RMB,b.ServerId ";
			$sqlTem .= " from player_table as b LEFT JOIN game_user as a on a.id=b.AccountId where a.account in ($oldAccountTem)";
			$createTem = $Server->fquery($sqlTem);
			
			$result = array(
					'sid'=>$serverList[$ip],
					'create_all'=>count(array_unique($account[$ip])),//总创角（去重）
					'create_all_1'=>count($account[$ip]),
					'create'=>count($oldAccount),//滚服创角（去重）
					'create_1'=>count($createTem),
					'create_per'=>0,//滚服创角率
					'pay_count_all'=>0,//总付费数
					'pay_count'=>0,//滚服付费数
					'pay_count_per'=>0,//滚服付费率
					'pay_all'=>0,//总充值元宝
					'pay'=>0,//滚服充值元宝
					'pay_per'=>0//滚服付费比
			);
			
			$chongzhi = D('chongzhi')->fquery("SELECT sum(c_price*c_num) as money,count(*) as num,c_openid from chongzhi WHERE c_sid={$ip} and c_state=2 group by c_openid");
			$rmb = array();//玩家充值记录
			foreach ($chongzhi as $k=>$v){
				$rmb[$v['c_openid']]['money'] = $v['money'];
				$rmb[$v['c_openid']]['num'] = $v['num'];
				$result['pay_all'] += $v['money'];
			}
			$result['pay_count_all'] = count($rmb);
			
			$arrTem3 = array();
			$tem = array();
			foreach ($oldAccount as $k=>$v){
				$tem = $accountList[$v];
				if(isset($rmb[$v])&&!in_array($v,$arrTem3)){
					$result['pay_count'] ++;
					$result['pay'] += $rmb[$v]['money'];
					$arrTem3[] = $v;
					$tem['paynum'] = $rmb[$v]['num'];
					$tem['paymoney'] = $rmb[$v]['money'];
				}else {
					$tem['paynum'] = 0;
					$tem['paymoney'] = 0;
				}
				$tem['oldServerId'] = $serverList[$old[$v]];
				$tem['CreateTime'] = date("Y-m-d H:i:s",$tem['CreateTime']);
				$tem['LoginTime'] = date("Y-m-d H:i:s",$tem['LoginTime']);
				$accountListTem[] = $tem;
			}
			$result['create_per'] = round($result['create']*100/$result['create_all'])."%";
			$result['pay_count_per'] = round($result['pay_count']*100/$result['pay_count_all'])."%";
			$result['pay_per'] = round($result['pay']*100/$result['pay_all'])."%";
			
			$num = count($accountListTem);
			for($i = 1; $i < $num; $i ++) {
				for($j = $num - 1; $j >= $i; $j --){
					if ($accountListTem [$j]['paymoney'] > $accountListTem [$j - 1]['paymoney']) {
						$x = $accountListTem [$j];
						$accountListTem [$j] = $accountListTem [$j - 1];
						$accountListTem [$j - 1] = $x;
					}
				}
			}
// 			$page = new autoAjaxPage($pageSize, $curPage, $total, "formAjax", "go","page");
// 			$pageHtml = $page->getPageHtml();
			echo json_encode(array(
					'result' => $result,
					'accountList'=>$accountListTem
// 					'pageHtml' => $pageHtml
				));exit;
		
		}else {
			echo '1';
		}
	}
}

 