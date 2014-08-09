<?php
/**
 * FileName: userrole.class.php
 * Description:创角分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-6-6 17:41:50
 * Version:1.00
 */
class userrole{
	/**
	 * 服务器IP
	 * @var string
	 */
	public $ip;
	
	
	/**
	 * 用户数据
	 * @var Array
	 */
	public $user;
	
	/**
	 * 结束时间
	 * @var string
	 */
	private $enddate;
	
	/**
	 * 开始时间
	 * @var string
	 */
	private $startdate;
	
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00400101', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}

	public function getshishi(){
		$ip = get_var_value('sip');
		$Gamebase = D('game_base');
		$g_folder = $Gamebase->field('g_ip')->table('gamedb')->where('g_id = '.$ip)->find();
		$path = LPATH . $g_folder['g_ip'] . '/' . date('Y-m-d') . '/';	//日志文件所在目录路径
		
		$result[0]['paynum'] = 0;//充值人数
		$result[0]['paycount'] = 0;//充值次数
		$result[0]['paymoney'] = 0;//充值金额
		$result[0]['jiaose'] = 0;//创角人数（账号去重）
		$result[0]['jiaoseall'] = 0;//创角人数(总)
		$result[0]['regcount'] = 0;
		$result[0]['login'] = 0;//登录总数
		$result[0]['online'] = 0;//当前在线
		
		global $t_conf;
		$sever = 's'.$ip;
		$obj = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		//注册数type8
		$reg_count = $obj->fquery("select count(id) as count from game_user");
		//在线3
		$online = $obj->fquery("SELECT count(GUID) count from player_table where bOnline=1 and ServerId = {$ip}");
		//登录数
		$result[0]['login'] = D('game'.$ip)->table('detail_login')->total();
		$loginLogFilePath = $path.'/log-type-2.log';
		if (file_exists($loginLogFilePath)) {
			$loginAccount = array();
			$ipTem = array();
			$fp = fopen($loginLogFilePath, "r");		//读取日志文件
			while(!feof($fp)) {
				$line = fgets($fp,2048);
				if(!empty($line)) {
					$INFO  = trim(substr($line, 21));
					$INFO  = str_replace("'", '"', $INFO );
					$arr = json_decode($INFO , true);
					if(is_array($arr)) {
						//$result[0]['login'] ++;//登录总数
						$loginAccount[] = $arr['account'];
						$ipTem[] = $arr['ip'];
					}
				}
			}
			fclose($fp);		//关闭文件指针
			$result[0]['login'] += count(array_unique($loginAccount));
			$ipTem = array_unique($ipTem);
			//echo json_encode($ipTem);exit;
			$result[0]['login'] .= '(今天登录ip数:'.count($ipTem).')';
		}
		
		$jiaose = $obj->fquery("SELECT COUNT(GUID) count FROM player_table where ServerId = {$ip} ");
		$jiaose1 = $obj->fquery("SELECT COUNT(GUID) count FROM player_table as a LEFT JOIN game_user as b on a.AccountId=b.id WHERE a.ServerId = {$ip}  group by AccountId");
		//充值相关
		$pay = D("chongzhi")->fquery("SELECT c_price,c_num,c_openid,c_sid,left(c_time,10) as date from chongzhi where c_sid={$ip} and c_state=2");
		$openidTem = array();
		foreach ($pay as $k=>$v){
			$result[0]['paycount']++;
			$result[0]['paymoney'] += $v['c_price']*$v['c_num'];
			if (!in_array($v['c_openid'], $openidTem)){
				$result[0]['paynum']++;
				$openidTem[] = $v['c_openid'];
			}
			if ($v['date']==date("Y-m-d")){
				$currentPlayer[] = $v['c_openid']; 
			}
		}
		
		if (!empty($currentPlayer)){
			$result[0]['currentpaynum'] = count(array_unique($currentPlayer));//当天充值人数
		}else {
			$result[0]['currentpaynum'] = 0;
		}
		
		$result[0]['jiaose'] = empty($jiaose1)?0:count($jiaose1);//创角人数（账号去重）
		$result[0]['jiaoseall'] = empty($jiaose)?0:$jiaose[0]['count'];//创角人数(总)
		$result[0]['regcount'] = $reg_count[0]['count'];
		$result[0]['online'] = empty($online)?0:$online[0]['count'];//当前在线
		echo json_encode($result);exit();
	}
	
