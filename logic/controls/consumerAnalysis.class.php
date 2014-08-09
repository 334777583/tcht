<?php
/**
 * FileName: consumerAnalysis.class.php
 * Description:用户行为消耗分析
 * Author: BestWell
 * Date:2013-11-13
 * Version:1.00
 */
class consumerAnalysis{


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
			if(!in_array('00100400', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}

	public function getResult(){
		//require $ServicePath.'/yanfa_1/logic/class/autoAjaxPage.class.php';
		$sip = get_var_value('sip');
		$type = get_var_value('type');
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$wjlx = get_var_value('wjlx');
		$size = get_var_value('page') == NULL ? 10: get_var_value('page');
		$point = D('game'.$sip);
		//$size = isset($_POST['page_num'])&& !empty($_POST['page_num']) ? $_POST['page_num'] : 10;
		$start = $startdate.' 00:00:00';
		$end = $enddate.' 59:59:59';
		$dtime = $point ->table('gold_list')->field('left(g_date,10) as g_date')->group('left(g_date,10)')->order('g_id desc')->select();
		if($wjlx == '非R'){
			$obj = D('chongzhi');
			$player = $obj ->table('chongzhi')->field('c_pid')->where('c_state = 2')->group('c_pid')->select();
			if(!empty($player)){
				foreach($player as $key => $item){
					$pid[] = $item['c_pid'];
				}
				$pid = implode(',',$pid);
				$sql = "select count(g_gold) as g_num,g_type ,left(g_date,10) as g_date from gold_list where g_playid not in (".$pid.") and  g_date > '".$start."' and g_date < '".$end."' group by g_type ,left(g_date,10) order by g_id desc limit 0,{$size}";
				$result = $point->fquery($sql);  //
				if($type == 3 || $type == 4){
					$sresult = $point ->fquery("select left(i_date,10) as i_date,count(i_num) as num from item where i_playid not in (".$pid.") and i_date > '".$start."' and i_date < '".$end."' and i_type = {$type} group by i_type ,left(i_date,10)" );
				}
			}else{
				$sql = "select count(g_gold) as g_num,g_type ,left(g_date,10) as g_date from gold_list where g_date > '".$start."' and g_date < '".$end."' group by g_type ,left(g_date,10) order by g_id desc limit 0,{$size}";
				$result = $point->fquery($sql);  //
				if($type == 3 || $type == 4){
					$sresult = $point ->fquery("select left(i_date,10) as i_date,count(i_num) as num from item where  and i_date > '".$start."' and i_date < '".$end."' and i_type = {$type} group by i_type ,left(i_date,10)" );
				}
			}
			// $result = $point->fquery($sql);  //
			// if($type == 3 || $type == 4){
				// $sresult = $point ->fquery("select left(i_date,10) as i_date,count(i_num) as num from item where i_playid not in (".$pid.") and i_date > '".$start."' and i_date < '".$end."' and i_type = {$type} group by i_type ,left(i_date,10)" );
			// }
		}else{
			$uid = $this->getPost();
			if($uid == 0){
				echo 1;
				exit;
			}else{
				$sql = "select count(g_gold) as g_num,g_type ,left(g_date,10) as g_date from gold_list where g_playid in (".$uid.") and  g_date > '".$start."' and g_date < '".$end."' group by g_type ,left(g_date,10) order by g_id desc limit 0,{$size}";
				// $sql = "select chount(g.g_gold) as g_num ,g.g_type,left(g.g_date,10) as g_date from gold_list g join item i"
				$result = $point->fquery($sql);  //
				if($type == 3 || $type == 4){
					$sresult = $point ->fquery("select left(i_date,10) as i_date,count(i_num) as num from item where i_playid in (".$uid.") and i_date > '".$start."' and i_date < '".$end."' and i_type = {$type} group by i_type ,left(i_date,10)" );
				}
			}
		}
		$db = D('game_base');
		$sname = $db->table('servers')->where(array('s_id'=>$sip))->find();
		if(!empty($result)){
			if(!empty($sresult) && $type < 5){
				foreach($sresult as $key => $item){
					$list[$item['i_date']]['shop'] = $item['num'];
					$list[$item['i_date']]['g_date'] = $item['i_date'];
					$list[$item['i_date']]['yxpt'] = $sname['s_name'];
					$list[$item['i_date']]['type'] = $type;
				}
			}
			foreach ($result as $key => $value) {
				
				if ($type == 3) {
					$list[$value['g_date']]['g_date'] = $value['g_date'];
					$list[$value['g_date']]['yxpt'] = $sname['s_name'];
					$list[$value['g_date']]['type'] = $type;
					if($result[$key]['g_type'] == 1801){
						$list[$value['g_date']]['zj'] = $result[$key]['g_num'];
					}if($result[$key]['g_type'] == 1802){
						$list[$value['g_date']]['xx'] = $result[$key]['g_num'];
					}if($result[$key]['g_type'] == 1803){
						$list[$value['g_date']]['sb'] = $result[$key]['g_num'];
					}if($result[$key]['g_type'] == 1804){
						$list[$value['g_date']]['yb'] = $result[$key]['g_num'];
					}if($result[$key]['g_type'] == 1805){
						$list[$value['g_date']]['kc'] = $result[$key]['g_num'];
					}if($result[$key]['g_type'] == 1806){
						$list[$value['g_date']]['cq'] = $result[$key]['g_num'];
					}if($result[$key]['g_type'] == 1807){
						$list[$value['g_date']]['dz'] = $result[$key]['g_num'];
					}if($result[$key]['g_type'] == 1808){
						$list[$value['g_date']]['zg'] = $result[$key]['g_num'];
					}if($result[$key]['g_type'] == 1809){
						$list[$value['g_date']]['tx'] = $result[$key]['g_num'];
					}
					
				}else if($type == 4){
					$list[$value['g_date']]['g_date'] = $value['g_date'];
					$list[$value['g_date']]['yxpt'] = $sname['s_name'];
					$list[$value['g_date']]['type'] = $type;
					if($result[$key]['g_type'] == 1805){
						$list[$value['g_date']]['kc'] = $result[$key]['g_num'];
					}if($result[$key]['g_type'] == 1902){
						$list[$value['g_date']]['hs'] = $result[$key]['g_num'];
					}
					
				}else{
					if($result[$key]['g_type'] == 2001){
						$list[$value['g_date']]['lq'] = $result[$key]['g_num'];
						$list[$value['g_date']]['g_date'] = $value['g_date'];
						$list[$value['g_date']]['yxpt'] = $sname['s_name'];
						$list[$value['g_date']]['type'] = $type;
					}
					
				}
			}
			
			if(!empty($list)){
				
				krsort($list);
				$list = array_values($list);
				
				if(isset($list) && count($list) > 0){
					$tmpfname = tempnam('/tmp','ASDFGHJKEWRTYUI');
					$handle = fopen($tmpfname, "w");
					fwrite($handle, json_encode($list));
					fclose($handle);
					$filename = base64_encode($tmpfname);
				}
				
				foreach($list as $key => $item){
					if($type == 5){
						if(empty($item['lq'])){
							echo 1;
							exit;
						}else{
							echo json_encode(array('list'=>$list,'filename'=>$filename));
							exit;
						}
					}
					if($type == 4){
						if(!empty($item['shop']) || !empty($item['kc']) || !empty($item['hs']) ){
							if(empty($item['shop'])){
								$item['shop'] = 0;
							}
							echo json_encode(array('list'=>$list,'filename'=>$filename));
							exit;
						}else{
							echo 1;
							exit;
						}
					}
					
					if($type == 3){
						if(!empty($item['shop']) || !empty($item['zj']) || !empty($item['xx']) || !empty($item['sb']) || !empty($item['yb']) || !empty($item['kc']) || !empty($item['cq']) || !empty($item['dz']) || !empty($item['zg']) || !empty($item['tx']) ){
							if(empty($item['shop'])){
								$item['shop'] = 0;
							}
							echo json_encode(array('list'=>$list,'filename'=>$filename));
							exit;
						}else{
							echo 1;
							exit;
						}
					}
				}
			}else{
				echo 1;
				exit;
			}
			//echo json_encode(array('list'=>$list,'filename'=>$filename));
		
		}else{
			echo 1;
		}

		
	}
	
	/**
	 * 获取表格数据
	 */
	public function getcarbon(){
		$point = D('game'.$this->ip);
		$list = $point -> table('')-> where(array(''=>$this->startdate,''=>$this->enddate)) ->select();
		echo json_encode(array(
						''=>$this->enddate,
						''=>$this->enddate,
						''=>$list
						));
		exit;
	}
	
	/**
	*导出元宝excel
	*/
	public function yuanbaoExcel(){
		$sip = get_var_value('ip');
		$type = get_var_value('type');
		$startdate = get_var_value('stardate');
		$enddate = get_var_value('enddate');
		$size = get_var_value('page');
		$point = D('game'.$sip);
		$size = isset($_POST['page_num'])&& !empty($_POST['page_num']) ? $_POST['page_num'] : 10;
		$wheresql = '';
		if(isset($_POST['yxpt']) || isset($_POST['wjlx']) || isset($_POST['ybxz']) || isset($_POST['qufu'])){ //判断是否有POST传值
			$wheresql = $this->getPost();
		}
		//$result = $point->fquery("SELECT *  FROM chongzhi ".$wheresql);
		$result = $point->table('pay_count')->where(array('createtime >=' =>$startdate , 'createtime <=' =>$enddate))->order('p_id desc')->limit(0,$size)->select();
		$obj = D('game_base');
		$sname = $obj->table('servers')->where(array('s_id'=>$sip))->find();
		//判断数据类型并输出
		$returnArr = array();
		if(!empty($result)){
			foreach ($result as $key => $value) {
				$returnArr[$key]['time'] = $value['createtime'];
				// $returnArr[$key]['type'] = $sname['s_name'];
				$returnArr[$key]['yxpt'] = $sname['s_name'];
				//  = $value['yxqf'];
				
				if ($type == 3) {
					$returnArr[$key]['yuanbaochongmai'] = $value['dj_add'];
					$returnArr[$key]['chdjhchzhizhifuben'] = $value['xx_qc'];
					$returnArr[$key]['chdjhqingchushaodangCD'] = $value['sb_qc'];
					$returnArr[$key]['gumqychzhifuben'] = $value['yb_bc'];
					$returnArr[$key]['gumqyshaodangCD'] = $value['bb_kc'];
					$returnArr[$key]['ningxgqcshlqCD'] = $value['cq_mj'];
					$returnArr[$key]['lxjysblq'] = $value['dz_mj'];
					$returnArr[$key]['banhuijx'] = $value['zg_qz'];
					$returnArr[$key]['qhdjwmcc'] = $value['tb_zh'];
					$returnArr[$key]['type'] = 1;
					
				}elseif($type == 4){
					$returnArr[$key]['shangchangshop'] = $value['b_bb_kc'];
					$returnArr[$key]['taohuazhen'] = $value['hs_gm'];
					$returnArr[$key]['type'] = 2;
				}else{
					$returnArr[$key]['shangchangshop'] = $value['zg_qz'];
					$returnArr[$key]['type'] = 3;
				}
			}
		//}

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
			if($type == 3){				 
				$objPHPExcel->setActiveSheetIndex(0);	
				$objPHPExcel->getActiveSheet()->setCellValue('A1', '游戏服'); 
				$objPHPExcel->getActiveSheet()->setCellValue('B1', '日期');
				$objPHPExcel->getActiveSheet()->setCellValue('C1', '点将台增加次数');
				$objPHPExcel->getActiveSheet()->setCellValue('D1', '星宿清除CD');
				$objPHPExcel->getActiveSheet()->setCellValue('E1', '商城购买');
				$objPHPExcel->getActiveSheet()->setCellValue('F1', '神兵聚灵清除');
				$objPHPExcel->getActiveSheet()->setCellValue('G1', '元宝领取补偿');
				$objPHPExcel->getActiveSheet()->setCellValue('H1', '扩充背包格');
				$objPHPExcel->getActiveSheet()->setCellValue('I1', '春秋古墓摸金');
				$objPHPExcel->getActiveSheet()->setCellValue('J1', '大周古墓摸金');
				$objPHPExcel->getActiveSheet()->setCellValue('K1', '诸葛钱庄');
				$objPHPExcel->getActiveSheet()->setCellValue('L1', '特性宝石转化');
				
				if (is_array($result)) {
					foreach($returnArr as $k => $item){
						$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["yxpt"]); 
						$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["time"]);
						$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["yuanbaochongmai"]);
						$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["chdjhchzhizhifuben"]);
						$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["banhuijx"]);
						$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["chdjhqingchushaodangCD"]);
						$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["gumqychzhifuben"]);
						$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["gumqyshaodangCD"]);
						$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["ningxgqcshlqCD"]);
						$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["lxjysblq"]);
						$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["banhuijx"]);
						$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["qhdjwmcc"]);
					}	
				}
			}else if($type == 4){
							$objPHPExcel->setActiveSheetIndex(0);	
				$objPHPExcel->getActiveSheet()->setCellValue('A1', '游戏服'); 
				$objPHPExcel->getActiveSheet()->setCellValue('B1', '日期');
				$objPHPExcel->getActiveSheet()->setCellValue('C1', '商城购买');
				$objPHPExcel->getActiveSheet()->setCellValue('D1', '扩充背包格');
				$objPHPExcel->getActiveSheet()->setCellValue('E1', '横扫千军购买次数');
				
				if (is_array($result)) {
					foreach($returnArr as $k => $item){
						$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["yxpt"]); 
						$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["time"]);
						$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["shangchangshop"]);
						$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["shangchangshop"]);
						$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["taohuazhen"]);
					}	
				}

			}else{
				$objPHPExcel->setActiveSheetIndex(0);	
				$objPHPExcel->getActiveSheet()->setCellValue('A1', '游戏服'); 
				$objPHPExcel->getActiveSheet()->setCellValue('B1', '日期');
				$objPHPExcel->getActiveSheet()->setCellValue('C1', '商城购买');
				
				if (is_array($result)) {
					foreach($returnArr as $k => $item){
						$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["yxpt"]); 
						$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["time"]);
						$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["shangchangshop"]);
					}	
				}

			}
			$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "行为消耗_".$startdate."[".$type."]";
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			exit;
		
		}

	}

	
	
	//返回货币类型
	public function getType(){
		echo $_POST['type'];
	}

	public function getPlayer(){
		$type = get_var_value('type');
		$wjlx = get_var_value('user');
		if ($type == 3) {
			echo $wjlx.'玩家元宝消费占比';
		}elseif($type == 4){
			echo $wjlx.'玩家绑定元宝消费占比';
		}else{
			echo $wjlx.'玩家礼券消费占比';
		}
	}

	//统计数据显示图片
	public function showImg(){
		$sip = get_var_value('sip');
		$type = get_var_value('type');
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$wjlx = get_var_value('wjlx');
		$point = D('game'.$sip);
		$arr ='';
		
		$start = $startdate.' 00:00:00';
		$end = $enddate.' 59:59:59';
		$dtime = $point ->table('gold_list')->field('left(g_date,10) as g_date')->group('left(g_date,10)')->order('g_id desc')->select();
		if($wjlx == '非R'){
			$obj = D('chongzhi');
			$player = $obj ->table('chongzhi')->field('c_pid')->where('c_state = 2')->group('c_pid')->select();
			if(!empty($player)){
				foreach($player as $key => $item){
				$pid[] = $item['c_pid'];
				}	
				$pid = implode(',',$pid);
				$sql = "select count(g_gold) as g_num,g_type ,left(g_date,10) as g_date from gold_list where g_playid not in (".$pid.") and  g_date > '".$start."' and g_date < '".$end."' group by g_type order by g_id desc ";
				$result = $point->fquery($sql);  //
				if($type == 3 || $type == 4){
					$sresult = $point ->fquery("select left(i_date,10) as i_date,count(i_num) as num from item where i_playid not in (".$pid.") and i_date > '".$start."' and i_date < '".$end."' and i_type = {$type} group by i_type " );
				}
			}else{
				$sql = "select count(g_gold) as g_num,g_type ,left(g_date,10) as g_date from gold_list where  g_date > '".$start."' and g_date < '".$end."' group by g_type order by g_id desc ";
				$result = $point->fquery($sql);  //
				if($type == 3 || $type == 4){
					$sresult = $point ->fquery("select left(i_date,10) as i_date,count(i_num) as num from item where i_date > '".$start."' and i_date < '".$end."' and i_type = {$type} group by i_type " );
				}
			}
		}else{
			$uid = $this->getPost();
			if($uid != 0){
				$sql = "select count(g_gold) as g_num,g_type ,left(g_date,10) as g_date from gold_list where g_playid in (".$uid.") and  g_date > '".$start."' and g_date < '".$end."' group by g_type order by g_id desc ";
				$result = $point->fquery($sql);  //
				if($type == 3 || $type == 4){
					$sresult = $point ->fquery("select left(i_date,10) as i_date,count(i_num) as num from item where i_playid in (".$uid.") and i_date > '".$start."' and i_date < '".$end."' and i_type = {$type} group by i_type" );
				}
			}
		}
		if(!empty($result)){
			if($type == 3){
				$list[0]['增加点将台次数']=0;
				$list[0]['星宿清除']=0;
				$list[0]['商城购买']=0;
				$list[0]['神兵聚灵清除']=0;
				$list[0]['元宝补偿']=0;
				$list[0]['背包扩充']=0;
				$list[0]['春秋古墓摸金']=0;
				$list[0]['大周古墓摸金']=0;
				$list[0]['诸葛钱庄']=0;
				$list[0]['特性宝石转化']=0;
			}else if($type == 4){
				$list[0]['商城购买']=0;
				$list[0]['背包扩充']=0;
				$list[0]['横扫千军购买次数']=0;
			}else{
				$list[0]['商城购买']=0;
			
			}
			foreach ($result as $key => $value) {
				if ($type == 3) {
					if($result[$key]['g_type'] == 1801){
						$list[0]['增加点将台次数'] = $result[$key]['g_num'];
					}
					if($result[$key]['g_type'] == 1802){
						$list[0]['星宿清除'] = $result[$key]['g_num'];
					}
					if(!empty($sresult)){
						$list[0]['商城购买'] = $sresult[0]['num'];
					}
					if($result[$key]['g_type'] == 1803){
						$list[0]['神兵聚灵清除'] = $result[$key]['g_num'];
					}
					if($result[$key]['g_type'] == 1804){
						$list[0]['元宝补偿'] = $result[$key]['g_num'];
					}
					if($result[$key]['g_type'] == 1805){
						$list[0]['背包扩充'] = $result[$key]['g_num'];
					}
					if($result[$key]['g_type'] == 1806){
						$list[0]['春秋古墓摸金'] = $result[$key]['g_num'];
					}
					if($result[$key]['g_type'] == 1807){
						$list[0]['大周古墓摸金'] = $result[$key]['g_num'];
					}
					if($result[$key]['g_type'] == 1808){
						$list[0]['诸葛钱庄'] = $result[$key]['g_num'];
					}
					if($result[$key]['g_type'] == 1809){
						$list[0]['特性宝石转化'] = $result[$key]['g_num'];
					}
					
				}elseif($type == 4){
					if(!empty($sresult)){
						$list[0]['商城购买'] = $sresult[$key]['num'];
					}
					if($result[$key]['g_type'] == 1805){
						$list[0]['背包扩充'] = $result[$key]['g_num'];
					}
					if($result[$key]['g_type'] == 1902){
						$list[0]['横扫千军购买次数'] = $result[$key]['g_num'];
					}
					
				}else{
					if($result[$key]['g_type'] == 2001){
						$list[0]['商城购买'] = $result[$key]['g_num'];
					}
					
				}
			}
			
		}else{
			if($type == 3){
				$list[0]['增加点将台次数']=0;
				$list[0]['星宿清除']=0;
				$list[0]['神兵聚灵清除']=0;
				$list[0]['元宝补偿']=0;
				$list[0]['背包扩充']=0;
				$list[0]['春秋古墓摸金']=0;
				$list[0]['大周古墓摸金']=0;
				$list[0]['诸葛钱庄']=0;
				$list[0]['特性宝石转化']=0;
			}else if($type == 4){
				$list[0]['背包扩充']=0;
				$list[0]['横扫千军购买次数']=0;
			}else{
				$list[0]['商城购买']=0;
			
			}
		}
		//$list = array_values($list);
		foreach ($list[0] as $key => $value) {
			$arr[] = '{"name":"'.$key.'","num":'.$value.'}';
		}
		if (!empty($arr)) {
			$str = implode(',', $arr);
			$str2 = '['.$str.']';
			echo $str2;
		}else{
			echo 1;
		}
		
	}

	//获取POST提交的数据
	private function getPost(){
		$sip = get_var_value('sip');
		$where = "c_state = 2 and ";
		$obj = D('chongzhi');
		if(isset($_POST['wjlx']) && !empty($_POST['wjlx'])) {
			if($_POST['wjlx'] == '大R'){
				$where .= "c_num * c_price >= 50000 and ";
			}elseif($_POST['wjlx'] == '中R'){
				$where .= "c_num * c_price < 50000 AND c_num * c_price >= 5000 and ";
			}else{
				$where .= "c_num * c_price < 5000 AND c_num * c_price >= 10 and ";
			}
		}
		
		$where .= 'c_sid ='.$sip;
		$usql= "select c_pid from chongzhi where ".$where." group by c_openid";
		$uid = $obj->fquery($usql);
		$pid ='';
		if(!empty($uid)){
			foreach($uid as $key => $item){
				$pid[] .= $item['c_pid'];
			}	
			$pid = implode(',',$pid);
		}else{
			$pid = 0;
		}
		return $pid;
	}
	
		
	/**
	*导出excel
	*/
	public function Excel(){
		$sip = get_var_value('ip');
		$type = get_var_value('type');
		$ex = get_var_value('ex');
		$f = base64_decode($ex);
		if(!is_file($f)){
			echo 'error';
			exit();
		}
		$arr = json_decode(file_get_contents($f),true);
		$list = '';
		foreach($arr as $key => $item){
			$list[$key] = $item;
			if($type == 3){
				if(empty($item['zj'])){
					$list[$key]['zj'] = 0;
				}
				if(empty($item['xx'])){
					$list[$key]['xx'] = 0;
				}
				if(empty($item['sb'])){
					$list[$key]['sb'] = 0;
				}
				if(empty($item['yb'])){
					$list[$key]['yb'] = 0;
				}
				if(empty($item['kc'])){
					$list[$key]['kc'] = 0;
				}
				if(empty($item['cq'])){
					$list[$key]['cq'] = 0;
				}
				if(empty($item['dz'])){
					$list[$key]['dz'] = 0;
				}
				if(empty($item['zg'])){
					$list[$key]['zg'] = 0;
				}
				if(empty($item['tx'])){
					$list[$key]['tx'] = 0;
				}
				if(empty($item['shop'])){
					$list[$key]['shop'] = 0;
				}
			}else if($type == 4){
				if(empty($item['kc'])){
					$list[$key]['kc'] = 0;
				}
				if(empty($item['shop'])){
					$list[$key]['shop'] = 0;
				}
				if(empty($item['hs'])){
					$list[$key]['hs'] = 0;
				}
			}else{
				if(empty($item['lq'])){
					$list[$key]['lq'] = 0;
				}
			}
		}
		if(!empty($list)){
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
			if($type == 3){				 
				$objPHPExcel->setActiveSheetIndex(0);	
				$objPHPExcel->getActiveSheet()->setCellValue('A1', '游戏服'); 
				$objPHPExcel->getActiveSheet()->setCellValue('B1', '日期');
				$objPHPExcel->getActiveSheet()->setCellValue('C1', '点将台增加次数');
				$objPHPExcel->getActiveSheet()->setCellValue('D1', '星宿清除CD');
				$objPHPExcel->getActiveSheet()->setCellValue('E1', '商城购买');
				$objPHPExcel->getActiveSheet()->setCellValue('F1', '神兵聚灵清除');
				$objPHPExcel->getActiveSheet()->setCellValue('G1', '元宝领取补偿');
				$objPHPExcel->getActiveSheet()->setCellValue('H1', '扩充背包格');
				$objPHPExcel->getActiveSheet()->setCellValue('I1', '春秋古墓摸金');
				$objPHPExcel->getActiveSheet()->setCellValue('J1', '大周古墓摸金');
				$objPHPExcel->getActiveSheet()->setCellValue('K1', '诸葛钱庄');
				$objPHPExcel->getActiveSheet()->setCellValue('L1', '特性宝石转化');
				
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["yxpt"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["g_date"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["zj"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["xx"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["shop"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["sb"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["yb"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["kc"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["cq"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["dz"]);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["zg"]);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["tx"]);
				}
			}else if($type == 4){
							$objPHPExcel->setActiveSheetIndex(0);	
				$objPHPExcel->getActiveSheet()->setCellValue('A1', '游戏服'); 
				$objPHPExcel->getActiveSheet()->setCellValue('B1', '日期');
				$objPHPExcel->getActiveSheet()->setCellValue('C1', '商城购买');
				$objPHPExcel->getActiveSheet()->setCellValue('D1', '扩充背包格');
				$objPHPExcel->getActiveSheet()->setCellValue('E1', '横扫千军购买次数');
				
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["yxpt"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["g_date"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["shop"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["kc"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["hs"]);
				}	
				

			}else{
				$objPHPExcel->setActiveSheetIndex(0);	
				$objPHPExcel->getActiveSheet()->setCellValue('A1', '游戏服'); 
				$objPHPExcel->getActiveSheet()->setCellValue('B1', '日期');
				$objPHPExcel->getActiveSheet()->setCellValue('C1', '商城购买');
				
				foreach($list as $k => $item){
						$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["yxpt"]); 
						$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["g_date"]);
						$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["lq"]);
				}

			}
			$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);

			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="行为消耗.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
		
		}
	}
	
}
?>