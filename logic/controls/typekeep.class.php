<?php
/**
 * FileName: typekeep.class.php
 * Description:渠道留存
 * Author: jan
 * Date:2014-01-21 10:09:51
 * Version:1.00
 */
class typekeep{
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
			if(!in_array('00401500', $this->user['code'])){
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
	 * FunctionName: keepList
	 * Description: 获取玩家留存情况
	 * Author: （jan）						
	 * Date: 2013-9-4 10:58:20	
	 **/
	public function keepList(){
		
		$obj = D("game".$this -> ip);
		$start = $obj->field('d_date')->table('detail_login')->order('d_date asc')->limit(0,1)->find();
		$enddate = get_var_value('enddate') == NULL ? date('Y-m-d',strtotime($start['d_date'])) : get_var_value('enddate');
		//if($ip && $startdate) {
			$result = array();			//统计结果
			$data = array();			//返回给页面的结果（经过组装后）
			if($enddate < date("Y-m-d")) {					
				$list = $obj -> table('detail_login') -> where('left(d_date, 10) = "'.$enddate.'"') ->group('d_userid') -> select();	//该天新增角色
				
				if($list != '') {
					$sum = count($list);				//新增总数
					$new_ids = '(';
					foreach($list as  $item) {
						$new_ids .= '"'.$item['d_userid'] .'",';
					}
					$new_ids = rtrim($new_ids, ',');
					$new_ids .= ')'; 	

					$two_date = date('Y-m-d', (strtotime($enddate) + 86400));
					$three_date = date('Y-m-d', (strtotime($enddate) + 86400*2));	
					$seven_date = date('Y-m-d', (strtotime($enddate) + 86400*6));	
					$tweek_date = date('Y-m-d', (strtotime($enddate) + 86400*13));	
					// $allsql ='select * from (select * from online_sec where left(o_date,10) > "' . $enddate  . '" and left(o_date,10) <= "'. $tweek_date . '" and o_userid in ' . $new_ids .'order by o_date desc ) as temp group by o_userid,left(o_date,10)';
					$allsql ='select * from (select * from online_sec where o_userid in ' . $new_ids .'order by o_date desc ) as temp group by o_userid,left(o_date,10)';
					// $sql ="select o.o_id , u.u_stid from online_sec o join user_list u on o.o_userid = u.u_plid group by o.o_user where o_date between '".$enddate."' and '".$tweek_date."'";
					 $sql ="select o.o_id ,o.o_date ,u.u_stid from (select * from online_sec where o_userid in ". $new_ids ."order by o_date desc ) o join user_list u on o.o_userid = u.u_plid group by o.o_userid";
					$all = $obj -> table('online_sec') -> fquery($sql);
					$keeper = '';
					if($all != '') {
						foreach($all as $item) {
							$a_date = substr($item['o_date'], 0, 10);
							$keeper[$item['u_stid']] = $item;
							$keeper[$item['u_stid']]['tyid'] = $item['u_stid'];
							$keeper[$item['u_stid']]['count'] = $sum;
							if($a_date == $two_date) {
								$keeper[$item['u_stid']]['two'] += count($a_date);
								$keeper[$item['u_stid']]['ptwo']= round($keeper[$item['u_stid']]['two']/$sum*100,2).'%';
							}else {
								$keeper[$item['u_stid']]['two'] =0;
								$keeper[$item['u_stid']]['ptwo']= '0%';
								}
							if($a_date == $three_date) {
								$keeper[$item['u_stid']]['thr'] += count($a_date);
								$keeper[$item['u_stid']]['pthr']= round($keeper[$item['u_stid']]['thr']/$sum*100,2).'%';
							}else {
								$keeper[$item['u_stid']]['thr'] =0;
								$keeper[$item['u_stid']]['pthr']= '0%';
							}
							if($a_date == $seven_date) {
								$keeper[$item['u_stid']]['sev'] += count($a_date);
								$keeper[$item['u_stid']]['psev']= round($keeper[$item['u_stid']]['sev']/$sum*100,2).'%';
							}else {
								$keeper[$item['u_stid']]['sev'] =0;
								$keeper[$item['u_stid']]['psev']= '0%';
							}
							if($a_date == $tweek_date) {
								$keeper[$item['u_stid']]['twee'] += count($a_date);
								$keeper[$item['u_stid']]['ptwee']= round($keeper[$item['u_stid']]['twee']/$sum*100,2).'%';
							}else{
								$keeper[$item['u_stid']]['twee'] =0;
								$keeper[$item['u_stid']]['ptwee']= '0%';
							} if($a_date >= $two_date && $a_date <= $tweek_date ){
								$keeper[$item['u_stid']]['week_to'] += count($a_date);
								$keeper[$item['u_stid']]['ptwesuv']= round($keeper[$item['u_stid']]['week_to']/$sum*100,2).'%';
								$keeper[$item['u_stid']]['twesuv'] = round($keeper[$item['u_stid']]['week_to'] / 14,2);
							}else{
								$keeper[$item['u_stid']]['ptwesuv']= '0%';
								$keeper[$item['u_stid']]['twesuv'] = 0;
							}
							
						
						}
						
					}else{
						$keeper[0]['two'] =0;
						$keeper[0]['ptwo']= '0%';
						$keeper[0]['thr'] =0;
						$keeper[0]['pthr']= '0%';
						$keeper[0]['sev'] =0;
						$keeper[0]['psev']= '0%';
						$keeper[0]['twee'] =0;
						$keeper[0]['ptwee']= '0%';
						$keeper[0]['ptwesuv']= '0%';
						$keeper[0]['twesuv'] = 0;
						$keeper[0]['tyid'] = 0;
						if(!empty($sum)){
							$keeper[0]['count'] = $sum;
						}else{
							$keeper[0]['count'] = 0;
						}
					}
					
				}else{
						$keeper[0]['two'] =0;
						$keeper[0]['ptwo']= '0%';
						$keeper[0]['thr'] =0;
						$keeper[0]['pthr']= '0%';
						$keeper[0]['sev'] =0;
						$keeper[0]['psev']= '0%';
						$keeper[0]['twee'] =0;
						$keeper[0]['ptwee']= '0%';
						$keeper[0]['ptwesuv']= '0%';
						$keeper[0]['twesuv'] = 0;
						$keeper[0]['tyid'] = 0;
						$keeper[0]['count'] = 0;
					}
			}	
			echo json_encode(array('list' => $keeper,'endDate'=>$enddate));
			exit;
		// } else {
			// echo '1';
		// }
		/*$endd = '2013-01-01';
		
		$use= "select * from user_list group by u_stid , u_account"; //用户去重
		$uselist = "select * from user where u_date ";
		$uselist .="between '" .$endd."' and '". date('Y-m-d',strtotime("$enddate,+1 days"))."' ";
		
		$cresql = "select count(l.u_plid) as coco,l.u_stid,l.u_plid from (".$use.") l inner join (".$uselist.") u on l.u_account = u.u_username group by l.u_stid ";
		$cretotal = "select count(l.u_plid) as coco from (".$use.") l inner join (".$uselist.") u on l.u_account = u.u_username ";
		//$count = "select count(u_id) as tot from (".$uselist.") as co group by u_stid";
		//次日留存
		$twdate =  " between '".date('Y-m-d',strtotime("$endd,+1 days"))."' and '". date('Y-m-d',strtotime("$enddate,+2 days"))."'";
		$twosql = "select count('d.d_userid') cuser ,l.u_stid from detail_login d , ({$uselist}) u ,user_list l where d.d_user = u.u_username and d.d_userid =l.u_plid and d.d_date ".$twdate." GROUP BY l.u_stid";
		$twototal = "select count('d.d_userid') cuser from detail_login d , ({$uselist}) u ,user_list l where d.d_user = u.u_username and d.d_userid =l.u_plid and d.d_date ".$twdate ;
		
		//3日留存
		$thrate = " between '".date('Y-m-d',strtotime("$endd,+2 days"))."' and '". date('Y-m-d',strtotime("$enddate,+3 days"))."'";
		$threesql = "select count('d.d_userid') cuser ,l.u_stid from detail_login d , ({$uselist}) u ,user_list l where d.d_user = u.u_username and d.d_userid =l.u_plid and d.d_date ".$thrate." GROUP BY l.u_stid";
		$threestotal = "select count('d.d_userid') cuser from detail_login d , ({$uselist}) u ,user_list l where d.d_user = u.u_username and d.d_userid =l.u_plid and d.d_date ".$thrate ;
		
		//7日留存
		$sevdate = " between '".date('Y-m-d',strtotime("$endd,+6 days"))."' and '". date('Y-m-d',strtotime("$enddate,+7 days"))."'";
		$sevensql = "select count('d.d_userid') cuser ,l.u_stid from detail_login d , ({$uselist}) u ,user_list l where d.d_user = u.u_username and d.d_userid =l.u_plid and d.d_date ".$sevdate." GROUP BY l.u_stid ";
		$seventotal = "select count('d.d_userid') cuser from detail_login d , ({$uselist}) u ,user_list l where d.d_user = u.u_username and d.d_userid =l.u_plid and d.d_date ".$sevdate ;
		
		//两周留存
		$tweekdate = " between '".date('Y-m-d',strtotime("$endd,+13 days"))."' and '". date('Y-m-d',strtotime("$enddate,+14 days"))."'";
		$tweeksql = "select count('d.d_userid') cuser ,l.u_stid from detail_login d , ({$uselist}) u ,user_list l where d.d_user = u.u_username and d.d_userid =l.u_plid and d.d_date ".$tweekdate." GROUP BY l.u_stid";
		$tweektotal = "select count('d.d_userid') cuser from detail_login d , ({$uselist}) u ,user_list l where d.d_user = u.u_username and d.d_userid =l.u_plid and d.d_date ".$tweekdate ;
		
		$cre = $obj->fquery($cresql);
		$two = $obj->fquery($twosql);
		$thr = $obj->fquery($threesql);
		$sev = $obj->fquery($sevensql);
		$tweek = $obj->fquery($tweeksql);
		
		$tocre = $obj->fquery($cretotal);
		$totwo = $obj->fquery($twototal);
		$tothr = $obj->fquery($threestotal);
		$tosev = $obj->fquery($seventotal);
		$totweek = $obj->fquery($tweektotal);
		
		$keeper = array();
		if(is_array($cre)){
		foreach($cre as $key => $value){
			$keeper[$key]['tyid'] = $cre[$key]['u_stid'];
			$keeper[$key]['count'] = $cre[$key]['coco'];
			$keeper[0]['ctotal'] = $tocre[0]['coco'];
			
			if($two[$key] && $two[$key]['u_stid'] = $cre[$key]['u_stid']){
				$keeper[$key]['two'] = $two[$key]['cuser'];
			}else{
				$keeper[$key]['two'] = 0 ;
			}
			if($thr[$key] && $thr[$key]['u_stid'] = $cre[$key]['u_stid']){
				$keeper[$key]['thr'] = $thr[$key]['cuser'];
			}else{
				$keeper[$key]['thr'] = 0 ;
			}
			if($sev[$key] && $sev[$key]['u_stid'] = $cre[$key]['u_stid']){
				$keeper[$key]['sev'] = $sev[$key]['cuser'];
			}else{
				$keeper[$key]['sev'] = 0 ;
			}
			if($tweek[$key] && $tweek[$key]['u_stid'] = $cre[$key]['u_stid']){
				$keeper[$key]['twee'] = $tweek[$key]['cuser'];
			}else{
				$keeper[$key]['twee'] = 0 ;
			}
			
			$keeper[$key]['twesuv'] = round($keeper[$key]['twee'] / 14,2);
			
			$keeper[0]['totwesuv'] = round($totweek[0]['cuser'] / 14,2);
			if($tocre[0]['coco'] != 0 && !empty($tocre)){
				$keeper[$key]['ptwo'] = round($keeper[$key]['two']/$tocre[0]['coco']*100,2) .'%';
			}else{
				$keeper[$key]['ptwo'] = 0 ;
			}
			if($totwo[0]['cuser'] != 0 && !empty($totwo)){
				$keeper[$key]['pthr'] = round($keeper[$key]['thr']/$totwo[0]['cuser']*100,2) .'%';
			}else{
				$keeper[$key]['pthr'] = 0;
			}
			if($tothr[0]['cuser'] != 0 && !empty($tothr)){
				$keeper[$key]['psev'] = round($keeper[$key]['sev']/$tothr[0]['cuser']*100,2) .'%';
			}else{
				$keeper[$key]['psev'] = 0;
			}
			if($tothr[0]['cuser'] != 0 && !empty($tothr)){
				$keeper[$key]['ptwee'] = round($keeper[$key]['twee']/$tosev[0]['cuser']*100,2) .'%';
			}else{
				$keeper[$key]['ptwee'] = 0;
			}
			if($keeper[0]['totwesuv'] != 0 && !empty($keeper)){
				$keeper[$key]['ptwesuv'] = round($keeper[$key]['twesuv'] /$keeper[0]['totwesuv']*100,2) .'%' ;
			}else{
				$keeper[$key]['ptwesuv'] = 0;
			}
			
		}
		
		}	*/	
		//echo json_encode(array('list'=>$keeper));
		
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