	public function querystage(){
		$ip = get_var_value('sip');
		$timer = get_var_value('timer');
		if(!$timer){
			$time = time();
		}else {
			$time = strtotime($timer);
		}
		
		$Gamebase = D('game_base');
		$g_folder = $Gamebase->field('g_ip')->table('gamedb')->where('g_id = '.$ip)->find();
		$path = LPATH . $g_folder['g_ip'] . '/' . date('Y-m-d') . '/';	//日志文件所在目录路径
		
		$result[0]['paynum'] = 0;//充值人数
		$result[0]['paycount'] = 0;//充值次数
		$result[0]['paymoney'] = 0;//充值金额
		$result[0]['jiaose'] = 0;//创角人数（账号去重）
		$result[0]['jiaoseall'] = 0;//创角人数(总)
		$result[0]['regcount'] = 0;
		$result[0]['login'] = 0;//登录总数
		$result[0]['online'] = 0;//当前在线
		
		// 		$nows = strtotime(date('Y-m-d'));//当天凌晨时间戳
		global $t_conf;
		$sever = 's'.$ip;
		$obj = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		//注册数type8
		$reg_count = $obj->fquery("select count(id) as count from game_user where CreateTime<={$time}");
		$result[0]['regcount'] = $reg_count[0]['count'];
		//在线3
		$onlineLogFilePath = $path.'/log-type-3.log';
		if (file_exists($onlineLogFilePath)) {
			$fp = fopen($onlineLogFilePath, "r");							//读取日志文件
			while(!feof($fp)) {
				$line = fgets($fp,2048);
				if(!empty($line)) {
					$INFO  = trim(substr($line, 21));
					$INFO  = str_replace("'", '"', $INFO );
					$arr = json_decode($INFO , true);
					if(is_array($arr)&&$arr['time']<=$time) {
						$result[0]['online'] = $arr['num'];//当前在线
					}else {
						break;
					}
				}
			}
			fclose($fp);										//关闭文件指针
		}
		//登录数
		$result[0]['login'] = D('game'.$ip)->table('detail_login')->total();
		$loginLogFilePath = $path.'/log-type-2.log';
		if (file_exists($loginLogFilePath)) {
			$loginAccount = array();
			$ipTem = array();
			$fp = fopen($loginLogFilePath, "r");		//读取日志文件
			while(!feof($fp)) {
				$line = fgets($fp,2048);
				if(!empty($line)) {
					$INFO  = trim(substr($line, 21));
					$INFO  = str_replace("'", '"', $INFO );
					$arr = json_decode($INFO , true);
					if(is_array($arr)) {
						//$result[0]['login'] ++;//登录总数
						$loginAccount[] = $arr['account'];
						$ipTem[] = $arr['ip'];
					}
				}
			}
			fclose($fp);		//关闭文件指针
			$result[0]['login'] += count(array_unique($loginAccount));
			$result[0]['login'] .= '(今天登录ip数:'.count(array_unique($ipTem)).')';
		}
		
		$jiaose = $obj->fquery("SELECT COUNT(GUID) as count FROM player_table where ServerId = {$ip} and CreateTime<{$time}");
		$jiaose1 = $obj->fquery("SELECT COUNT(AccountId) as count FROM player_table WHERE ServerId = {$ip} and  CreateTime<{$time} group by AccountId");
		//echo "SELECT COUNT(AccountId) as count FROM player_table WHERE ServerId = {$ip} and  CreateTime<{$time} group by AccountId";exit;
		$result[0]['jiaose'] = count($jiaose1);//创角人数（账号去重）
		$result[0]['jiaoseall'] = $jiaose[0]['count'];//创角人数(总)
		
		//充值相关
		$pay = D("chongzhi")->fquery("SELECT c_price,c_num,c_openid,c_sid,left(c_time,10) from chongzhi where c_sid={$ip} and c_state=2 and c_ts<{$time}");
		$openidTem = array();
		foreach ($pay as $k=>$v){
			$result[0]['paycount']++;
			$result[0]['paymoney'] += $v['c_price']*$v['c_num'];
			if (!in_array($v['c_openid'], $openidTem)){
				$result[0]['paynum']++;
				$openidTem[] = $v['c_openid'];
			}
			if ($v['date']==date("Y-m-d")){
				$currentPlayer[] = $v['c_openid']; 
			}
		}
		
		if (!empty($currentPlayer)){
			$result[0]['currentpaynum'] = count(array_unique($currentPlayer));//当天充值人数
		}else {
			$result[0]['currentpaynum'] = 0;
		}
		echo json_encode($result);exit();
	}
	
