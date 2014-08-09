<?php
/**
 * FileName: rechargequery.class.php
 * Description:充值查询
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-7-4 14:08:50
 * Version:1.00
 */
class rechargequery{
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
			if(!in_array('00100100', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取充值记录
	 */
	public function getRecords(){
		$rate = 10; 		//货币与元宝的比例
		
		$ip = get_var_value('ip');
		$startDate = get_var_value('startDate');
		$endDate = get_var_value('endDate');
		$code = get_var_value('code');
		$orderKey = get_var_value('orderKey');
		//$key = get_var_value('key');
		$pageSize = get_var_value('pageSize') == NULL ? 10 : get_var_value('pageSize');
		$curPage = get_var_value('curPage') == NULL ? 1 : get_var_value('curPage');
		$cty = get_var_value('cty');
		$bear = get_var_value('bear');
		global $t_conf;
				//list($sip) = autoConfig::getServer($ip);//获取服务器信息
		$sever = 's'.$ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$uname = $point->field('guid,rolename')->table('player_table')->select();	
		if($ip) {
			
			$obj = D("chongzhi");
			$where_sql = "c_sid=$ip and ";
			
			if($startDate) {
				$where_sql .= "left(c_time,10) >= '" . $startDate . "' and ";
			}
			if($endDate) {
				$where_sql .= "left(c_time,10) <='" . $endDate . "' and ";
			}
			if($code && $orderKey) {
				switch($code ) {
					//case 0 : $where_sql .= "token_id = '" . $orderKey . "' and ";break;
					case 1 : $where_sql .= "c_openid = '" . $orderKey . "' and ";break;
					case 3 : $where_sql .= "c_pid = '" . $orderKey . "' and ";break;
			
				}
				if($code == 2){
					$pid = $point ->field('GUID')->table('player_table')->where("RoleName='$orderKey'")->find();
					$where_sql .= "c_pid = '" . $pid['GUID']. "' and ";
				}
					
			}
			
			// if($code && $orderKey) {
				
			// }
			if($code == 0 && $orderKey){
				$where_sql .= "token_id = '" . $orderKey. "' and ";
			}
			$where_sql .= "c_state >= 1 and ";
			//echo $where_sql;
			
			if($where_sql !== "") {
				$where_sql = rtrim($where_sql, ' and ');
				$list = $obj -> table('chongzhi') -> where($where_sql) -> limit(intval(($curPage-1)*$pageSize),intval($pageSize)) -> order('c_time desc') -> select();
				//print_R($obj);
				$total = $obj -> table('chongzhi') -> where($where_sql)  -> total();
			} else {
				$list = $obj -> table('chongzhi') -> limit(intval(($curPage-1)*$pageSize),intval($pageSize)) -> order('c_time desc') -> select();
				$total = $obj -> table('chongzhi') -> total();
			}
			
			$u ='';
			foreach($uname as $k => $value){
					$u[$value['guid']]['guid'] = $value['guid'];
					if($u[$value['guid']]['guid'] == $value['guid']){
						$u[$value['guid']]['name']	= $value['rolename'];
					}
			}
			
			foreach($list as $key=>$item){
				if (isset($u[$item['c_pid']])){
					$list[$key]['name'] = $u[$item['c_pid']]['name'];
				}
			}
			
			$page = new autoAjaxPage($pageSize, $curPage, $total, "formAjax", "go","page");
			$pageHtml = $page->getPageHtml();
			echo json_encode(array(
					'result' => $list,
					'pageHtml' => $pageHtml
				));
		
		}else {
			echo '1';
		}
	}
}