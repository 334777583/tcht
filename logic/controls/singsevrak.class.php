<?php
/**
 * FileName: singsevrak.class.php
 * Description:单服排行
 * Author: hjt
 * Date : 2013-9-6 10:37:51
 * Version:1.01
 */
class singsevrak{
	/**
	 * 用户数据
	 * @var Array
	 */
	public $user;

	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00300300', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 单服排行查询
	 */
	public function SingSevRak() {
		$ip = get_var_value('ip');
		$sort_key = get_var_value('sort_key') == NULL ? 'RMB' : get_var_value('sort_key');//需要排序的字段
		$sort = get_var_value('sort') == NULL ? 0 : get_var_value('sort');//排序  0、升序；1、倒序
		$curPage = get_var_value('curPage') == NULL ? 1 : get_var_value('curPage');//分页
		$pageSize = 25;
		
		global $t_conf;
		$sever = 's'.$ip;
		$obj = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
// 		$playerList = $obj->fquery("select GUID,RoleName,AccountId,`Level`,Gold,CreateTime,LoginTime,LogoutTime from player_table");
		$sql = "SELECT a.id,a.account,b.GUID,b.AccountId,b.`Level`,b.RoleName,b.Gold,b.CreateTime,";
		$sql.= "b.LoginTime,b.LogoutTime,b.RMB,b.ServerId ";
		$sql.= " from player_table as b LEFT JOIN game_user as a on a.id=b.AccountId where b.serverid=$ip";
		$playerList = $obj->fquery($sql);
		foreach ($playerList as $v){
			$userList[$v['GUID']] = $v;
		}


		//服务器名称
		$dbList = D('game_base')->fquery("select g_id,g_name from gamedb");
		foreach ($dbList as $k=>$v){
			$serverList[$v['g_id']] = $v['g_name'];
		}
		
		global $account_list;
		$AccountListObj = F($account_list['db'], $account_list['ip'], $account_list['user'], $account_list['password'], $account_list['port']);
		
		$where = "find_in_set($ip,sids)";
		$rollsuitPlayer = $AccountListObj->table("account_list")->where($where)->select();
		foreach ($rollsuitPlayer as $k=>$v){
			$rollsuitAccountServerNme[$v['account']] = array(
					'name' => $serverList [$v ['first_sid']],
					'date' => date ( 'Y-m-d H:i:s', $v ['first_create_time'] ) 
			);
		}
		
		$ChongZhi = D("chongzhi");
		$list = $ChongZhi ->fquery("SELECT sum(c_price*c_num) as RMB,c_pid,c_time from chongzhi where c_state=2 and c_sid={$ip} GROUP BY c_pid ORDER BY RMB DESC");

		global $task_db;
		$Task = DF($task_db);
// 		$task = DF($task_db)->table('task_market')->select();
// 		$taskOpen = '';
// 		foreach ($task as $v){
// 			$taskOpen .= $v['openid'].',';
// 		}
				
		foreach ($list as $k=>$v){
			if (isset($userList[$v['c_pid']])){
				$list[$k]['sortValue'] = $k+1;
				$list[$k]['RoleName'] = $userList[$v['c_pid']]['RoleName'];
				$list[$k]['account'] = $userList[$v['c_pid']]['account'];
				$list[$k]['Level'] = $userList[$v['c_pid']]['Level'];
				$list[$k]['Gold'] = $userList[$v['c_pid']]['Gold'];
				$list[$k]['CreateTime'] = empty($userList[$v['c_pid']]['CreateTime'])?'':date('Y-m-d H:i:s',$userList[$v['c_pid']]['CreateTime']);
				$list[$k]['LoginTime'] = empty($userList[$v['c_pid']]['LogoutTime'])?'':date('Y-m-d H:i:s',$userList[$v['c_pid']]['LogoutTime']);
				
				$list[$k]['firstCreateSid'] = $rollsuitAccountServerNme[$userList[$v['c_pid']]['account']]['name'];
				$list[$k]['firstCreateDate'] = $rollsuitAccountServerNme[$userList[$v['c_pid']]['account']]['date'];

				$s = $Task->table('task_market')->where('openid="'.$userList[$v['c_pid']]['account'].'"')->find();
				if ($s){
					$list[$k]['channel'] = '任务集市';
				}else {
					$list[$k]['channel'] = '';
				}
// 				if (strpos($taskOpen, $userList[$v['c_pid']]['account'])){
// 					$list[$k]['channel'] = '任务集市';
// 				}else {
// 					$list[$k]['channel'] = '';
// 				}
				
				$loginTime = time()-strtotime($list[$k]['LoginTime']);
				if ($loginTime>7*24*60*60){
					$list[$k]['class'] = "background:red;";
				}elseif ($loginTime>3*24*60*60){
					$list[$k]['class'] = "background:#800080;";
				}else {
					$list[$k]['class'] = "background:#eef2fb;";
				}
			}
			
		}
		
		$num = count($list);
		for($i = 1; $i < $num; $i ++) {
			for($j = $num - 1; $j >= $i; $j --){
				if ($sort){
					if ($list [$j][$sort_key] > $list [$j - 1][$sort_key]) {
						$x = $list [$j];
						$list [$j] = $list [$j - 1];
						$list [$j - 1] = $x;
					}
				}else {
					if ($list [$j][$sort_key] < $list [$j - 1][$sort_key]) {
						$x = $list [$j];
						$list [$j] = $list [$j - 1];
						$list [$j - 1] = $x;
					}
				}
			}
		}
		$list = array_slice($list,$pageSize*($curPage-1),$pageSize);
		
		//查询最近充值时间,登录时间超出显示
		foreach ($list as $k=>$v){
			$timeTem = $ChongZhi->fquery("select c_time from chongzhi where c_pid=".$v['c_pid']." and c_state=2 order by c_id desc");
			$list[$k]['lastPayTime'] = $timeTem[0]['c_time'];
		}
		
		$page = new autoAjaxPage($pageSize, $curPage, $num, "formAjax", "go","page");
		$pageHtml = $page->getPageHtml();
		echo json_encode(array('result' => $list,'pageHtml' => $pageHtml,'key_reset'=>$sort_key));
	}
	