	public function fileDate($path){
		if (file_exists($path)) {
			$fp = fopen($path, "r");							//读取日志文件
			$log_data = array();								//保存日志分析信息
			while(!feof($fp)) {
				$line = fgets($fp,2048);
				if(!empty($line)) {
					$INFO  = trim(substr($line, 21));
					$INFO  = str_replace("'", '"', $INFO );
					$arr = json_decode($INFO , true);
					if(is_array($arr)) {
						$log_data[] = $arr;
					}
				}
			}
			fclose($fp);										//关闭文件指针
			return $log_data;
		}else {
			return array('no file');
		}
	}
	
	/**
	 * 获取行为分析数据
	 */
	public function getRole() {
	
		$ip = get_var_value('sip');
		$point = D('game'.$ip);
		$start = $point->field('c_date')->table('createplay')->order('c_date asc')->find();
		$startdate = get_var_value('startdate') == NULL? $start['c_date'] : get_var_value('startdate');
		$enddate = get_var_value('enddate');
		//$date = substr(file_get_contents(TPATH."/log-type-9.log"), 1,10);
		// $file2 = count($this->getLog(file_get_contents(TPATH."/log-type-10.log")));
		// $file1 = count($this->getLog(file_get_contents(TPATH."/log-type-9.log")));
		// $point = D('game'.$ip);
		// $point->fquery("INSERT INTO createplay(c_csuccess,c_entergame,c_date) VALUES({$file1},{$file2},'{$date}')");
		// die;
		
		if($ip && $startdate && $enddate) {
			$enddate = $enddate.' 23:59:59';
			//$point
			$list = $point -> table('createplay') -> where('c_date >= "'.$startdate .'" and c_date <= "'.$enddate.'"') ->select();
			
			//创角数去重
			global $t_conf;
			$sever = 's'.$ip;
			$obj = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
			$starttime = strtotime($startdate);
			$endtime = strtotime($enddate);
			$play_table = $obj->fquery("SELECT count(a.GUID) as count,FROM_UNIXTIME(b.CreateTime,'%Y-%m-%d') as date,a.AccountId FROM player_table as a LEFT JOIN game_user as b on a.AccountId=b.id where a.ServerId=$ip and b.CreateTime BETWEEN $starttime and $endtime GROUP BY date,a.AccountId");
			
			if ($play_table!=''){
				foreach ($play_table as $k=>$v){
					if (!isset($play[$v['date']])){
						$play[$v['date']] = 0;
					}
					$play[$v['date']] += 1;
				}
			}
// 			echo json_encode($play);exit();
			if($list != '') {
				foreach($list as $k => $item) {
					if($item['c_enter'] != 0&&isset($play[mb_substr($item['c_date'], 0,10,'utf-8')]) ){
						$cjv = $play[mb_substr($item['c_date'], 0,10,'utf-8')]/$item['c_enter'];							//创角率
						if($cjv < 0) {															//负数设为0
							$cjv = 0;
						}
					}else {
						$cjv = 0;
					}
					if($item['c_csuccess'] != 0) {
						$load = ($item['c_csuccess']-$item['c_entergame'])/$item['c_csuccess'];	 //流失率
						if($load < 0) {
							$load = 0;
						}
					}else {
						$load = 0;
					}
					
					$list[$k]['c_cjv'] = sprintf('%0.2f',$cjv*100).'%';
					$list[$k]['c_load'] = sprintf('%0.2f',$load*100).'%';
				}
			}
			echo json_encode(array('result' => $list,'startDate'=>date('Y-m-d',strtotime($startdate))));
			exit;
		} else {
			echo '1';
		}
	}
	
