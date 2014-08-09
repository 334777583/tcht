<?php
/**
   * FileName		: operation.class.php
   * Description	: 操作日志
   * Author	    : zwy
   * Date			: 2014-6-12
   * Version		: 1.00
   */
class operation{
	private $user;
	private $ip;
	private $startDate;
	private $endDate;
	private $code;//查询类型、角色名，账号。。。
	private $codeValue;
	private $pageSize;
	private $curPage;
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00300800', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		$this->ip = get_var_value('ip');
		$this->startDate = get_var_value('startDate');
		$this->endDate = get_var_value('endDate');
		$this->code = get_var_value('code');
		$this->codeValue = get_var_value('codeValue');
		$this->pageSize = get_var_value('pageSize') == NULL ? 10 : get_var_value('pageSize');
		$this->curPage = get_var_value('curPage') == NULL ? 1 : get_var_value('curPage');
	}

	/**
	 * 获取数据
	 */
	public function getHistoryLog(){
		$this->startDate = $this->startDate.' 00:00:00';
		$this->endDate = $this->endDate.' 23:59:59';
		if($this->ip&&$this->codeValue) {
			global $t_conf;
			$srever = 's'.$this->ip;
			$Server = F($t_conf[$srever]['db'], $t_conf[$srever]['ip'], $t_conf[$srever]['user'], $t_conf[$srever]['password'], $t_conf[$srever]['port']);
			$user = $Server->fquery("SELECT GUID,RoleName,account from player_table as a LEFT JOIN game_user as b on a.AccountId=b.id where GUID=$this->codeValue");
			
			$tableNum = $this->codeValue%15;
			$obj = D("game".$this->ip);
			$total =  $obj->table('water_log'.$tableNum)->where("date between '{$this->startDate}' and '{$this->endDate}' and ItemId>100 and playid=$this->codeValue")->total();
			
			$sql = "select b.t_name,a.Count,a.date,a.Source,a.ItemId,a.target_playid from water_log$tableNum as a left join tools_detail as b on a.ItemId=b.t_code";
			$sql.= " where date between '{$this->startDate}' and '{$this->endDate}' and ItemId>100 and playid=$this->codeValue group by id limit ";
			$sql.= intval(($this->curPage-1)*$this->pageSize).",".$this->pageSize;
			$moneylog = $obj->fquery($sql);
// 			$moneylog = $obj->table('money_log')
// 			->where("date between '{$this->startDate}' and '{$this->endDate}' and coin_type=$this->cointype and playid=$this->codeValue")
// 			-> limit(intval(($this->curPage-1)*$this->pageSize),intval($this->pageSize))->select();
			
			//来源
			$source = file_get_contents(ITEM.'source.json');
			$source = json_decode($source,true);
			foreach ($moneylog as $k=>$v){
				if (isset($source[$v['Source']])){
					if ($v['target_playid']>0){
						$userTem = $Server->fquery("SELECT RoleName from player_table where ServerId=$this->ip and GUID=".$v['target_playid']);
						$moneylog[$k]['remark'] = $source[$v['Source']]."(另一个玩家角色名：".$userTem[0]['RoleName'].")";
					}else {
						$moneylog[$k]['remark'] = $source[$v['Source']];
					}
				}else {
					$moneylog[$k]['remark'] = '';
				}
			}
			
			$page = new autoAjaxPage($this->pageSize, $this->curPage, $total, "formAjax", "go","page");
			$pageHtml = $page->getPageHtml();
			echo json_encode(array(
					'result' => $moneylog,
					'user'=>$user,
					'pageHtml' => $pageHtml
			));
			
		}else {
			echo '1';
		}
	}
	
	//查看当天操作日记
	public function getCurrentLog(){
		if ($this->ip&&$this->codeValue){
			global $t_conf;
			$srever = 's'.$this->ip;
			$Server = F($t_conf[$srever]['db'], $t_conf[$srever]['ip'], $t_conf[$srever]['user'], $t_conf[$srever]['password'], $t_conf[$srever]['port']);
			$user = $Server->fquery("SELECT GUID,RoleName,account from player_table as a LEFT JOIN game_user as b on a.AccountId=b.id where GUID=$this->codeValue");
			
			$obj = D('game_base');
			$db = $obj -> table('gamedb') -> where("g_flag = 1 and g_id=$this->ip") -> find();
			$path = LPATH . $db['g_ip'] . '/' . date('Y-m-d') . '/';	//日志文件所在目录路径
			$file_type1 = $path.'log-type-100010.log';
			
			//所有物品id对应名称
			$goods = D('game'.$this->ip)->table("tools_detail")->select();
			foreach ($goods as $k=>$v){
				$goodsArr[$v['t_code']] = $v['t_name'];
			}
			
			//来源
			$source = file_get_contents(ITEM.'source.json');
			$source = json_decode($source,true);
			
			$moneylog = $this->getFileDate($file_type1,$this->codeValue);
			if (!$moneylog){
				echo 1;exit;
			}
			foreach ($moneylog as $k=>$v){
				$moneylog[$k]['goodsname'] = $goodsArr[$v['ItemId']];
				$moneylog[$k]['date'] = date("Y-m-d H:i:s",$v['time']);
				if (isset($source[$v['From']])){
					$moneylog[$k]['remark'] = $source[$v['From']];
				}else {
					unset($moneylog[$k]);
					//$moneylog[$k]['remark'] = $v['From'];
				}
			}
			
			echo json_encode(array(
					'result' => $moneylog,
					'user'=>$user
			));
		}else {
			echo '1';
		}
	}
	
	/**
	 * 获取日记文件数据
	 * @param unknown $path	路径
	 * @param unknown $playid		玩家id	
	 * @param unknown $cointype		货币类型
	 * @return multitype:mixed |multitype:string
	 */
	public function getFileDate($path,$playid){
		if (file_exists($path)) {
			$fp = fopen($path, "r");							//读取日志文件
			$log_data = array();								//保存日志分析信息
			while(!feof($fp)) {
				$line = fgets($fp,2048);
				if(!empty($line)) {
					$INFO  = trim(substr($line, 21));
					$INFO  = str_replace("'", '"', $INFO );
					$arr = json_decode($INFO , true);
					if(is_array($arr)&&$arr['playid']==$playid&&$arr['ItemId']>100) {
						$log_data[] = $arr;
					}
				}
			}
			fclose($fp);										//关闭文件指针
			return $log_data;
		}else {
			return false;
		}
	}
	
	//根据账号或者角色名查找用户角色id
	public function getUserPlayId(){
		if ($this->codeValue){
			global $t_conf;
			$sever = 's'.$this->ip;
			$Server = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
				
			switch ($this->code){
				case 1:
					$user = $Server->fquery("SELECT a.GUID,a.RoleName,b.account from player_table as a LEFT JOIN game_user as b on a.AccountId=b.id where b.account like '%$this->codeValue%'");
					break;
				case 2:
					$user = $Server->fquery("SELECT GUID,RoleName from player_table where RoleName LIKE '%$this->codeValue%'");
					break;
			}
			if (count($user)>0){
				echo json_encode(array('result'=>$user));
			}else {
				echo '1';
			}
		}else {
			echo '1';
		}
	}
}
 