	/**
	 * 单服活跃用户等级排行查询(3天内有登录的玩家进行等级排行)
	 */
	public function SingSevAct() {
		$ip = get_var_value('ip');
		
		if($ip) {
			$data = array();			//返回给页面的结果（经过组装后）
			
			if(true) {	
				global $t_conf;
				
				list($sip,$port,$loginName,$gid,$gfile) = autoConfig::getConfig($ip);
				$sever = 's'.$ip;
				$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
				if(!$point){
					echo json_encode(array(
						'error' => '数据库连接失败！'
					));
					exit;
				}else{
					$Sing_Field = 'account_code,name,level,create_time,last_down_time';
					
					//对3天内有登录的玩家进行等级排行
					$StartTime = strtotime('-3 day') * 1000;
					$EndTime = strtotime('+0 day') * 1000;
					$where_arr = array(
						'last_down_time >= ' => $StartTime,
						'last_down_time <= ' => $EndTime
					);
					//游戏角色信息
					$SingResult = $point -> table('t_player') -> field($Sing_Field) -> where($where_arr) -> select();		
				}
				if(!empty($SingResult)){//组装数据
					$data = $SingResult;
					foreach($SingResult as $key => $value){
						//初始化
						$downline_time = '';
						$create_time = '';
						$last_down_time = '';
						
						//当时间不存在的时候 默认为今天
						$create_time = $this -> DateFormat($data[$key]['createtime']);
						$last_down_time = $this -> DateFormat($data[$key]['logintime']);
						
						//组装数据
						$data[$key]['createtime'] = $create_time;//注册时间
						$data[$key]['logintime'] = $last_down_time;//最后下线时间
						
						//离线时间
						$down_time = $this -> DateFormat($SingResult[$key]['logintime'],1);
						$downline_time = $this -> ShowTime($down_time);
						$data[$key]['downline_time'] = $downline_time;
					}
				}
				
				
				echo json_encode(array('result' => $data));
				exit;
			} else {
				echo '1';
			}
		}
	}
	
