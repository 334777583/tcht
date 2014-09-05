<?php
/**
 * FileName: typelose.class.php
 * Description:商城消费分析
 * Author: xiaoliao
 * Date:2013-12-12 10:09:51
 * Version:1.00
 */
class typelose{
	/**
	 * 服务器IP
	 * @var string
	 */
	public $ip;
	
	/**
	 * gm接口类
	 * @var class
	 */
	public $gm;
	
	/**
	 * 用户数据
	 * @var Array
	 */
	public $user;
	
	/**
	 * 每页显示记录数
	 * @var int
	 */
	private $pageSize = 10;
	
	/**
	 * 当前页
	 * @var int
	 */
	private $curPage = 1;
	
	/**
	 * 开始时间
	 * @var string
	 */
	private $startdate;
	
	/**
	 * 结束时间
	 * @var string
	 */
	private $enddate;
	
	/**
	 * 金钱类型（1：元宝；2：绑定元宝）
	 * @var int
	 */
	private $type;
	
	
	
	 /**
	 * 初始化数据
	 **/
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00401600', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		
		$this->gm = new autogm();
		$this->ip =  get_var_value('ip') == NULL? -1 : get_var_value('ip');
		$this->pageSize = get_var_value('pageSize') == NULL? 10: get_var_value('pageSize');
		$this->curPage =  get_var_value('curPage') == NULL? 1 : get_var_value('curPage');
		$this->startdate = get_var_value('startdate') == NULL? '': get_var_value('startdate');
		$this->enddate =  get_var_value('enddate') == NULL? '' : get_var_value('enddate');
		$this->type =  get_var_value('type') == NULL? 0 : get_var_value('type');
	}

	
	
	 /**
	 * FunctionName: typeNow
	 * Description: 获取实时流失情况
	 * Author: （jan）						
	 * Date: 2013-9-4 10:58:20	
	 **/
	public function typeNow(){
		$obj = D("game".$this -> ip);
		//$this-> getLog($this -> ip);
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$hour = get_var_value('hour')== NULL? 0: get_var_value('hour');
		// $this->analy();
		// die;
		if($hour == 1){			//当前1个小时内
			$start = date("Y-m-d H:i:s");
			$end = date("Y-m-d H:i:s",strtotime("+8 hours"));
		}
		if($hour == 2){			//1个小时前
			$start = date("Y-m-d H:i:s",strtotime("+6 hours"));
			$end = date("Y-m-d H:i:s",strtotime("+7 hours"));
		}
		if($hour == 3){			//2个小时前
			$start = date("Y-m-d H:i:s",strtotime("+5 hours"));
			$end = date("Y-m-d H:i:s",strtotime("+6 hours"));
		}
		if($hour == 4){			//3个小时前
			$start = date("Y-m-d H:i:s",strtotime("+5 hours"));
			$end = date("Y-m-d H:i:s",strtotime("+5 hours"));
		}
		if($hour == 5){			//当天
			$start = date("Y-m-d H:i:s",strtotime(date('Y-m-d',time())));
			$end = date("Y-m-d H:i:s",strtotime("+8 hours"));
		}if($hour == 0){		//指定时间
			$start = date('Y-m-d').' 00:00:00';
			$end = date('Y-m-d H',strtotime('+1 hours')).':00:00';
		}
		$c_creat = $obj->field('l_stid,count(l_plid) as count')->table('lose_temp')->where(array('l_cretime >='=>$start,'l_cretime <='=>$end))->group('l_stid')->select();
		$c_jump = $obj ->field('l_stid,count(l_plid) as jump')->table('lose_temp')->where(array('l_jumptime >='=>$start,'l_jumptime <='=>$end))->group('l_stid')->select();
		$c_succ = $obj ->field('l_stid,count(l_plid) as succ')->table('lose_temp')->where(array('l_suctime >='=>$start,'l_suctime <='=>$end))->group('l_stid')->select();
		$c_load = $obj ->field('l_stid,count(l_plid) as loading')->table('lose_temp')->where(array('l_loatime >='=>$start,'l_loatime <='=>$end))->group('l_stid')->select();
		$clist=array();
		foreach($c_creat as $key => $value){
				$list[$key]['cre']= $c_creat[$key]['count'];
				$list[$key]['tid']= $c_creat[$key]['l_stid'];
			if($c_creat[$key]['l_stid'] = $c_jump[$key]['l_stid']){
				$list[$key]['jum']=$c_jump[$key]['jump'];
			}
			if($c_creat[$key]['l_stid'] = $c_succ[$key]['l_stid']){
				$list[$key]['suc']=$c_succ[$key]['succ'];
			}
			if($c_creat[$key]['l_stid'] = $c_load[$key]['l_stid']){
				$list[$key]['loa']=$c_load[$key]['loading'];
			}
		}
		
				
		echo json_encode(array('list'=>$list,'startDate'=>$start,'endDate'=>$end));
		exit;		
	}
	
	 /**
	 * FunctionName: typeHistory
	 * Description: 获取玩家历史流失情况
	 * Author: （jan）						
	 * Date: 2013-9-5 15:58:20	
	 **/
	public function typeHistory(){
		$obj = D("game".$this -> ip);
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$day = get_var_value('d')== NULL? 0: get_var_value('d');
		if($day == 0){
			$start = $startdate;
			$end = $enddate;
		}
		if($day == 1){
			$start = date('Y-m-d H:i:s',strtotime(date('Y-m-d',time())) -(2*24+8)*60*60);
			$end = date('Y-m-d H:i:s',strtotime(date('Y-m-d',time())) -(1*24+8)*60*60-1);
		}
		if($day == 2){
			$start = date('Y-m-d H:i:s',strtotime(date('Y-m-d',time())) -(3*24+8)*60*60);
			$end = date('Y-m-d H:i:s',strtotime(date('Y-m-d',time())) -(2*24+8)*60*60-1);
		}
		// $phpsql = "select count(u_id) as count,u_stid from user_list where createtime <= '".$end."' group by u_stid";
		// $tzsql ="select count(a.a_id) as cjm,u.u_stid from account_list a inner join (select * from user_list GROUP BY u_plid) u on u.u_plid = a.a_playid where a.a_date <= '".$end."' group by u.u_stid";
		// $cgsql ="select count(u.c_id) as cjm,u.u_stid from creat_success c inner join (select * from user_list GROUP BY u_plid) u on u.u_plid = c.c_playid where c.c_time <= '".$end."' group by u.u_stid";
		// $jzsql ="select count(u.c_id) as cjm,u.u_stid from creat_play cuser_list c inner join (select * from user_list GROUP BY u_plid) u on u.u_plid = c.c_playid where c.c_time <= '".$end."' group by u.u_stid";
		$phpsql ="select count(u_id) as count,u_stid from user_list group by u_stid";
		$tzsql = "select count(a.a_id) as ctz,u.u_stid from account_list a inner join (select * from user_list GROUP BY u_plid) u on u.u_plid = a.a_playid group by u.u_stid";
		$cgsql = "select count(c.c_id) as cgs,u.u_stid from creat_success c inner join (select * from user_list GROUP BY u_plid) u on u.u_plid = c.c_playid group by u.u_stid";
		$jzsql = "select count(c.c_id) as cjz,u.u_stid from creat_play c inner join (select * from user_list GROUP BY u_plid) u on u.u_plid = c.c_playid group by u.u_stid";
		$cphp = $obj->fquery($phpsql);
		$ctz = $obj ->fquery($tzsql);
		$ccg = $obj ->fquery($cgsql);
		$cjz = $obj ->fquery($jzsql);
		
		$list =array();
		$n = count($cphp);
		foreach($cphp as $key =>$value){
		for($i = 0;$i<$n;$i++){
			$list[$key]['tyid'] = $cphp[$key]['u_stid'];
			$list[$key]['cphp'] = $cphp[$key]['count'];
			if(!empty($ctz[$key]['u_stid']) && $ctz[$key]['u_stid'] == $cphp[$i]['u_stid']){
				$list[$key]['ctz'] = $ctz[$key]['ctz'];
			}else{
				$list[$key]['ctz'] = 0;
			}
			if(!empty($ccg[$key]['u_stid']) && $ccg[$key]['u_stid'] == $cphp[$i]['u_stid']){
				$list[$key]['ccg'] = $ccg[$key]['cgs'];
			}else{
				$list[$key]['ccg'] = 0;
			}
			if(!empty($cjz[$key]['u_stid']) && $cjz[$key]['u_stid'] == $cphp[$i]['u_stid']){
				$list[$key]['cjz'] = $cjz[$key]['cjz'];
			}else{
				$list[$key]['cjz'] = 0;
			}
		}
		}		
		echo json_encode(array('list'=>$list));
		
	}

	 /**
	 * FunctionName: refresh
	 * Description: 获取创角实时数据并插入库
	 * Author: （jan）						
	 * Date: 2014-1-20 11:38:20	
	 **/
	public function refresh(){
	
		ini_set('memory_limit','1024M');
		set_time_limit(1000);
		$GLOBALS['date'] = date('Y-m-d',strtotime('-2 days'));				//默认分析前一天数据
		$GLOBALS['datetime'] = date('Y-m-d H:i:s',strtotime('-2 days'));
		$point = D("game".$this -> ip);				//连接的数据库句柄	
		$sql = 'delete from a_list_temp;'; 			//数据插入前清表
		$a = $point->rquery($sql);
		$sql = 'delete from c_success_temp;'; 
		$s = $point->rquery($sql);
		$sql = 'delete from c_play_temp;'; 
		$p = $point->rquery($sql);
		$sql = 'delete from lose_temp;'; 
		$l = $point->rquery($sql);
		$db = D('game_base');
		$filename = $db ->field('g_ip')->table('gamedb')->where(array('g_id' => $this->ip))->find();
		$path = "/data0/yanfa/php_home/log/". $filename['g_ip'].'/'. $GLOBALS['date'] . '/';	//日志文件所在目录路径	
		// $path = "D:/wamp/www/s0.zhanshen.aofyx.com" .'/'. $GLOBALS['date'] . '/';	//日志文件所在目录路径	
		$file = array();									//日志名称及日志字段和数据库对应关系
	
		$file['log-type-8.log'] = array(
											'table' => 'a_list_temp',
											'playid' => 'a_playid',
											'time' => 'a_date'
										);	
		$file['log-type-9.log'] = array(
											'table'   => 'c_success_temp',
											'playid'  => 'c_playid',
											'time'    => 'c_time'
										);	
		$file['log-type-10.log'] = array(
											'table'   => 'c_play_temp',
											'playid'  => 'c_playid',
											'time'    => 'c_time'
										);	
					
											
		foreach ($file as $name => $fields) {
			$file_name = $path.$name;	
			//echo "Log analyse " . $file_name . " start!<br/>";
			$fp = fopen($file_name, "r");							//读取日志文件
			if($fp) {
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
				if(!empty($log_data)) {
					$table = array_shift($fields);
					
					$sql = '';
					$i=1;
					foreach($log_data as $item) {
						$sql .= "(";
						$db_fields = "(";
						foreach($fields as $log_field => $db_field) {
							if($log_field == 'time') {				
								$item[$log_field] = date('Y-m-d H:i:s', $item[$log_field]);		
							} else if($log_field == 'date') {
								$item[$log_field] = date('Y-m-d');		
							}
							$sql .= "'" . $item[$log_field] . "',";
							$db_fields .= $db_field . ',';
						}
						$sql = rtrim($sql, ',');
						$sql .=	"),";
						$db_fields = rtrim($db_fields, ',');
						$db_fields .= ") ";	
						
						if($i%500 == 0 || $i == count($log_data)) {			//默认每五百条插入一次
							$sql = rtrim($sql, ',');
							$sql .= ';';
							$sql = 'insert into ' . $table . $db_fields . ' values ' . $sql;
							$f = $point-> table($table) -> rquery($sql);
							if(!$f) {
								echo "error";
								exit;
								
							}
							$sql = "";
						}
						$i++;
					}
					
					
					echo "Log analyse " . $file_name . " finish!<br/>";
					
					unset($log_data);
					unset($sql);
					unset($db_fields);
				}
				
			} else {
				echo "Can't not open file ".$path ."<br/>";
			}
			
		}
		
		global $t_conf;
		$sever = 's'.$this->ip;
		$obj = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$ctime = strtotime(date('Y-m-d',time()))-8*60*60;
		$uslist = "select p.guid ,p.accountid,g.bWallow typeid ,g.createtime from game_user g inner join player_table p on p.accountid = g.id where g.LastLogoutTime >=".$ctime;
		$ustype = $obj->fquery($uslist);
		
		$uscreat = $point-> table('c_success_temp')->select();
		$usplay = $point -> table('c_play_temp') ->select();
		$usjump = $point -> table('a_list_temp') ->select();
		$us =array();
		foreach($ustype as $key => $value){
				$us[$key]['typeid'] = $value['typeid'];
				$us[$key]['guid'] = $value['guid'];
				$us[$key]['cretime'] = $value['createtime'];
			if($ustype[$key]['guid'] = $uscreat[$key]['c_playid'] && is_array($uscreat[$key])){
				$us[$key]['sutime'] = $uscreat[$key]['c_time'];
			}else{
				$us[$key]['sutime'] = 0;
			}
			if($ustype[$key]['guid'] = $usplay[$key]['c_playid'] && is_array($usplay[$key])){
				$us[$key]['lotime'] = $usplay[$key]['c_time'];
			}else{
				$us[$key]['lotime'] = 0;
			}
			if($ustype[$key]['guid'] = $usjump[$key]['a_playid'] && is_array($uscreat[$key])){
				$us[$key]['jutime'] = $usjump[$key]['a_date'];
			}else{
				$us[$key]['jutime'] = 0;
			}
		}
		
		
		//整理需要插入数据
		
		$resultcount = count($us);
		$ins_data = "insert into lose_temp(l_stid,l_plid,l_jumptime,l_suctime,l_loatime,l_cretime) values ";
		$n = 800;
		//每次插入800条数据
		for($i = 0;$i<$resultcount;$i++){
			$stid= $us[$i]['typeid']; 		//角色id
			$guid = $us[$i]['guid'];	//类型
			$sutime = $us[$i]['sutime'];			//成功创角
			$jutime = $us[$i]['jutime'];			//跳转创角页面
			$lotime = $us[$i]['lotime'];			//进入游戏
			$cretime = $us[$i]['cretime'];
			$ins_data .= "('" . $stid . "','" . $guid . "','" . $jutime . "','" . $sutime ."','" . $lotime ."','".$cretime."'),";
			if($i > 0 && ($i % $n) == 0){
				$ins_data = rtrim($ins_data, ',');
				$ins_data .= ';';
				
				$ins_str = $point->rquery($ins_data);
				
				if(!$ins_str){//添加失败
					echo "error";
					exit;
				}
				$ins_data = "insert into lose_temp(l_stid,l_plid,l_jumptime,l_suctime,l_loatime,l_cretime) values ";
			}
		}
		
		$ins_data = rtrim($ins_data, ',');
		$ins_data .= ';';
		$ins_str = $point->rquery($ins_data);
		if(!$ins_str){//添加失败
			echo "false";
			exit;
		}
		echo "success";//返回插入成功
		
	
	}

	/**
	 * FunctionName: nowExcel
	 * Description: 导出实时数据
	 * Author: （jan）						
	 * Date: 2014-1-20 11:38:20	
	 **/
	private function nowExcel($path){
	
		require_once (AClass.'phpexcel/PHPExcel.php');
		
		$extend = pathinfo($path);
		$extend = strtolower($extend["extension"]);
		
		if($extend  == 'xls'){
			$objPHPExcel = PHPExcel_IOFactory::createReader('Excel5');//2007版本以下excel
		}else if($extend  == 'xlsx'){
			$objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007');//2007版本excel
		}
		
		$PHPExcel = $objPHPExcel->load($path);
		$sheet = $PHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		
		//循环读取excel文件
		for($j = 4;$j <= $highestRow; $j++){
			$result[$j]['gid'] = $PHPExcel->getActiveSheet()->getCell("B".$j)->getValue();//获取道具id
			$result[$j]['name'] = $PHPExcel->getActiveSheet()->getCell("C".$j)->getValue();//获取道具名
			$result[$j]['type1'] = $PHPExcel->getActiveSheet()->getCell("H".$j)->getValue();//type1
			$result[$j]['type2'] = $PHPExcel->getActiveSheet()->getCell("I".$j)->getValue();//type2
			$result[$j]['type3'] = $PHPExcel->getActiveSheet()->getCell("K".$j)->getValue();//type3
		}
		return $result;
		exit;
	}

	

	
}