	//任务集市创角统计
	public function taskquery(){
		$ip = get_var_value('sip');
		global $task_db;
		$Task = F($task_db['db'], $task_db['ip'], $task_db['user'], $task_db['password'], $task_db['port']);
		$taskAccount = $Task->table('task_market')->where('serverid='.$ip)->select();
		$account1 = array();
		foreach ($taskAccount as $k=>$v){
			$account1[] = '"'.$v['openid'].'"';
		}
		$account = array_unique($account1);
		//成功创建角色的玩家数 	付费金额 	付费人数 	付费次数 	ARPU 	当前在线
		$result = array (
				'tasknum' => 0,
				'rolenum' => 0 ,
				'paymoney'=>0,//付费金额
				'paypeoplenum'=>0,//付费人数
				'paynum'=>0,//付费次数
				'payarpu'=>0,//	ARPU
				'online'=>0//当前在线
		) ;
		$result['tasknum'] = "总任务数：(".count($account1).")玩家数：（".count($account).")";
		
		//echo count($account);exit;
		$account = implode(',', $account);//任务集市玩家账号
		
		global $t_conf;
		$sever = 's'.$ip;
		$obj = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$sql = "SELECT a.account,b.GUID,b.RMB,b.bOnline from player_table as b LEFT JOIN game_user as a on a.id=b.AccountId WHERE a.account in(".$account.")";
		$play_table = $obj->fquery($sql);
 		//echo json_encode($sql);exit;
 		
 		$accountTem1 = array();//玩家账号去重
 		foreach ($play_table as $k=>$v){
 			$accountTem1[] = $v['account'];
 			if ($v['bOnline']==1){
 				$result['online'] ++;
 			}
 		}
 		$result['rolenum'] = count(array_unique($accountTem1));
 		
 		$pay = D("chongzhi")->fquery("SELECT c_price,c_num,c_openid,c_sid from chongzhi where c_sid={$ip} and c_state=2 and c_openid in ({$account})");
 		$openidTem = array();
 		foreach ($pay as $k=>$v){
 			$result['paynum']++;
 			$result['paymoney'] += $v['c_price']*$v['c_num'];
 			if (!in_array($v['c_openid'], $openidTem)){
 				$result['paypeoplenum']++;
 				$openidTem[] = $v['c_openid'];
 			}
 		}
 		
 		$result['payarpu'] = round($result['paymoney']/$result['paypeoplenum'],2);
		echo json_encode ($result);
		exit();
	}
	
