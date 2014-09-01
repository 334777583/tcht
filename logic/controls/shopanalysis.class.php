<?php
/**
 * FileName: shopanalysis.class.php
 * Description:商城消费分析
 * Author: xiaoliao
 * Date:2013-12-12 10:09:51
 * Version:1.00
 */
class shopanalysis{
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
			if(!in_array('00300100', $this->user['code'])){
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
	 * FunctionName: getGoods
	 * Description: 获取商城消费分析物品情况
	 * Author: （jan）						
	 * Date: 2013-9-4 10:58:20	
	 **/
	public function getGoods(){
		$obj = D("game".$this -> ip);
		$listdate = $obj -> table('item_detail') -> field('i_date') -> order('i_date asc') -> limit(0,1) -> find();
		$list_date = isset($listdate['i_date'])? date('Y-m-d',strtotime($listdate['i_date'])) : date("Y-m-d",strtotime("-7 day"));//如果表里没数据 默认7天前
		$startdate = get_var_value('startdate') == NULL? $list_date : get_var_value('startdate');
		
		
		$total = 0;
		$list = '';
		$total_list = array();
			$list = $obj->fquery("SELECT SUM(i.i_num) as cnum, sum(i.i_total) as cid,SUM(i.i_price) as c_price,i.i_price,t.t_name,t.t_code FROM item_detail as i LEFT JOIN tools_detail as t ON i.i_shopid=t.t_code WHERE i.i_type={$this->type} AND i.i_date BETWEEN '{$this->startdate} 00:00:00' AND '{$this->enddate} 23:59:59' GROUP BY i.i_shopid");
			$total_list[0]['cnum']=0;
			$total_list[0]['c_price']=0;
			$total_list[0]['cid']=0;
			if(!empty($list)){
				foreach($list as $key =>$value){
					$total_list[0]['cnum'] += $value['cnum'];
					$total_list[0]['c_price'] += $value['cnum']*$value['i_price'];
					$total_list[0]['cid'] += $value['cid'];
				}
			}	
		$result = array(
					'list' => $list,
					'total_list'=>$total_list,
					'startDate'=>$startdate
				);
				
		echo json_encode($result);exit;
		//}		
	}

	public function writeExcel(){
		$obj = D("game".$this -> ip);
		$listdate = $obj -> table('item_detail') -> field('i_date') -> order('i_date asc') -> limit(0,1) -> find();
		$list_date = isset($listdate['i_date'])? date('Y-m-d',strtotime($listdate['i_date'])) : date("Y-m-d",strtotime("-7 day"));//如果表里没数据 默认7天前
		$startdate = get_var_value('startdate') == NULL? $list_date : get_var_value('startdate');

		$total = 0;
		if ($this->enddate == $this->startdate) {	//选择一天的时候
			
			$a = ($this->curPage - 1) * $this->pageSize;
			$b = $this->pageSize;
			$list = $obj->fquery("SELECT SUM(i.i_num) as cnum, sum(i.i_total) as cid,SUM(i.i_price) as c_price,i.i_price,t.t_name,t.t_code FROM item_detail as i LEFT JOIN tools_detail as t ON i.i_shopid=t.t_code WHERE i.i_type={$this->type} AND i.i_date BETWEEN '{$this->startdate} 00:00:00' AND '{$this->startdate} 23:59:59' GROUP BY i.i_shopid");

			$total_list = $obj->fquery("SELECT SUM(i.i_num) as cnum, sum(i.i_total) as cid,SUM(i.i_price) as c_price FROM item_detail as i LEFT JOIN tools_detail as t ON i.i_shopid=t.t_code WHERE i.i_type={$this->type} AND i.i_date BETWEEN '{$this->startdate} 00:00:00' AND '{$this->startdate} 23:59:59'");

			$total = $obj ->fquery("SELECT sum(i.i_total) as cid FROM item_detail as i LEFT JOIN tools_detail as t ON i.i_shopid=t.t_code WHERE i.i_type={$this->type} AND i.i_date BETWEEN '{$this->startdate} 00:00:00' AND '{$this->startdate} 23:59:59'");	
			
		} else {	//选择时间区间
			$a = ($this->curPage - 1) * $this->pageSize;
			$b = $this->pageSize;
			$list = $obj->fquery("SELECT SUM(i.i_num) as cnum, sum(i.i_total) as cid,SUM(i.i_price) as c_price,i.i_price,t.t_name,t.t_code FROM item_detail as i LEFT JOIN tools_detail as t ON i.i_shopid=t.t_code WHERE i.i_type={$this->type} AND i.i_date BETWEEN '{$this->startdate} 00:00:00' AND '{$this->enddate} 23:59:59' GROUP BY i.i_shopid");

			$total_list = $obj->fquery("SELECT SUM(i.i_num) as cnum, sum(i.i_total) as cid,SUM(i.i_price) as c_price FROM item_detail as i LEFT JOIN tools_detail as t ON i.i_shopid=t.t_code WHERE i.i_type={$this->type} AND i.i_date BETWEEN '{$this->startdate} 00:00:00' AND '{$this->enddate} 23:59:59'");
			
			$total = $obj ->fquery("SELECT sum(i.i_total) as cid FROM item_detail as i LEFT JOIN tools_detail as t ON i.i_shopid=t.t_code WHERE i.i_type={$this->type} AND i.i_date BETWEEN '{$this->startdate} 00:00:00'AND '{$this->enddate} 23:59:59'");
					 			
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
		//$objPHPExcel->getActiveSheet()->mergeCells('A1:A2', '时间');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '物品ID');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '物品名称');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '购买人数');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '单价');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '数量');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '数量比');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '总价');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '总价比');
		
		//$DataType = PHPExcel_Cell_DataType::TYPE_STRING;//科学型 改成字符串型

			for ($i=0; $i < count($list); $i++) { 
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.($i+2), $list[$i]['t_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.($i+2), $list[$i]['t_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.($i+2), $list[$i]['cid']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.($i+2), $list[$i]['i_price']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.($i+2), $list[$i]['cnum']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.($i+2), round($list[$i]['cnum']/$total_list[0]['cnum']*100,2).' %');
				$objPHPExcel->getActiveSheet()->setCellValue('G'.($i+2), $list[$i]['c_price']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.($i+2), round(($list[$i]['c_price']/$total_list[0]['c_price'])*100,2));
			}
		

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "商城消费分析_".date('Y_m_d H_i_s');
			
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');


			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;	
	}
	
	 /**
	 * FunctionName: getGoods
	 * Description: 获取每天商城消费情况
	 * Author: （jan）						
	 * Date: 2013-9-5 15:58:20	
	 **/
	public function getSumGoods(){
		$obj = D("game".$this -> ip);

		$total = 0;
		$listdate = $obj -> table('item_detail') -> field('i_date') -> order('i_date asc') -> limit(0,1) -> find();
		$list_date = isset($listdate['i_date'])? date('Y-m-d',strtotime($listdate['i_date'])) : date("Y-m-d",strtotime("-7 day"));//如果表里没数据 默认7天前
		$startdate = get_var_value('startdate') == NULL? $list_date : get_var_value('startdate');

		//去除重复获取最新数据
		$list = $obj ->fquery("SELECT sum(i_total) cid,SUM(i_num) cnum,SUM(i_price * i_num) cprice,date_format(i_date,'%Y-%m-%d') time FROM item_detail WHERE i_type={$this->type} AND i_date BETWEEN '{$this->startdate} 00:00:00' AND '{$this->enddate} 23:59:59' GROUP BY date_format(i_date,'%Y-%m-%d')");
		$sql ="select count(cid) as cid , i_date from (select count(i_id) as cid,left(i_date,10) as i_date from item where i_type={$this->type} AND i_date BETWEEN '{$startdate} 00:00:00' AND '{$this->enddate} 23:59:59' GROUP BY left(i_date,10) ,i_playid) as m group by i_date";
		$num = $obj -> fquery($sql);
		$sumList = $obj->fquery("SELECT sum(i_total) cid, SUM(i_num) cnum, SUM(i_price * i_num) cprice FROM item_detail WHERE i_type={$this->type} AND i_date BETWEEN '{$this->startdate} 00:00:00' AND '{$this->enddate} 23:59:59'");
		$sum = '';
		if(!empty($list)){
			foreach ($list as $key => $value) {
				if(!empty($num[$key])){
					if($list[$key]['time'] == $num[$key]['i_date'] ){
						$list[$key]['cid'] = $num[$key]['cid'];
					}
				}
				$sum += $list[$key]['cid'];
			
				if ($key > 0) {
					if ($list[$key]['cid'] >= $list[($key-1)]['cid']) {
						$list[$key]['cid_s'] = 1;
					}elseif($list[$key]['cid'] < $list[($key-1)]['cid']){
						$list[$key]['cid_s'] = 2;
					}

					if ($list[$key]['cnum'] >= $list[($key-1)]['cnum']) {
						$list[$key]['cnum_s'] = 1;
					}elseif($list[$key]['cnum'] < $list[($key-1)]['cnum']){
						$list[$key]['cnum_s'] = 2;
					}

					if ($list[$key]['cprice'] >= $list[($key-1)]['cprice']) {
						$list[$key]['cprice_s'] = 1;
					}elseif($list[$key]['cprice'] < $list[($key-1)]['cprice']){
						$list[$key]['cprice_s'] = 2;
					}
				}
				
			}
		}else{
			$list = array();
		}
		//$sum = '';
		if(!empty($sum)){
			$sumList[0]['cid'] = $sum;
		}
		if( empty($sumList) ){
			$sumList = array();
		}				
		
		
					  		 
		
		$result = array(
					'list' => $list,
					'sumList' => $sumList 		
				);
				
		echo json_encode($result);
		exit;
		
	}

	public function analy(){
		
		ini_set('upload_max_filesize','10M');
		ini_set('post_max_size','10M');
		$id = get_var_value('ip');
		if(!$id) {return ;}
		$tool_string = '';			//道具列表
		//$basepath = TPATH.'brophp/public/uploads/';
		$filepath = TPATH.'/goods_detail.xlsx';//更新道具列表路径
		//print_R($filepath);
		if(file_exists($filepath)) {
			$xml = $this -> ReadExcel($filepath);
			
			if($xml) {
				foreach($xml as $item => $val) {
					//$tid = $item;				//ID
					if (!$val['t_code'] =='') {
						$t_code = $val['t_code'];			//物品id
						$name = $val['name'];		//物品名称
						$type1 = $val['type1'];
						$type2 = $val['type2'];
						$type3 = $val['type3'];
						$tool_string .= "({$t_code},'{$name}',{$type1},{$type2},{$type3}),";
					}
				}
				
				if($tool_string != '') {
					$tool_string = rtrim($tool_string, ',');
					$tool_string .= ';';
					
					if($id) {
						$status = $this->update($tool_string, $id);
						if($status) {
							//unlink($filepath);//上传完毕  清除文件
							echo json_encode('success');
							exit;
						} else {
							echo json_encode('fail');
							exit;
						}	
					}else {
						echo json_encode('fail');
						exit;
					}
				}
			}
		} else {
			echo json_encode('File is not find!');
			exit;
		}
	}

	private function ReadExcel($path){
	
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
			$result[$j]['t_code'] = $PHPExcel->getActiveSheet()->getCell("B".$j)->getValue();//获取道具id
			$result[$j]['name'] = $PHPExcel->getActiveSheet()->getCell("C".$j)->getValue();//获取道具名
			$result[$j]['type1'] = $PHPExcel->getActiveSheet()->getCell("H".$j)->getValue();//type1
			$result[$j]['type2'] = $PHPExcel->getActiveSheet()->getCell("I".$j)->getValue();//type2
			$result[$j]['type3'] = $PHPExcel->getActiveSheet()->getCell("K".$j)->getValue();//type3
		}
		return $result;
		exit;
	}

	private function update($data, $id){
		$db = D('game_base');
		$db->fquery("delete FROM goods_detail");
		
		$sql = 'insert into goods_detail_detail(t_code,t_name,t_type1,t_type2,t_type3) values ' .$data;
		
		$f = $db->rquery($sql);
		if(!$f) 
			return false;
		else	
			return true;
	}
	
	
	/**
	* 获取商城消费每天物品的消费数据，用作图表展示
	*/
	public function getChartData() {
		$obj = D("game".$this -> ip);
		$listdate = $obj -> table('item_detail') -> field('i_date') -> order('i_date asc') -> limit(0,1) -> find();
		$list_date = isset($listdate['i_date'])? date('Y-m-d',strtotime($listdate['i_date'])) : date("Y-m-d",strtotime("-7 day"));//如果表里没数据 默认7天前
		$startdate = get_var_value('startdate') == NULL? $list_date : get_var_value('startdate');
		$result = array();				//返回的结果集
		//$arr_by_date = array();		//以日期为键值组装数据
		
		// $list = $obj -> table('item')
		// 			 -> where( array('i_type' => $this->type, 'left(i_date,10) >= ' => $this->startdate,'left(i_date,10) <= '=> $this->enddate) )
		// 			 -> order('date_format(i_date,"%Y-%m-%f") asc')
		// 			 -> select();
		$enddate = date('Y-m-d',strtotime($this->enddate) + 24*3600);
		$list = $obj->fquery("SELECT sum(i_num) as i_num,i_shopid,date_format(i_date,'%Y-%m-%d') ii_date FROM item_detail WHERE i_type={$this->type} AND i_date > '{$this->startdate}' AND i_date < '{$enddate}' GROUP BY left(i_date,10),i_shopid ORDER BY date_format(i_date,'%Y-%m-%d') ASC");
		if($list != '') {
			foreach($list as $item) {
				//if(isset($result[$item['ii_date']][$item['i_shopid']])) {	//存在则累加
					//$result[$item['ii_date']][$item['i_shopid']] += $item['i_num'];
				// } else {												//不存在则初始化		
					$result[$item['ii_date']][$item['i_shopid']] = $item['i_num'];
				// }
			}
			foreach($result as $date => $item) {						//计算百分比小于1%的归类为其他
				$sum  = 0; 												//日期对应的商品总数
				foreach($item as $good) {
					$sum += $good;
				}
				if($sum != 0) {
					foreach($item as $id => $good) {
						if($good/$sum < 0.01) {
							if(isset($result[$date]['other'])) {
								$result[$date]['other'] += $good;
							} else {
								$result[$date]['other'] = $good;
							}
							unset($result[$date][$id]);
						}
					}
				}
			}
		}
		
		
		$goods = $obj -> table('tools_detail') -> select();
		$goods_arr = array();
		if(!empty($goods)){
			foreach($goods as $val) {
				$goods_arr[$val['t_code']] = $val['t_name'];
			}
		}
		$goods_arr['other'] = '其他';
		echo json_encode(array(
				'result' => $result,
				'goods_arr' => $goods_arr,
				'startDate'=>$startdate
		));
	}
	
	/**
	 * 实时商城
	 */
	public function shishi(){
		$enddate = date('Y-m-d');
		$ip = get_var_value('ip');
		$obj = D('game_base');
		$point = D('game'.$ip);
		$s_ip = $obj ->table('servers')->where("s_id = {$ip}")->find();
		$file = LPATH.$s_ip['s_ip'].'/'.$enddate.'/log-type-6.log';
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
		if(!empty($log_data)){
			$point->rquery('DELETE from mall');
		}else{
			echo 1;
		}
		$resultcount = count($log_data);
		$ins_data = "insert into mall(i_playid,i_shopid,i_price,i_type,i_num,i_dtype,i_date) values ";
		$n = 500;
		$state = 1;
		//每次插入800条数据
		for($i = 0 ;$i < $resultcount ; $i++){
			$pid = $log_data[$i]['playid']; 		//角色id
			$cid = $log_data[$i]['commodityid'];	//商品id
			$price = $log_data[$i]['price'];		//单价
			$p_type = $log_data[$i]['price_type'];	//钱类型1：元宝，2：绑定元宝，3：铜币，4：绑定铜币
			$num = $log_data[$i]['num'];			//数量
			$act = $log_data[$i]['activity'];		//类型 1：普通购买，2：活动购买
			$date = date('Y-m-d H:i:s' ,$log_data[$i]['time']);		//时间
			
			$ins_data .= "('" . $pid . "','" . $cid . "','" . $price . "','" . $p_type . "','" . $num . "','" . $act . "','" . $date ."'),";
			// if(($i % $n) == 0){
				// $ins_data = rtrim($ins_data, ',');
				// $ins_data .= ';';
				// $ins_str = $point->rquery($ins_data);
				// if(!$ins_str){//添加失败
					// echo 0;
					// print_R($ins_data);
					// exit;
				// }
				// $ins_data = "insert into item(i_playid,i_shopid,i_price,i_type,i_num,i_dtype,i_date) values ";
			// }
		}
		$ins_data = rtrim($ins_data, ',');
		$ins_data .= ';';
		$ins_str = $point->rquery($ins_data);
		
		
		$itsql = "select count(i_id) as total, sum(i_num) as coun_n ,i_shopid,i_price,i_type,i_dtype,i_date from mall group by i_shopid ,i_type ,i_dtype";
		$itlist = $point ->fquery($itsql);
		if(!empty($itlist)){
			$point->rquery('DELETE from mall_detail');
			$count = count($itlist);
			$item_data = "insert into mall_detail(i_total,i_shopid,i_price,i_type,i_num,i_dtype,i_date) values ";
			for($i = 1 ;$i < $count ; $i++){
				$total = $itlist[$i]['total'];   //购买人数
				$commodityid = $itlist[$i]['i_shopid']; 		//角色id
				$price = $itlist[$i]['i_price'];	//商品id
				$price_type = $itlist[$i]['i_type'];		//钱类型1：铜币，2：绑定铜币，3：元宝，4：绑定元宝 5：礼券
				$num = $itlist[$i]['coun_n'];	//
				$activity = $itlist[$i]['i_dtype'];	//活动类型
				$time = $itlist[$i]['i_date'];	
				$item_data .= '("'.$total.'","'.$commodityid.'","'.$price.'","'.$price_type.'","'.$num.'","'.$activity.'","'.$time.'"),';
				// if(($i % 500) == 0){
					// $item_data = rtrim($item_data, ',');
					// $item_data .= ';';	
					// $item_str = $point->rquery($item_data);
					// if(!$item_str){//添加失败
						// echo 1;
						// exit;
					// }
					// $item_data = "insert into mall_detail(i_total,i_shopid,i_price,i_type,i_num,i_dtype,i_date) values ";
				// }
			}
			$item_data = rtrim($item_data, ',');
			$item_data .= ';';
			
			$item_str = $point->rquery($item_data);
			if(!$item_str){//添加失败
				echo 2;
				exit;
			}else{
				echo json_encode('success');
			}
		}else{
			echo 1;
		}
		
	}
	
	public function nowsumgoods(){
		$obj = D("game".$this -> ip);

		$total = 0;
		
		//去除重复获取最新数据
		$list = $obj ->fquery("SELECT sum(i_total) cid,SUM(i_num) cnum,SUM(i_price) cprice,date_format(i_date,'%Y-%m-%d') time FROM mall_detail WHERE i_type={$this->type} GROUP BY date_format(i_date,'%Y-%m-%d')");
		$cid = $obj ->fquery('select count(i_id) cid from (select * from mall group by i_playid ) as m');
		//$sumList = $obj->fquery("SELECT sum(i_total) cid, SUM(i_num) cnum, SUM(i_price) cprice FROM mall_detail WHERE i_type={$this->type}");
					
		if(!empty($list)){
			foreach ($list as $key => $value) {
				if ($key > 0) {
					if ($list[$key]['cid'] >= $list[($key-1)]['cid']) {
						$list[$key]['cid_s'] = 1;
					}elseif($list[$key]['cid'] < $list[($key-1)]['cid']){
						$list[$key]['cid_s'] = 2;
					}

					if ($list[$key]['cnum'] >= $list[($key-1)]['cnum']) {
						$list[$key]['cnum_s'] = 1;
					}elseif($list[$key]['cnum'] < $list[($key-1)]['cnum']){
						$list[$key]['cnum_s'] = 2;
					}

					if ($list[$key]['cprice'] >= $list[($key-1)]['cprice']) {
						$list[$key]['cprice_s'] = 1;
					}elseif($list[$key]['cprice'] < $list[($key-1)]['cprice']){
						$list[$key]['cprice_s'] = 2;
					}
				}
			}
		}else{
			$list = array();
		}
		
		// if( empty($sumList) ){
			// $sumList = array();
		// }				
	
		
		$list[0]['cid']=$cid[0]['cid'];		  		 
		
		$result = array(
					'list' => $list,
					'sumList' => $list,	
				);
				
		echo json_encode($result);
		exit;
	}
	
	public function now_goods(){
		$obj = D("game".$this -> ip);
		
		$total = 0;
		$list = '';
		$total_list = array();
		
		$list = $obj->fquery("SELECT SUM(i.i_num) as cnum, sum(i.i_total) as cid,SUM(i.i_price) as c_price,i.i_price,t.t_name,t.t_code FROM mall_detail as i LEFT JOIN tools_detail as t ON i.i_shopid=t.t_code WHERE i.i_type={$this->type} GROUP BY i.i_shopid");
		$total_list[0]['cnum']=0;
		$total_list[0]['c_price']=0;
		$total_list[0]['cid']=0;
		if(!empty($list)){
			foreach($list as $key =>$value){
				$total_list[0]['cnum'] += $value['cnum'];
				$total_list[0]['c_price'] += $value['c_price'];
				$total_list[0]['cid'] += $value['cid'];
			}
		}		 			
		
		$result = array(
					'list' => $list,
					'total_list'=>$total_list,
					'endDate' => date('Y-m-d')
				);
				
		echo json_encode($result);
		exit;
	}


	

}