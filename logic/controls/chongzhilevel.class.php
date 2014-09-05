<?php
/**
   * FileName		: chongzhilevel.class.php
   * Description	: 充值等级查询
   * Author	    : zwy
   * Date			: 2014-6-6
   * Version		: 1.00
   */
class chongzhilevel{
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
			if(!in_array('00401700', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取数据
	 */
	public function getData(){
		$ip = get_var_value('ip');
		$startDate = get_var_value('startDate')==null?date('Y-m-d'):get_var_value('startDate');
		$endDate = get_var_value('endDate')==null?date('Y-m-d'):get_var_value('endDate');
		$pageSize = get_var_value('pageSize') == NULL ? 10 : get_var_value('pageSize');
		$curPage = get_var_value('curPage') == NULL ? 1 : get_var_value('curPage');
		$times = get_var_value('times');
		$startDate = $startDate.' 00:00:00';
		$endDate = $endDate.' 23:59:59';
		if($ip) {
			$obj = D("chongzhi");
			//select left(c_time,10) as date,c_level,sum(c_amt) as RMB,COUNT(c_id) as num from chongzhi where c_times=2 GROUP BY c_level,date
			$total = $obj->fquery("select left(c_time,10) as date,c_level,sum(c_amt) as RMB,COUNT(c_id) as num from chongzhi where c_sid=$ip and c_state=2 and c_times=$times and c_time>='$startDate' and c_time<='$endDate' GROUP BY c_level,date");
			
			$where_sql = " c_sid=$ip and c_state=2 and c_times=$times and c_time>='$startDate' and c_time<='$endDate'";
			$list = $obj -> table('chongzhi') 
						->field("left(c_time,10) as date,c_level,sum(c_price*c_num) as RMB,COUNT(c_id) as num")
						-> where($where_sql)
						->group("c_level,date")
						-> limit(intval(($curPage-1)*$pageSize),intval($pageSize)) 
						-> order('c_level asc,date asc')
						-> select();
			$total = count($total);
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

 