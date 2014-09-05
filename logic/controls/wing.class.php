<?php
/**
   * FileName		: wing.class.php
   * Description	: 元宝消费统计查询
   * Author	    : zwy
   * Date			: 2014-6-6
   * Version		: 1.00
   */
class wing{
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
			if(!in_array('00200500', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取数据
	 */
	public function getWingData(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate')." 23:59:59";
		$ip = get_var_value('ip');
		if (!$ip){
			echo 1;exit;
		}
		$obj = D('game'.$ip);
		$wing = $obj->fquery("select * from wing where date between '{$startdate}' and '{$enddate}'");
		//echo "select * from wing where date between '{$startdate}' and '{$enddate}'";exit;
		if (empty($wing)){
			echo 1;exit;
		}
		$list = array(
				'shop'=>0,
				'mystery_shop'=>0,
				'bargain'=>0,
				'touch_of_gold'=>0,
				'bank'=>0
		);
		foreach ($wing as $k=>$v){
			$list['shop'] += $v['shop'];
			$list['mystery_shop'] += $v['mystery_shop'];
			$list['bargain'] += $v['bargain'];
			$list['touch_of_gold'] += $v['touch_of_gold'];
			$list['bank'] += $v['bank'];
		}
		echo json_encode($list);
	}
}

 