	public function shishi() {
	
		$ip = get_var_value('sip');
		$enddate = date('Y-m-d');
		$point = D('game'.$ip);
		
		$obj = D('game_base');
		
		//插入实时数据
		$s_ip = $obj ->table('servers')->where("s_id = {$ip}")->find();
		
		$cfile = LPATH.$s_ip['s_ip'].'/'.$enddate.'/log-type-9.log';
		$pfile = LPATH.$s_ip['s_ip'].'/'.$enddate.'/log-type-10.log';
		// $file = file_get_contents($file);
		// $file = explode("\n", $file);
		$fp = fopen($cfile, "r");	 //创角成功
		$c_data = array();								//保存日志分析信息
		while(!feof($fp)) {					
			$line = fgets($fp,2048);
			if(!empty($line)) {
				$INFO  = trim(substr($line, 21));
				$INFO  = str_replace("'", '"', $INFO );
				$arr = json_decode($INFO , true);
				if(is_array($arr)) {
					$c_data[] = $arr;
				}
				
			}
		}
		fclose($fp);
		
		$fp = fopen($pfile, "r");						//成功进入游戏
		$p_data = array();
		while(!feof($fp)) {					
			$line = fgets($fp,2048);
			if(!empty($line)) {
				$INFO  = trim(substr($line, 21));
				$INFO  = str_replace("'", '"', $INFO );
				$arr = json_decode($INFO , true);
				if(is_array($arr)) {
					$p_data[] = $arr;
				}
				
			}
		}
		fclose($fp);
		foreach($p_data as $key => $value){
			$pag[$key]['pid'] = $value['playid'];
		}
		foreach ($pag as $v) 
		{ 
			$v = join(",",$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串 
			$temp[] = $v; 
		} 
		$temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组 
		foreach ($temp as $k => $v) 
		{ 
			$temp[$k] = explode(",",$v); //再将拆开的数组重新组装 
		} 
		
		//创角数去重
		global $t_conf;
		$sever = 's'.$ip;
		$obj = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		$starttime = strtotime(date('Y-m-d'));
		$endtime = strtotime(date('Y-m-d').' 23:59:59');
		$play_table = $obj->fquery("SELECT count(GUID) as count,FROM_UNIXTIME(CreateTime,'%Y-%m-%d') as date,AccountId FROM player_table where CreateTime BETWEEN $starttime and $endtime and ServerId = {$ip}   GROUP BY date,AccountId");
		
		if ($play_table!=''){
			foreach ($play_table as $k=>$v){
				if (!isset($play[$v['date']])){
					$play[$v['date']] = 0;
				}
				$play[$v['date']] += 1;
			}
		}
		$login = 0;//平台成功跳转
		$logincount = D('game'.$ip)->table('type_jump')->where("time='".date("Y-m-d")."'")->select();
		$login = count($logincount);
// 		$file_login_data = LPATH."testlogin/login_testS0".date('Ymd').".log";
// 		if (file_exists($file_login_data)) {
// 			$fp = fopen($file_login_data, "r");							//读取日志文件
// 			$log_data = array();								//保存日志分析信息
// 			while(!feof($fp)) {
// 				$line = fgets($fp,2048);
// 				if(!empty($line)) {
// 					$login++;
// 				}
// 			}
// 			fclose($fp);										//关闭文件指针
// 		}
		
		if($login != 0 &&isset($play[date('Y-m-d')])){
			$cjv = $play[date('Y-m-d')]/$login;							//创角率
			if($cjv < 0) {															//负数设为0
				$cjv = 0;
			}
		}else {
			$cjv = 0;
		}
		
		$arr = '';
		$count_c = count($c_data);
		$count_f = count($temp);
		
		$list = '';
		
		if($count_c != 0) {
			$load = ($count_c-$count_f)/$count_c;	 //流失率
			if($load < 0) {
				$load = 0;
			}
		}else {
			$load = 0;
		}
		$list[0]['c_date'] = date('Y-m-d H:i:s');
		$list[0]['c_enter'] = $login;
		$list[0]['c_csuccess'] = $count_c;
		$list[0]['c_entergame'] = $count_f;
		$list[0]['c_cjv'] = sprintf('%0.2f',$cjv*100).'%';
		$list[0]['c_load'] = sprintf('%0.2f',$load*100).'%';
			
		
		echo json_encode(array('result' => $list,'startDate'=>date('Y-m-d')));
		exit;
	}
}