<?php
/**
 * FileName: shopconsume.class.php
 * Description:商城消费记录
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午3:40:29
 * Version:1.00
 */
class shopconsume{
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
	 * 账号类型
	 * @var int
	 */
	private $type;
	
	/**
	 * 搜索内容
	 * @var string
	 */
	private $key;
	
	/**
	 * 模糊查询(0：模糊；1：精确)
	 * @var int
	 */
	private $fuzzy;
	
	
	/**
	*初始化数据
	*/
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00200100', $this->user['code'])){
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
		$this->key =  get_var_value('key') == NULL? '' : get_var_value('key');
		$this->fuzzy =  get_var_value('fuzzy') == NULL? -1 : get_var_value('fuzzy');
	}
	
	/**
	 * FunctionName: getStartData
	 * Description: 获取开服时间
	 * Author: jan	
	 * Parameter：null
	 * Return: json
	 * Date: 2014-4-04 17:49:08
	 **/
	function getStartData(){
		$obj = D('game'.$this -> ip);
		 //查询商城消费记录 的开服日期
		$listdate = $obj -> table('item1')
						 -> field('i_date')
						 -> limit(0,1)
						 -> find();
		$list_date = isset($listdate['i_date'])?date('Y-m-d',strtotime($listdate['i_date'])):date("Y-m-d",strtotime("-7 day"));//如果表里没数据 默认7天前
		$this -> startdate = get_var_value("startdate") == NULL?$list_date:get_var_value("startdate");
		$this -> enddate = get_var_value("enddate") == NULL ? date('Y-m-d',strtotime($list_date)+ 7*24*3600):get_var_value("enddate");
		
		echo json_encode(array('startDate'=>$this -> startdate,'endDate'=>$this -> enddate));
	}
	
	
	/**
	* 搜索消费记录
	*/
	public function search(){
		$this->enddate = $this->enddate.' 23:59:59';
		$roleList = array();
		$money_type = get_var_value("money_type") ;//== NULL ? 3 : get_var_value("money_type");
		$type = get_var_value("type");
		global $t_conf;
		list($ip, $port, $loginName) = autoConfig::getConfig($this->ip);
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$obj = D("game".$this -> ip);
		// $star = $obj->field('left(o_date,10) as o_date')->table('online_sec')->order('o_date asc')->find();
		// $enddate = get_var_value('enddate') == NULL ? date('Y-m-d',strtotime($star['o_date'])+7*24*3600) :get_var_value('enddate');
		// $startdate = get_var_value('startdate') == NULL ? $start : get_var_value('startdate');
		
		if (!empty($this->key)) {
				if($this->fuzzy == 1 && $type ==2){
					$roleid = $point -> field('guid')->table('player_table')->where(array('RoleName'=>$this->key))->find();
					$this ->key = $roleid['guid'];
				}
				
				$table = 'item'.$this->key % 15 ;
				$total = 0;							//查询总记录数
				
				if($this->startdate == date('Y-m-d')){
					$ip = get_var_value('ip');
					$obj = D('game_base');
					$gamedb = D('game'.$this->ip);
					$s_ip = $obj ->table('servers')->where("s_id = {$this->ip}")->find();
					$file = LPATH.$s_ip['s_ip'].'/'.date('Y-m-d').'/log-type-6.log';
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
						$gamedb->rquery('DELETE from mall');
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
					$ins_str = $gamedb->rquery($ins_data);
					$Reasult1 = $gamedb->fquery("SELECT i.i_playid,i.i_shopid,i.i_price,i.i_num cnum,i.i_dtype,i.i_date,g.t_name FROM mall as i LEFT JOIN tools_detail as g ON i.i_shopid=g.t_code WHERE i_playid = {$this->key} AND i.i_type={$money_type} ORDER BY i.i_date DESC");
					$total = count($Reasult1);
					$Reasult1 = $gamedb->fquery("SELECT i.i_playid,i.i_shopid,i.i_price,i.i_num cnum,i.i_dtype,i.i_date,g.t_name FROM mall as i LEFT JOIN tools_detail as g ON i.i_shopid=g.t_code WHERE i_playid = {$this->key} AND i.i_type={$money_type} ORDER BY i.i_date DESC limit ".intval(($this->curPage-1)*$this->pageSize).",".intval($this->pageSize));
				}else{
					$count = $obj->fquery("SELECT i.i_playid FROM {$table} as i WHERE i_playid = {$this->key} AND i.i_type={$money_type} AND i.i_date > '{$this->startdate}' AND i.i_date <= '{$this->enddate}'");
					$total = count($count);
					$Reasult1 = $obj->fquery("SELECT i.i_playid,i.i_shopid,i.i_price,i.i_num cnum,i.i_dtype,i.i_date,g.t_name FROM {$table} as i LEFT JOIN tools_detail as g ON i.i_shopid=g.t_code WHERE i_playid = {$this->key} AND i.i_type={$money_type} AND i.i_date > '{$this->startdate}' AND i.i_date <= '{$this->enddate}' ORDER BY i.i_date asc,i.i_shopid ASC limit ".intval(($this->curPage-1)*$this->pageSize).",".intval($this->pageSize));
					//$Reasult1 = $obj->fquery("SELECT i.i_playid,i.i_shopid,i.i_price,SUM(i.i_num) cnum,i.i_dtype,i.i_date,g.t_name FROM {$table} as i LEFT JOIN tools_detail as g ON i.i_shopid=g.t_code WHERE i_playid = {$this->key} AND i.i_type={$money_type} AND i.i_date > '{$this->startdate}' AND i.i_date <= '{$this->enddate}' GROUP BY i.i_playid, i.i_shopid ORDER BY i.i_shopid ASC");
				}
				$where = " WHERE GUID={$this->key}";
			}else{
				$where = '';
			}
			$Reasult2 = $point->fquery("SELECT GUID,RoleName,AccountId FROM player_table".$where);

			foreach ($Reasult1 as $key => $value) {
				foreach ($Reasult2 as $k => $v) {
					$value['p_name'] = $Reasult2[$k]['RoleName'];
					$value['p_account'] = $Reasult2[$k]['AccountId'];
					$roleList[] = $value;
				}
			}
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax','go','page');
		$pageHtml = $page->getPageHtml();
		$result = array(
				'list' => $roleList,
				'pageHtml'=>$pageHtml
			);
		echo json_encode($result);
		exit;
	}
	
	/**
	*根据角色名获取角色ID
	*/
	public function getRoleList(){
		list($ip, $port, $loginName) = autoConfig::getConfig($this->ip);
		$money_type = get_var_value('money_type');
		global $t_conf;

		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$obj = D("game".$this -> ip);
		
		if($this -> fuzzy == 0){ 		//模糊查询
			$total = 0;					//查询总记录数
			$roleList = array();		//玩家基本信息

			$info = array();
			$info['pageNum'] = $this -> curPage;
			$info['what'] = $this -> key;
		
			if (!empty($this->key)) {
				$where = " WHERE RoleName like '%{$this->key}%'";
			}else{
				$where = '';
			}
			$Reasult2 = $point->fquery("SELECT GUID,RoleName,AccountId FROM player_table".$where);
			
			$page = new autoAjaxPage($this -> pageSize,$this -> curPage,$total,"pageAjax2","go2","page2");

			$pageHtml = $page -> getPageHtml();
			$result = array(
					'pageHtml' 	=> $pageHtml,
					'plays' 	=> $Reasult2
			);
			if($this->curPage != 1) {			//请求延迟一秒执行
				sleep(1);
			}
			echo json_encode($result);
			exit;
		}
		/*else if($this->fuzzy == 1){			//精确查询
		
			if (!empty($this->key)) {
			$roleid = $point -> field('guid')->table('player_table')->where(array('RoleName'=>$this->key))->find();
			$table = 'item'.$roleid['guid'] % 15 ;
			$total = 0;							//查询总记录数
			$Reasult1 = $obj->fquery("SELECT i.i_playid,i.i_shopid,i.i_price,SUM(i.i_num) cnum,i.i_dtype,i.i_date,g.t_name FROM {$table} as i LEFT JOIN tools_detail as g ON i.i_shopid=g.t_code WHERE i_playid = {$roleid['guid']} AND i.i_type={$money_type} AND i.i_date > '{$this->startdate}' AND i.i_date <= '{$this->enddate}' GROUP BY i.i_playid, i.i_shopid ORDER BY i.i_shopid ASC");
			$where = " WHERE GUID=".$roleid['guid'];
			}else{
				$where = '';
			}
			$Reasult2 = $point->fquery("SELECT GUID,RoleName,AccountId FROM player_table".$where);

			foreach ($Reasult1 as $key => $value) {
				foreach ($Reasult2 as $k => $v) {
					//if ($Reasult1[$key]['i_playid'] == $Reasult2[$k]['GUID']) {
						$value['p_name'] = $Reasult2[$k]['RoleName'];
						$value['p_account'] = $Reasult2[$k]['AccountId'];
						$roleList[] = $value;
					//}
				}
			}
			$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax','go','page');
			$pageHtml = $page->getPageHtml();
			$result = array(
					'list' => $roleList,
					'pageHtml'=>$pageHtml
					//'roleName' => $roleName,
					//'good_list' => $goods_arr
				);
			echo json_encode($result);
			exit;
		}*/
		//}
	}

}