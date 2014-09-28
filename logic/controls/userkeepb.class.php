<?php
/**
 * FileName: userkeep.class.php
 * Description:留存分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-5-18 14:20:29
 * Version:1.00
 */
class userkeepb{
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
	 * 时间
	 * @var string
	 */
	private $enddate;
	
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00400300', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		
		
		$this->ip =  get_var_value('sip') == NULL? -1 : get_var_value('sip');
		$this->enddate =  get_var_value('enddate');// == NULL? '' : get_var_value('enddate');
		$this->startdate =  get_var_value('startdate');// == NULL? '' : get_var_value('startdate');
	}
	
	
	/**
	 * user_temp缓存表取数据
	 */
	private function getRecordByTemp(&$loseIds, &$keepIds, $date, $point) {
		$point -> table('user_temp') -> where('u_expire < "'.date("Y-m-d").'"') -> delete();	//检查过期的，先清除(默认三天后)
	
		$keep_temp = $point -> table('user_temp') -> where('u_type = 0 and u_date = "'.$date.'"') -> select();
		$lose_temp = $point -> table('user_temp') -> where('u_type = 1 and u_date = "'.$date.'"') -> select();
		if($keep_temp != '') {
			foreach($keep_temp as $keep) {
				$keepIds[] = array('o_id' => $keep['u_oid'],'userid' => $keep['u_userid']);
			}	
		}
		if($lose_temp != '') {
			foreach($lose_temp as $lose) {
				$loseIds[] = array('o_id' => $lose['u_oid'],'userid' => $lose['u_userid']);
			}	
		}
	}

	public function getstartTime(){
		
		//list($ip, $port, $loginName) = autoConfig::getConfig($this->ip);
		$point = D('game'.$this->ip);
		$listdate = $point -> table("online_sec") -> field('left(o_date,10) as o_date') -> order('o_date asc')-> limit(0,1) ->find();
		echo json_encode(array('startDate'=>$listdate['o_date'],
							   'endDate'=>date('Y-m-d')
							   ));
	}
	
	public function getResult(){ //获取所有统计的数据
		$point = D('game'.$_POST['sip']);
		$enddate = date("Y-m-d",strtotime($this->enddate)+86400);
		//查询当前日期
		//$listdata = $point->fquery("SELECT COUNT(c_id) dataC,c_time  FROM creat_success WHERE c_time BETWEEN '{$_POST['startdate']}' AND '{$enddate}' GROUP BY date_format(c_time,'%Y-%m-%d')");
		$start = $point->table('creat_success')->field('left(c_time,10) as c_time')->order('c_time asc')->find();
		$startDate = get_var_value('startdate') == NULL ? $start['c_time'] : get_var_value('startdate');
		
		$creatrole="SELECT left(d_date,10) as d_date ,d_user FROM detail_login WHERE d_date BETWEEN '{$this->startdate}' AND '{$enddate}' group by d_user order by d_date desc";// GROUP BY date_format(c_time,'%Y-%m-%d')";
		
		$user = $point -> fquery($creatrole);
		foreach($user as $key => $item){
			$arr[$item['d_date']][$key] = "'".$item['d_user']."'";
		}
		// print_R($arr);
		foreach($arr as $key => $item){
			$se_start = date('Y-m-d H:i:s',strtotime($key)+24*3600);
			$se_end = date('Y-m-d H:i:s',strtotime($key)+2*24*3600);
			$th_start = date('Y-m-d H:i:s',strtotime($key)+2*24*3600);
			$th_end = date('Y-m-d H:i:s',strtotime($key)+3*24*3600);
			$fo_start = date('Y-m-d H:i:s',strtotime($key)+3*24*3600);
			$fo_end = date('Y-m-d H:i:s',strtotime($key)+4*24*3600);
			$fi_start = date('Y-m-d H:i:s',strtotime($key)+4*24*3600);
			$fi_end = date('Y-m-d H:i:s',strtotime($key)+5*24*3600);
			$si_start = date('Y-m-d H:i:s',strtotime($key)+5*24*3600);
			$si_end = date('Y-m-d H:i:s',strtotime($key)+6*24*3600);
			$sev_start = date('Y-m-d H:i:s',strtotime($key)+6*24*3600);
			$sev_end = date('Y-m-d H:i:s',strtotime($key)+7*24*3600);
			$two_start = date('Y-m-d H:i:s',strtotime($key)+13*24*3600);
			$two_end = date('Y-m-d H:i:s',strtotime($key)+14*24*3600);
			$mon_start = date('Y-m-d H:i:s',strtotime($key)+29*24*3600);
			$mon_end = date('Y-m-d H:i:s',strtotime($key)+30*24*3600);
			$ar[$key]=implode(',',$item);
			$listdata[]['dataC'] = count($item);//一天的登录数
			$date[]['time'] = $key;
			
			$second[] = $point->fquery('select COUNT(d_id) dataC, d_date as o_date from (select * from detail_login where d_user in ('.$ar[$key].') and d_date between "'.$se_start.'" and "'.$se_end.'" group by d_user) as c');
			$thr[] = $point->fquery('select COUNT(d_id) dataC, d_date as o_date from (select * from detail_login where d_user in ('.$ar[$key].') and d_date between "'.$th_start.'" and "'.$th_end.'" group by d_user) as c');
			$four[] = $point->fquery('select COUNT(d_id) dataC, d_date as o_date from (select * from detail_login where d_user in ('.$ar[$key].') and d_date between "'.$fo_start.'" and "'.$fo_end.'" group by d_user) as c');
			$five[] = $point->fquery('select COUNT(d_id) dataC, d_date as o_date from (select * from detail_login where d_user in ('.$ar[$key].') and d_date between "'.$fi_start.'" and "'.$fi_end.'" group by d_user) as c');
			$six[] = $point->fquery('select COUNT(d_id) dataC, d_date as o_date from (select * from detail_login where d_user in ('.$ar[$key].') and d_date between "'.$si_start.'" and "'.$si_end.'" group by d_user) as c');
			$seven[] = $point->fquery('select COUNT(d_id) dataC, d_date as o_date from (select * from detail_login where d_user in ('.$ar[$key].') and d_date between "'.$sev_start.'" and "'.$sev_end.'" group by d_user) as c');
			$week[] = $point->fquery('select COUNT(d_id) dataC, d_date as o_date from (select * from detail_login where d_user in ('.$ar[$key].') and d_date between "'.$two_start.'" and "'.$two_end.'" group by d_user) as c');
			$mon[] = $point->fquery('select COUNT(d_id) dataC, d_date as o_date from (select * from detail_login where d_user in ('.$ar[$key].') and d_date between "'.$mon_start.'" and "'.$mon_end.'" group by d_user) as c');
			
			
		}
			foreach($listdata as $key => $item){
				$listdata[$key]['c_time'] = $date[$key]['time'];
				$seconddata[$key]['dataC'] = $second[$key][0]['dataC'];
				$thrdata[$key]['dataC'] = $thr[$key][0]['dataC'];
				$fourdata[$key]['dataC'] = $four[$key][0]['dataC'];
				$fivedata[$key]['dataC'] = $five[$key][0]['dataC'];
				$sixdata[$key]['dataC'] = $six[$key][0]['dataC'];
				$sevendata[$key]['dataC'] = $seven[$key][0]['dataC'];
				$weekdata[$key]['dataC'] = $week[$key][0]['dataC'];
				$mondata[$key]['dataC'] = $mon[$key][0]['dataC'];
				$seconddata[$key]['c_time'] = $second[$key][0]['o_date']; 
				$thrdata[$key]['c_time'] = $thr[$key][0]['o_date']; 
				$fourdata[$key]['c_time'] = $four[$key][0]['o_date']; 
				$fivedata[$key]['c_time'] = $five[$key][0]['o_date']; 
				$sixdata[$key]['c_time'] = $six[$key][0]['o_date']; 
				$sevendata[$key]['c_time'] = $seven[$key][0]['o_date']; 
				$weekdata[$key]['c_time'] = $week[$key][0]['o_date']; 
				$mondata[$key]['c_time'] = $mon[$key][0]['o_date']; 
				
				$psec[$key]['dataC'] = round($seconddata[$key]['dataC'] / $listdata[$key]['dataC'] * 100,2);
				$pthr[$key]['dataC'] = round($thrdata[$key]['dataC'] / $listdata[$key]['dataC'] * 100,2);
				$pfou[$key]['dataC'] = round($fourdata[$key]['dataC'] / $listdata[$key]['dataC'] * 100,2);
				$pfiv[$key]['dataC'] = round($fivedata[$key]['dataC'] / $listdata[$key]['dataC'] * 100,2);
				$psix[$key]['dataC'] = round($sixdata[$key]['dataC'] / $listdata[$key]['dataC'] * 100,2);
				$psev[$key]['dataC'] = round($sevendata[$key]['dataC'] / $listdata[$key]['dataC'] * 100,2);
				$pwee[$key]['dataC'] = round($weekdata[$key]['dataC'] / $listdata[$key]['dataC'] * 100,2);
				$pmon[$key]['dataC'] = round($mondata[$key]['dataC'] / $listdata[$key]['dataC'] * 100,2);
				
				$list[$key]['dataC'] = $item['dataC'];
				$list[$key]['time'] = $date[$key]['time'];
				$list[$key]['psec'] = $psec[$key]['dataC'].'%('.$seconddata[$key]['dataC'].')';
				$list[$key]['pthr'] = $pthr[$key]['dataC'].'%('.$thrdata[$key]['dataC'].')';
				$list[$key]['pfou'] = $pfou[$key]['dataC'].'%('.$fourdata[$key]['dataC'].')';
				$list[$key]['pfiv'] = $pfiv[$key]['dataC'].'%('.$fivedata[$key]['dataC'].')';
				$list[$key]['psix'] = $psix[$key]['dataC'].'%('.$sixdata[$key]['dataC'].')';
				$list[$key]['psev'] = $psev[$key]['dataC'].'%('.$sevendata[$key]['dataC'].')';
				$list[$key]['pwee'] = $pwee[$key]['dataC'].'%('.$weekdata[$key]['dataC'].')';
				$list[$key]['pmon'] = $pmon[$key]['dataC'].'%('.$mondata[$key]['dataC'].')';
				
			}
			
			
			if(isset($list) && count($list) > 0){
				$tmpfname = tempnam('/tmp','ASDFGHJKEWRTYUI');
				$handle = fopen($tmpfname, "w");
				fwrite($handle, json_encode($list));
				fclose($handle);
				$filename = base64_encode($tmpfname);
			}		
			
			$return_Arr = array('listdata'=>$listdata,
								'seconddata'=>$seconddata,
								'thrdata'=>$thrdata,
								'fourdata'=>$fourdata,
								'fivedata'=>$fivedata,
								'sixdata'=>$sixdata,
								'sevendata'=>$sevendata,
								'weekdata'=>$weekdata,
								'mondata'=>$mondata,
								'startDate'=>$startDate,
								
								'seco'=>$psec,
								'thco'=>$pthr,
								'foco'=>$pfou,
								'fico'=>$pfiv,
								'sico'=>$psix,
								'sevco'=>$psev,
								'weco'=>$pwee,
								'moco'=>$pmon,
								'filename'=>$filename
								);
								
						
			if (!empty($listdata)) {
				 ECHO json_encode($return_Arr);
			}else{
				echo 1;
			}
		
	}

	public function getImgResult(){ //获取所有统计的图表数据
	
		$point = D('game'.$_POST['sip']);
		//查询当前日期
		$listdata = $point->fquery("SELECT COUNT(o_id) dataC,date_format(o_date,'%Y-%m-%d') Cdate  FROM online_sec WHERE o_date BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		//查询第二天统计数据
		
			$secondS = date("Y-m-d",strtotime($_POST['startdate'])+86400);
			$secondE = date("Y-m-d",strtotime($_POST['enddate'])+86400);
			$seconddata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$secondS}' AND '{$secondE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第三天数据统计
		
			$thrS = date("Y-m-d",strtotime($_POST['startdate'])+86400*2);
			$thrE = date("Y-m-d",strtotime($_POST['enddate'])+86400*2);
			$thrdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$thrS}' AND '{$thrE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第四天数据统计
		
			$fourS = date("Y-m-d",strtotime($_POST['startdate'])+86400*3);
			$fourE = date("Y-m-d",strtotime($_POST['enddate'])+86400*3);
			$fourdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$fourS}' AND '{$fourE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第五天数据统计
		
			$fiveS = date("Y-m-d",strtotime($_POST['startdate'])+86400*4);
			$fiveE = date("Y-m-d",strtotime($_POST['enddate'])+86400*4);
			$fivedata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$fiveS}' AND '{$fiveE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		
		
		

		//查询第六天数据统计
		
			$sixS = date("Y-m-d",strtotime($_POST['startdate'])+86400*5);
			$sixE = date("Y-m-d",strtotime($_POST['enddate'])+86400*5);
			$sixdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$sixS}' AND '{$sixE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第七天数据统计
		
			$sevenS = date("Y-m-d",strtotime($_POST['startdate'])+86400*6);
			$sevenE = date("Y-m-d",strtotime($_POST['enddate'])+86400*6);
			$sevendata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$sevenS}' AND '{$sevenE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		
		
		//查询双周数据统计
		
			$weekS = date("Y-m-d",strtotime($_POST['startdate'])+86400*13);
			$weekE = date("Y-m-d",strtotime($_POST['enddate'])+86400*13);
			$weekdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$weekS}' AND '{$weekE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询30天保留数据统计
		
			$monS = date("Y-m-d",strtotime($_POST['startdate'])+86400*29);
			$monE = date("Y-m-d",strtotime($_POST['enddate'])+86400*29);
			$mondata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$monS}' AND '{$monE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		$data_Arr = array();
		$str_arr = array();
		$string_arr = array();
		error_reporting(4);

		
			for ($i=0; $i <count($listdata); $i++) { 
				$str_arr[$i][] = '"time":"'.$listdata[$i]['Cdate'].'"';
				if(!empty($seconddata)){
					if ($i < count($seconddata)) {
						$str_arr[$i][] = '"second":'.$seconddata[$i]['dataC'];
					}
				}

				if(!empty($thrdata)){
					if ($i < count($thrdata)) {
						$str_arr[$i][] = '"thr":'.$thrdata[$i]['dataC'];
					}
				}

				if(!empty($fourdata)){
					if ($i < count($fourdata)) {
						$str_arr[$i][] = '"four":'.$fourdata[$i]['dataC'];
					}
				}

				if(!empty($fivedata)){
					if ($i < count($fivedata)) {
						$str_arr[$i][] = '"five":'.$fivedata[$i]['dataC'];
					}
				}

				if(!empty($sixdata)){
					if ($i < count($sixdata)) {
						$str_arr[$i][] = '"six":'.$sixdata[$i]['dataC'];
					}
				}

				if(!empty($sevendata)){
					if ($i < count($sevendata)) {
						$str_arr[$i][] = '"seven":'.$sevendata[$i]['dataC'];
					}
				}

				if(!empty($weekdata)){
					if ($i < count($weekdata)) {
						$str_arr[$i][] = '"week":'.$weekdata[$i]['dataC'];
					}
				}

				if(!empty($mondata)){
					if ($i < count($mondata)) {
						$str_arr[$i][] = '"mon":'.$mondata[$i]['dataC'];
					}
				}
				$string_arr[] = implode(',', $str_arr[$i]);
			}
			echo $str3 = "[{".implode('},{', $string_arr).'}]';
		/*
		global $t_conf;
		$point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		//查询当前日期
		$startdate = strtotime($_POST['startdate'].'00:00:00');
		$enddate = strtotime($_POST['startdate'].'23:59:59');
		$listdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime FROM player_table WHERE LoginTime BETWEEN {$startdate} AND {$enddate} GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		foreach ($listdata as $key => $value) {
			$listdata[$key]['LoginTime'] = date('Y-m-d',$value['LoginTime']);
		}

		//查询第二天统计数据
			$secondS = strtotime($_POST['startdate'].'00:00:00')+86400;
			$secondE = strtotime($_POST['startdate'].'23:59:59')+86400;
			$seconddata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$secondS}' AND '{$secondE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");

		//查询第三天数据统计
			$thrS = strtotime($_POST['startdate'].'00:00:00')+86400*2;
			$thrE = strtotime($_POST['startdate'].'23:59:59')+86400*2;
			$thrdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$thrS}' AND '{$thrE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询第四天数据统计
			$fourS = strtotime($_POST['startdate'].'00:00:00')+86400*3;
			$fourE = strtotime($_POST['startdate'].'23:59:59')+86400*3;
			$fourdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$fourS}' AND '{$fourE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询第五天数据统计
			$fiveS = strtotime($_POST['startdate'].'00:00:00')+86400*4;
			$fiveE = strtotime($_POST['startdate'].'23:59:59')+86400*4;
			$fivedata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$fiveS}' AND '{$fiveE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询第六天数据统计
			$sixS = strtotime($_POST['startdate'].'00:00:00')+86400*5;
			$sixE = strtotime($_POST['startdate'].'23:59:59')+86400*5;
			$sixdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$sixS}' AND '{$sixE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询第七天数据统计
			$sevenS = strtotime($_POST['startdate'].'00:00:00')+86400*6;
			$sevenE = strtotime($_POST['startdate'].'23:59:59')+86400*6;
			$sevendata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$sevenS}' AND '{$sevenE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询双周数据统计
			$weekS = strtotime($_POST['startdate'].'00:00:00')+86400*13;
			$weekE = strtotime($_POST['enddate'].'23:59:59')+86400*13;
			$weekdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$weekS}' AND '{$weekE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询30天保留数据统计
			$monS = strtotime($_POST['startdate'].'00:00:00')+86400*29;
			$monE = strtotime($_POST['enddate'].'23:59:59')+86400*29;
			$mondata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime > '{$monS}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		

		$data_Arr = array();
		$str_arr = array();
		$string_arr = array();
		error_reporting(4);

		
			for ($i=0; $i <count($listdata); $i++) { 
				$str_arr[$i][] = '"time":"'.$listdata[$i]['LoginTime'].'"';
				if(!empty($seconddata)){
					if ($i < count($seconddata)) {
						$str_arr[$i][] = '"second":'.$seconddata[$i]['cid'];
					}
				}

				if(!empty($thrdata)){
					if ($i < count($thrdata)) {
						$str_arr[$i][] = '"thr":'.$thrdata[$i]['cid'];
					}
				}

				if(!empty($fourdata)){
					if ($i < count($fourdata)) {
						$str_arr[$i][] = '"four":'.$fourdata[$i]['cid'];
					}
				}

				if(!empty($fivedata)){
					if ($i < count($fivedata)) {
						$str_arr[$i][] = '"five":'.$fivedata[$i]['cid'];
					}
				}

				if(!empty($sixdata)){
					if ($i < count($sixdata)) {
						$str_arr[$i][] = '"six":'.$sixdata[$i]['cid'];
					}
				}

				if(!empty($sevendata)){
					if ($i < count($sevendata)) {
						$str_arr[$i][] = '"seven":'.$sevendata[$i]['cid'];
					}
				}

				if(!empty($weekdata)){
					if ($i < count($weekdata)) {
						$str_arr[$i][] = '"week":'.$weekdata[$i]['cid'];
					}
				}

				if(!empty($mondata)){
					if ($i < count($mondata)) {
						$str_arr[$i][] = '"mon":'.$mondata[$i]['cid'];
					}
				}
				$string_arr[] = implode(',', $str_arr[$i]);
			}
			echo $str3 = "[{".implode('},{', $string_arr).'}]';
			*/
		
	}

	public function writeExcel(){
		$f = base64_decode($_GET['f']);
		if(!is_file($f)){
			echo 'error';
			exit();
		}
		$list = json_decode(file_get_contents($f),true);
		if(!empty($list)){

			require_once(AClass.'phpexcel/PHPExcel.php');
			
			$objPHPExcel = new PHPExcel();
			
			$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
								 ->setLastModifiedBy("Maarten Balliauw")
								 ->setTitle("PHPExcel Test Document")
								 ->setSubject("PHPExcel Test Document")
								 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
								 ->setKeywords("office PHPExcel php")
								 ->setCategory("Test result file");
								 
			
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '时间');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '新登陆账号');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '次日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '三日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '四日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '五日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '六日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '七日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', '双周日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', '30日留存率');
		
		//$DataType = PHPExcel_Cell_DataType::TYPE_STRING;//科学型 改成字符串型
		
		if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $item["time"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["dataC"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item['psec']);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item['pthr']);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item['pfou']);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item['pfiv']);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item['psix']);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item['psev']);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item['pwee']);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item['pmon']);
				}	
			}	

			$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);

			$da = date('Y-m-d');
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="留存分析.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
		}
	}
}	