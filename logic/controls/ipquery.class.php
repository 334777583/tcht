<?php
/**
   * FileName		: ipquery.class.php
   * Description	: 充值等级查询
   * Author	    : zwy
   * Date			: 2014-6-6
   * Version		: 1.00
   */
class ipquery{
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
			if(!in_array('00502000', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取数据
	 */
	public function getIpData(){
		$ip = get_var_value('ip');
		$startDate = get_var_value('startdate')==null?date('Y-m-d'):get_var_value('startdate');
		
		$Gamebase = D('game_base');
		$g_folder = $Gamebase->field('g_ip')->table('gamedb')->where('g_id = '.$ip)->find();
		$path = LPATH . $g_folder['g_ip'] . '/' . $startDate . '/';	//日志文件所在目录路径
// 		$path = LPATH.'192.168.0.64/2014-07-29/';
		$loginLogFilePath = $path.'log-type-2.log';
		if (!file_exists($loginLogFilePath)) {
			echo '1';exit;
		}
		$fp = fopen($loginLogFilePath, "r");		//读取日志文件
		$ipArr = array();
		while(!feof($fp)) {
			$line = fgets($fp,2048);
			if(!empty($line)) {
				$INFO  = trim(substr($line, 21));
				$INFO  = str_replace("'", '"', $INFO );
				$arr = json_decode($INFO , true);
				if(is_array($arr)) {
					if (isset($ipArr[$arr['ip']])){
						$ipArr[$arr['ip']]['count']++;
						$ipArr[$arr['ip']]['playid'][] = $arr['playid'];
						$ipArr[$arr['ip']]['account'][] = $arr['account'];
					}else {
						$ipArr[$arr['ip']]['count'] = 1;
						$ipArr[$arr['ip']]['playid'][] = $arr['playid'];
						$ipArr[$arr['ip']]['account'][] = $arr['account'];
					}
				}
			}
		}
		fclose($fp);		//关闭文件指针
		
		//汇总
		$summary = array('ip_num'=>0,'account_num'=>0,'playerid_num'=>0,'login_num'=>0);
		
		$i = 0;
		$list = array();
		foreach ($ipArr as $k=>$v){
			$playerid = array_unique($v['playid']);
			$account = array_unique($v['account']);
			$list[$i]['ip']= $k;
			$list[$i]['playerid']= count($playerid);
			$list[$i]['accountnum'] = count($account);
			$list[$i]['account'] = '';
			//$list[$i]['account'] = $account;
			$list[$i]['count'] = $v['count'];
			$i++;
			
			$summary['ip_num'] ++;
			$summary['account_num'] += count($account);
			$summary['playerid_num'] += count($playerid);
			$summary['login_num'] += $v['count'];
		}
		$num = count($list);
		for($i = 1; $i < $num; $i ++) {
			for($j = $num - 1; $j >= $i; $j --){
				if ($list [$j]['count'] > $list [$j - 1]['count']) { // 如果是从大到小的话，只要在这里的判断改成if($b[$j]>$b[$j-1])就可以了
					$x = $list [$j];
					$list [$j] = $list [$j - 1];
					$list [$j - 1] = $x;
				}
			}
		}
		echo json_encode(array('list'=>$list,'summary'=>$summary));
	}
	
	public function getPlayerIp(){
		$ip = get_var_value('ip');
		$startDate = get_var_value('startdate')==null?date('Y-m-d'):get_var_value('startdate');
		$rolename = get_var_value('rolename');
		$Gamebase = D('game_base');
		$g_folder = $Gamebase->field('g_ip,g_name')->table('gamedb')->where('g_id = '.$ip)->find();
		$path = LPATH . $g_folder['g_ip'] . '/' . $startDate . '/';	//日志文件所在目录路径
		$loginLogFilePath = $path.'log-type-2.log';
		if (!file_exists($loginLogFilePath)) {
			echo '1';exit;
		}
		global $t_conf;
		$sever = 's'.$ip;
		$obj = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		$role = $obj->fquery("select guid,rolename,serverid from player_table where rolename like '%".$rolename."%'");
		if (!$role){
			echo '1';exit;
		}
		foreach ($role as $k=>$v){
			$playeridArr[] = $v['guid'];
			$player[$v['guid']]['playerid'] = $v['guid'];
			$player[$v['guid']]['rolename'] = $v['rolename'];
			$player[$v['guid']]['serverid'] = $g_folder['g_name'];
		}
		$fp = fopen($loginLogFilePath, "r");		//读取日志文件
		$ipArr = array();
		while(!feof($fp)) {
			$line = fgets($fp,2048);
			if(!empty($line)) {
				$INFO  = trim(substr($line, 21));
				$INFO  = str_replace("'", '"', $INFO );
				$arr = json_decode($INFO , true);
				if(is_array($arr)&&in_array($arr['playid'], $playeridArr)) {
					if (isset($player[$arr['playid']]['ip'])){
						$player[$arr['playid']]['ip'] = $player[$arr['playid']]['ip'].'、'.$arr['ip'];
					}else {
						$player[$arr['playid']]['ip'] = $arr['ip'];
					}
					$player[$arr['playid']]['account'] = $arr['account'];
				}
			}
		}
		fclose($fp);		//关闭文件指针
		foreach ($player as $k=>$v ){
			if (empty($v['account'])){
				unset($player[$k]);
			}
		}
		sort($player);
		if (empty($player)){
			echo '1';
		}else {
			echo json_encode($player);
		}
	}
}

 