	/**
	 * FunctionName: RankData
	 * Description: 即时更新单服排名数据
	 * Author: hjt	
	 * Parameter：null
	 * Return: boolen   
	 * Date: 2013-9-6 15:26:24
	 */
	public function RankData(){
		$mon_str = '';				//保存有充值的用户id字符串（用于二维降一维）
		$ins_data = '';				//插入的数据
		$sing_str = '';				//保存所有用户id字符串（用于二维降一维）
		$Mkey_len = 0;				//记录有充值的用户id数组长度（防止数组出现空值）
		$Skey_len = 0;				//记录所有用户id数组长度（防止数组出现空值）
		
		$is_money = array();		//有充值的用户id
		$sing_arr = array();		//所有用户id
		$no_money = array();		//没充值的用户id
		$money_array = array();		//所有用户充值结果（经过重组）
		
		$ip = get_var_value('ip');
		
		global $t_conf;
				
		//list($sip,$port,$loginName,$gid,$gfile) = autoConfig::getConfig($ip);//获取服务器信息

		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		if(!$point){
			echo json_encode(array(
				'error' => '数据库连接失败！'
			));
			exit;
		}else{
			$Obj = D('game');
			
			$Sing_Field = 'player_id,account_code,name,level,gold,create_time,last_down_time';
			//游戏角色信息
			$SingResult = $point -> table('t_player') -> field($Sing_Field)-> select();
			//充值金额
			$MonResult = $Obj -> table('pay_detail') -> field('p_playid,sum(p_money) as p_money') -> where('p_result = 1') -> group('p_playid') -> select();
			if($MonResult < 1 && $SingResult < 1){//没记录不更新
				echo json_encode(array('success' => 0));
				exit;
			}
			//将已充值的用户写成字符串（用于二维降一维）
			$Mkey_len = count($MonResult);
			if($Mkey_len > 0){
				foreach($MonResult as $key => $value) {
					$mon_str .= $value['p_playid'].',';
				}
			}
			$mon_str = rtrim($mon_str,',');
			//将所有用户写成字符串（用于二维降一维）
			$Skey_len = count($SingResult);
			foreach($SingResult as $key => $val) {
				$sing_str .= $val['player_id'].',';
			}
			$sing_str = rtrim($sing_str,',');
			
			//分离未充值的用户
			$is_money = explode(',',$mon_str);
			$sing_arr = explode(',',$sing_str);
			$no_money = array_diff($sing_arr,$is_money);
			//已充值的用户
			if($Mkey_len > 0){
				foreach($is_money as $key => $value){
					$money_array[$value] = $MonResult[$key]['p_money'];
				}
			}
			//未充值的用户默认为0
			foreach($no_money as $key => $value){
				$money_array[$value] = 0;
			}
			ksort($money_array);//重新排序 
			
			//将所有用户充值金额一起组装
			foreach($SingResult as $key => $val){
				$SingResult[$key]['money'] = $money_array[$sing_arr[$key]];
			}
			
			//先清空数据库
			$clear_res = $Obj -> rquery('delete from sing_rank');
			if(!$clear_res){//清空失败
				echo json_encode(array('success' => 0));
				exit;
			}
			
			//整理需要插入数据
			$Singcount = count($SingResult);
			$num = 800;
			$time = date('Y-m-d H:i:s');	
			$ins_data = "insert into sing_rank(s_acc,s_name,s_level,s_gold,s_money,s_create_time,s_last_down_time,s_ins_time) values ";

			//每次插入800条数据
			for($i = 0 ;$i < $Singcount ; $i++){
				$account_code = $SingResult[$i]['account_code'];//账号
				$name = $SingResult[$i]['name'];//角色
				$level = $SingResult[$i]['level'];//等级
				$gold = $SingResult[$i]['gold'];//元宝
				$money = $SingResult[$i]['money'];//充值金额
				$create_time = $this->DateFormat($SingResult[$i]['create_time']);//注册时间
				$last_down_time = $this->DateFormat($SingResult[$i]['last_down_time']);//最后登录时间
				
				$ins_data .= "('" . $account_code . "','" . $name . "','" . $level . "','" . $gold . "','" . $money . "','" . $create_time . "','" . $last_down_time ."','" . $time ."'),";
				
				if(($i % $num) == 0){
					$ins_data = rtrim($ins_data, ',');
					$ins_data .= ';';
					$ins_str = $Obj -> rquery($ins_data);
					if(!$ins_str){//添加失败
						echo json_encode(array('success' => 0));
						exit;
					}
					$ins_data = "insert into sing_rank(s_acc,s_name,s_level,s_gold,s_money,s_create_time,s_last_down_time,s_ins_time) values ";
				}
			}
			$ins_data = rtrim($ins_data, ',');
			$ins_data .= ';';
			$ins_str = $Obj -> rquery($ins_data);
			if(!$ins_str){//添加失败
				echo json_encode(array('success' => 0));
				exit;
			}
			 echo json_encode(array('success' => 1));//返回插入成功
			 exit;
		}
	}
	
