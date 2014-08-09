<?php
/**
 * FileName: userlogin.class.php
 * Description: 登录概况
 * Author: xiaochengcheng
 * Date: 2013-4-8 14:23:40
 * Version: 1.00
 **/
class userlogin{
	/**
	 * 服务器IP
	 * @var String
	 */
	private $ip;
	
	/**
	 * 开始时间，默认当天的前七天
	 * @var String
	 */
	private $startDate;
	
	/**
	 * 结束时间，默认今天
	 * @var String
	 */
	private $endDate;
	
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
	 * 用户数据
	 * @var array
	 */
	private $user;
	
	/**
	 * 初始化数据
	 */
	
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo "not available!";
			exit();
		}else{
			if(!in_array("00400200", $this->user["code"])){
				echo "not available";
				exit();
			}
		}
		$this->ip =  get_var_value("ip") == NULL?-1:get_var_value("ip");
		$this->startDate = get_var_value("startDate") == NULL?date("Y-m-d",strtotime("-7 day")):date("Y-m-d",strtotime(get_var_value("startDate")));
		$this->endDate = get_var_value("endDate") == NULL?date("Y-m-d",strtotime("-1 day")):date("Y-m-d",strtotime(get_var_value("endDate")));
		$this->pageSize = get_var_value("pageSize") == NULL?10:get_var_value("pageSize");
		$this->curPage =  get_var_value("curPage") == NULL?1:get_var_value("curPage");
	}
	
	/**
	 * ajax获取登录概况数据
	 */
	public function getdata(){
		$obj = D("game".$this->ip);
		$start = $obj->field('m_date')->table('main_login')->order('m_date asc')->find();
		$startDate = get_var_value("startDate") == NULL ? $start['m_date'] : get_var_value("startDate");
		if($this->ip == -1){
			$this->ip = current($obj ->table("main_login")->field( 'distinct m_service')->order('m_service asc') -> find());
		}
		
		$list = $obj-> table("main_login")->order('m_date asc') -> where(array('m_date >='=>$startDate,"m_date <="=>$this->endDate))->select();
		
		$chartList = array();	
		$start = strtotime($startDate);
		$end = strtotime($this->endDate);
		if($list == '') {			//没有记录时，默认为0
			
			$n = 0;					
			for($i = $start; $i <= $end; $i = $i+86400) {
				$chartList[$n]['m_date'] = date('Y-m-d', $i);
				$chartList[$n]['m_creat'] = 0;
				$chartList[$n]['m_login'] = 0;
				$chartList[$n]['m_sametime'] = 0;
				$chartList[$n]['m_maxsametime'] = 0;
				$n++;
			}
			
		}else {
			//充值相关
			$end = $end+24*60*60;
			$pay = D("chongzhi")->table('chongzhi')
			-> where(array('c_ts >='=>$start,"c_ts <="=>$end,"c_sid"=>$this->ip,'c_state'=>2))
			->select();
			
			if (count($pay)>0){
				$arr = array();//玩家账号
				foreach ($pay as $k=>$v){
					$sit = date('Y-m-d',$v['c_ts']);
					if (!isset($payrmb[$sit])){
						$payrmb[$sit] = array('paynum'=>0,'paycount'=>0,'paymoney'=>0);
					}
					$payrmb[$sit]['paycount'] +=1;//充值次数
					$payrmb[$sit]['paymoney'] += $v['c_price']*$v['c_num'];//充值金额(RMB)
					if (!in_array($v['c_openid'], $arr[$sit])){
						$payrmb[$sit]['paynum'] +=1;//充值人数
						$arr[$sit][] = $v['c_openid'];
					}
				}
			}
			
			foreach($list as $k => $item) {				//赋值到原来数组，并覆盖原来值
				if (isset($payrmb[$item['m_date']])){
					$list[$k]['paycount'] = $payrmb[$item['m_date']]['paycount'];
					$list[$k]['paynum'] = $payrmb[$item['m_date']]['paynum'];
					$list[$k]['paymoney'] = $payrmb[$item['m_date']]['paymoney'];
					if ($payrmb[$item['m_date']]['paynum']>0){
						$list[$k]['arpu'] = round($payrmb[$item['m_date']]['paymoney']/$payrmb[$item['m_date']]['paynum'],2);
					}else {
						$list[$k]['arpu'] = 0;
					}
				}else {
					$list[$k]['paycount'] = 0;
					$list[$k]['paynum'] = 0;
					$list[$k]['paymoney'] = 0;
					$list[$k]['arpu'] = 0;
				}
			}
			
		}
		
		$total = $obj -> table("main_login")->where(array('m_date >='=>$startDate,"m_date <="=>$this->endDate))->total();
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,"formAjax","go","page");
		$pageHtml = $page->getPageHtml();
		$comb = array(
					'list'=>$list,
					'chartList' => $chartList,
					'startDate'=>$startDate,
					'endDate'=>$this->endDate,
					'ip'=>$this->ip,'total'=>$total,
					'curPage'=>$this->curPage,
					'pageSize' =>$this->pageSize,
					'pageHtml'=>$pageHtml
				);
 		echo json_encode($comb);
		exit(); 
	}
	
	/**
	 * ajax获取统计数据(用于表格)
	 */
	public function getJsonData(){
		$obj = D("game".$this->ip);
		if($this->ip == -1){
			$this->ip = current($obj -> table("main_login")->field( 'distinct m_service')->order('m_service asc') -> find());
		}
		$total = $obj->table("main_login")-> order('m_date asc') -> where(array('m_date >='=>$this->startDate,"m_date <="=>$this->endDate))->total();
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,"formAjax");
		$pageHtml = $page->getPageHtml();
		$list = $obj-> table("main_login")->order('m_date asc') -> where(array('m_date >='=>$this->startDate,"m_date <="=>$this->endDate))->limit(intval($page->getOff()),intval($this->pageSize))->select();
		if($list != '') {
			//充值相关
			$start = strtotime($this->startDate);
			$end = strtotime($this->endDate)+24*60*60;
			$end = $end+24*60*60;
			$pay = D("chongzhi")->table('chongzhi')
			-> where(array('c_ts >='=>$start,"c_ts <="=>$end,"c_sid"=>$this->ip,'c_state'=>2))
			->select();
			
			if (count($pay)>0){
				$arr = array();//玩家账号
				foreach ($pay as $k=>$v){
					$sit = date('Y-m-d',$v['c_ts']);
					if (!isset($payrmb[$sit])){
						$payrmb[$sit] = array('paynum'=>0,'paycount'=>0,'paymoney'=>0);
					}
					$payrmb[$sit]['paycount'] +=1;//充值次数
					$payrmb[$sit]['paymoney'] += $v['c_price']*$v['c_num'];//充值金额(RMB)
					if (!in_array($v['c_openid'], $arr[$sit])){
						$payrmb[$sit]['paynum'] +=1;//充值人数
						$arr[$sit][] = $v['c_openid'];
					}
				}
			}
			
			foreach($list as $k => $item) {				//赋值到原来数组，并覆盖原来值
				if (isset($payrmb[$item['m_date']])){
					$list[$k]['paycount'] = $payrmb[$item['m_date']]['paycount'];
					$list[$k]['paynum'] = $payrmb[$item['m_date']]['paynum'];
					$list[$k]['paymoney'] = $payrmb[$item['m_date']]['paymoney'];
					$list[$k]['arpu'] = round($payrmb[$item['m_date']]['paymoney']/$payrmb[$item['m_date']]['paynum'],2);
				}else {
					$list[$k]['paycount'] = 0;
					$list[$k]['paynum'] = 0;
					$list[$k]['paymoney'] = 0;
					$list[$k]['arpu'] = 0;
				}
			}
			
			$login_temp = $obj -> table("login_temp") -> where("l_date >= '".$this->startDate."' and l_date <= '".$this->endDate."'") -> select();	//先从缓存表取
			$login_result = array();
			$login_field = array('2' => 'l_two','3' => 'l_three', '5' => 'l_five','10' => 'l_ten','15' => 'l_fifteen');
			if($login_temp != '') {						//组装数据，以日期为键值
				foreach($login_temp as $l) {
					foreach($login_field as $field) {
						$login_result[$l['l_date']][$field] = $l[$field];
					}
				}
				
				
			}
		
			foreach($list as $k => $item) {
// 				if(isset($create_arr[$item['m_date']])) {
// 					$list[$k]['m_creat'] = $create_arr[$item['m_date']];
// 				}else {
// 					$list[$k]['m_creat'] = 0;
// 				}
				
				foreach ($login_field as $key => $val) {
					if(isset($login_result[$item['m_date']])) {
						$list[$k][$val] = $login_result[$item['m_date']][$val];
					}else {
						$list[$k][$val] = 0;
					}
				}
			}
		}
		$oriList = $obj-> table("main_login")-> order('m_date asc') -> where(array('m_date >='=>$this->startDate,"m_date <="=>$this->endDate))->select();
		foreach($oriList as $k => $item) {				//赋值到原来数组，并覆盖原来值
			if (isset($payrmb[$item['m_date']])){
				$oriList[$k]['paycount'] = $payrmb[$item['m_date']]['paycount'];
				$oriList[$k]['paynum'] = $payrmb[$item['m_date']]['paynum'];
				$oriList[$k]['paymoney'] = $payrmb[$item['m_date']]['paymoney'];
				$oriList[$k]['arpu'] = round($payrmb[$item['m_date']]['paymoney']/$payrmb[$item['m_date']]['paynum'],2);
			}else {
				$oriList[$k]['paycount'] = 0;
				$oriList[$k]['paynum'] = 0;
				$oriList[$k]['paymoney'] = 0;
				$oriList[$k]['arpu'] = 0;
			}
			
			foreach ($login_field as $key => $val) {
				if(isset($login_result[$item['m_date']])) {
					$oriList[$k][$val] = $login_result[$item['m_date']][$val];
				}else {
					$oriList[$k][$val] = 0;
				}
			}
		}
		$json = array(
					'list'=>$list,
					'oriList' => $oriList,
					'total'=>$total,
					'pageHtml'=>$pageHtml
				);
		echo json_encode($json);
		exit();
	}
	
	/**
	 * ajax获取实时数据
	 */
	public function getCurData(){
		$oriArr = array();						//分页数据
		$minArr =  array();						//原始数据
		$com = array();							//json数据
		
		$interval = get_var_value('interval');	//时间间隔(1:每分钟；2：每5分钟；3：每1小时)
		$enddate = get_var_value('endDate');
		$ip = get_var_value('ip');
		$obj = D('game_base');
		$s_ip = $obj ->table('servers')->where("s_id = {$ip}")->find();
		$file = LPATH.$s_ip['s_ip'].'/'.$enddate.'/log-type-3.log';
		// $file = file_get_contents($file);
		// $file = explode("\n", $file);
		$fp = fopen($file, "r");	
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
		fclose($fp);
		$arr = '';
		foreach($log_data as $key => $item){
			$arr[$key]['h_date'] = date('Y-m-d H:i:s',$item['time']);
			$arr[$key]['h_num'] = $item['num'];
		}
		echo json_encode(array('minArr'=>$arr,'olist'=>array_reverse($arr)));
		exit();
	}
	
	//用户每日平均在线时长
	public function getDaily(){
		$main = D("game".$this->ip);
		$list = $main-> table("main_login")->order('m_date asc') -> where(array('m_date >='=>$this->startDate,"m_date <="=>$this->endDate))->select();
		
		$chartList = array();
		if($list == '') {		//没有记录时默认为空
			$start = strtotime($this->startDate);
			$end = strtotime($this->endDate);
			
			$n = 0;					
			for($i = $start; $i <= $end; $i = $i+86400) {
				$chartList[$n]['m_date'] = date('Y-m-d', $i);
				$chartList[$n]['m_count'] = 0;
				$n++;
			}
		
		}
		
		echo json_encode(array('list' => $list, 'chartList' => $chartList));
		exit();
	}
	
	
	//日平均在线时长分布
	public function getDuration(){
		$result = array();
		$result["030"] = $result["3060"] = $result["12"] = $result["24"] = $result["48"] = $result["8m"] = 0;
		$online = D("game".$this->ip);
		if($this->startDate != $this->endDate){
			$endDate = date('Y-m-d', strtotime($this->endDate)+86400);
			$userList =  $online->table("online_sec")->where(array('o_date >='=>$this->startDate,"o_date <"=>$endDate))->order('o_date asc')->select();
		}else{ 			
			$userList =  $online->table("online_sec")->where(array('o_date like'=>$this->startDate."%"))->order('o_date asc')->select();
		}
		
		if(is_array($userList)) {
			$tem_arr = array();
			foreach($userList as $user){
				if(isset($tem_arr[$user['o_userid']]['seconds'])) {		//计算总时长
					$tem_arr[$user['o_userid']]['seconds'] +=  $user['o_second'];
				}else {
					$tem_arr[$user['o_userid']]['seconds'] = $user['o_second'];	
				}
					
				$date = substr($user['o_date'], 0, 10);
				if(!isset($tem_arr[$user['o_userid']]['date'])){
					$tem_arr[$user['o_userid']]['date'] = $date;
				}
				if(isset($tem_arr[$user['o_userid']]['days'])) {
					if($tem_arr[$user['o_userid']]['date'] != $date) {
						$tem_arr[$user['o_userid']]['days'] ++;
						$tem_arr[$user['o_userid']]['date'] = $date;
					}
				}else {
					$tem_arr[$user['o_userid']]['days'] = 1 ;
				}
			}
			
			if(!empty($tem_arr)) {
				foreach($tem_arr as $obj) {
					$sum = $obj['seconds'];
					$days = $obj['days'];
					if($days == 0) continue;
					$average = floor($sum/$days);
					if($average >= 0 && $average < 1800){
						$result["030"] += 1;
					}else if($average >= 1800 && $average < 3600){
						$result["3060"] += 1;
					}else if($average >= 3600 && $average < 7200){
						$result["12"] += 1;
					}else if($average >= 7200 && $average < 14400){
						$result["24"] += 1;
					}else if($average >= 14400 && $average < 28800){
						$result["48"] += 1;
					}else if($average >= 28800){
						$result["8m"] += 1;
					}
				}
			}
			
		}
		echo json_encode($result);
		exit();
	}
	
	/**
	 * 导出excel
	 */
	public function writeExcel(){
		$obj = D("game".$this->ip);
		if($this->ip == -1){
			$this->ip = current($obj ->table("main_login")->field( 'distinct m_service')->order('m_service asc') -> find());
		}
		$list = $obj-> table("main_login")->order('m_date asc') -> where(array('m_date >='=>$this->startDate,"m_date <="=>$this->endDate))->select();
		if($list != '') {
			$create_arr = array();	
			$create  = $obj-> table("createplay") ->select();
			if($create != '') {
				foreach($create as $item) {
					$create_arr[$item['c_date']] = $item['c_csuccess'];
				}
			}
			
			$login_temp = $obj -> table("login_temp") -> where("l_date >= '".$this->startDate."' and l_date <= '".$this->endDate."'") -> select();	//先从缓存表取
			$login_result = array();
			$login_field = array('2' => 'l_two','3' => 'l_three', '5' => 'l_five','10' => 'l_ten','15' => 'l_fifteen');
			if($login_temp != '') {						//组装数据，以日期为键值
				foreach($login_temp as $l) {
					foreach($login_field as $field) {
						$login_result[$l['l_date']][$field] = $l[$field];
					}
				}
				
				
			}
		
			foreach($list as $k => $item) {
				$list[$k]['m_count'] = $this->toformat($list[$k]['m_count']);
				$list[$k]['m_maxtime'] = $this->toformat($list[$k]['m_maxtime']);
				if(isset($create_arr[$item['m_date']])) {
					$list[$k]['m_creat'] = $create_arr[$item['m_date']];
				}else {
					$list[$k]['m_creat'] = 0;
				}
				
				foreach ($login_field as $key => $val) {
					if(isset($login_result[$item['m_date']])) {
						$list[$k][$val] = $login_result[$item['m_date']][$val];
					}else {
						$list[$k][$val] = 0;
					}
				}
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '时间');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '创号数');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '登录数');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '登录总数');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '登录IP数');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '≥2登');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '≥3登');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '≥5登');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', '≥10登');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', '≥15登');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', '平均在线时长');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', '最高在线时长');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', '平均同时在线人数');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', '最高同时在线人数');
		
		if (is_array($list)) {
			foreach($list as $k => $item){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.($k+2), $item["m_date"])
				->setCellValue('B'.($k+2), $item["m_creat"])
				->setCellValue('C'.($k+2), $item["m_login"])
				->setCellValue('D'.($k+2), $item["m_login_sum"])
				->setCellValue('E'.($k+2), $item["m_ip_num"])
				->setCellValue('F'.($k+2), $item["l_two"])
				->setCellValue('G'.($k+2), $item["l_three"])
				->setCellValue('H'.($k+2), $item["l_five"])
				->setCellValue('I'.($k+2), $item["l_ten"])
				->setCellValue('J'.($k+2), $item["l_fifteen"])
				->setCellValue('K'.($k+2), $item["m_count"])
				->setCellValue('L'.($k+2), $item["m_maxtime"])
				->setCellValue('M'.($k+2), $item["m_sametime"])
				->setCellValue('N'.($k+2), $item["m_maxsametime"]);
			}	
		}	

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

		$objPHPExcel->setActiveSheetIndex(0);

		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="登录概况.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;

	}
	
	/**
	 * 将单位为秒的时间转化成HH:mm:ss的格式
	 */
	private function toformat($s) {
		$hour = 0;
		$min = 0;
		$second = 0;
		$delimiter = ":";
		if ($s >= 3600) {      			//小时    
			$hour = floor($s/3600);
			$s = $s - 3600 * $hour;	
		} 
		if ($s >= 60 && $s < 3600) {  	//分钟	     
			$min = floor($s/60);
			$s = $s - 60 * $min;	
		}

		$second = $s;					//秒
		$format = "%1$02d".$delimiter."%2$02d".$delimiter."%3$02d";	
		return sprintf($format, $hour, $min, $second);                     
	}
}