	/**
	 * FunctionName: DateFormat
	 * Description: 时间戳转换(格式为 Y-m-d H:i:s)显示
	 * Author: hjt	
	 * Parameter：
	 * $Time 时间戳(单位：毫秒 或者 秒 也可以)
	 * $Type 显示模式(0、直接转换；1、直接显示) 默认直接转换
	 * Return: String				
	 * Date: 2013-8-30 17:10:19
	 **/
	private function DateFormat($Time,$Type = 0){
		//初始化
		$result = '';
		
		$Length = strlen($Time);
		if($Length > 10){//单位为毫秒时候 转成秒
			$Time = ceil($Time / 1000);
		}
		
		if($Type == 1){//直接显示数据
			$result = $Time;
			return $result;
		}
		//转换时间
		if($Time != 0 && $Time != ''){//当时间存在直接转换 否则默认为今天
			$result = date('Y-m-d H:i:s',$Time);
		}else{
			$result = date('Y-m-d H:i:s');
		}
		return $result;
	}
	
	
	/**
	 * FunctionName: ShowTime
	 * Description: 自动算出离线时间
	 * Author: hjt	
	 * Parameter：type $Time 最后下线时间
	 * Return: string   离线时间
	 * Date: 2013-8-30 10:43:19
	 */
	private function ShowTime($Time){
		//初始化
		$show_hour = '';
		$show_day = '';
		$result = '';
		
		$EndTime = strtotime('+0 day');//为今天的时间
		$StartTime = $Time;
		$DownLineTime = $EndTime - $StartTime;
		$result = $DownLineTime;
		/*$show_day = floor($DownLineTime / 3600 / 24);//天
		$show_hour = floor($DownLineTime / 3600 % 24);//小时
		
		if($show_hour == 0){//当离线小时小于1个小时  显示0.X小时
			$show_hour = round(floor($DownLineTime /60 %60)/60,1);
		}
		if($show_day > 0){//当离线大于1天 显示天数
			$result = $show_day.'天'.$show_hour.'小时';
		}else{//否则  只显示小时
			$result = $show_hour.'小时';
		}*/
		return $result;
	}
	
	/**
	 * 导出excel
	 */
	public function writeExcel(){
		$ip = get_var_value('ip');
		$Obj = D(GNAME.$ip);
		$SingResult = $Obj -> table('sing_rank') -> order('s_level desc') -> select();
				
				if(!empty($SingResult)){//组装数据
					//$sort_id = intval(($curPage - 1) * $pageSize + 1);
					foreach($SingResult as $key => $value){
						$data[$key]['id'] = $key +1;//id排序
						$data[$key]['account_code'] = $value['s_acc'];//账号
						$data[$key]['name'] = $value['s_name'];//角色
						$data[$key]['level'] = $value['s_level'];//等级
						$data[$key]['gold'] = $value['s_gold'];//元宝
						$data[$key]['money'] = $value['s_money'];//充值金额
						$data[$key]['create_time'] = $value['s_create_time'];//注册时间(单位秒)
						$data[$key]['last_down_time'] = $value['s_last_down_time'];//最后登录时间(单位秒)
						//$sort_id++;
					}
				}
		require_once(AClass.'phpexcel/PHPExcel.php');
		
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");
							 
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '排行');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '账号');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '角色');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '等级');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '剩余元宝');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '充值金额');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '注册时间');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '最后登录');
		
		if (is_array($data)) {
			foreach($data as $k => $item){
			
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $item["id"]);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["account_code"]);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["name"]);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["level"]);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["gold"]);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["money"]);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["create_time"]);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["last_down_time"]);
			}	
		}	
		
		$objPHPExcel->getActiveSheet()->setTitle('Simple');
		$objPHPExcel->setActiveSheetIndex(0);
		$file_name = "单服排行_".date('Y_m_d H_i_s');
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
}