<?php
/**
   * FileName		: killBoss.class.php
   * Description	: 玩家击杀boss查询
   * Author	    : zwy
   * Date			: 2014-8-8
   */
class killBoss{
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
			if(!in_array('00301000', $this->user['code'])){
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
	 * 查看历史
	 */
	public function getHistoryLog(){
		$this->endDate = $this->endDate.' 23:59:59';
		if($this->ip&&$this->codeValue) {
			global $t_conf;
			$srever = 's'.$this->ip;
			$Server = F($t_conf[$srever]['db'], $t_conf[$srever]['ip'], $t_conf[$srever]['user'], $t_conf[$srever]['password'], $t_conf[$srever]['port']);
			if ($this->code==2){
				$user = $Server->fquery("SELECT GUID,RoleName from player_table where RoleName='{$this->codeValue}'");
			}elseif ($this->code==3) {
				$user = $Server->fquery("SELECT GUID,RoleName from player_table where GUID=$this->codeValue");
			}
			
			$obj = D("game".$this->ip);
			$where = "date between '{$this->startDate}' and '{$this->endDate}' ";
			$where .= " and playid=".$user[0]['GUID'];
			$total =  $obj->table('killboss')->where($where)->total();

			if (!$total){
				echo 1;exit;
			}
			$list = $obj->table('killboss')
			->where($where)
			-> limit(intval(($this->curPage-1)*$this->pageSize),intval($this->pageSize))
			->order("id desc")
			->select();
			$bossPath = ITEM."monster.json";//物品配置jsoin文件路径
			$boss = json_decode(file_get_contents($bossPath),true);
			foreach ($list as $k=>$v){
				$list[$k]['playername'] = $user[0]['RoleName'];
				$list[$k]['bossname'] = $boss[$v['bossid']]['MonsterName'];
			}
			
			$page = new autoAjaxPage($this->pageSize, $this->curPage, $total, "formAjax", "go","page");
			$pageHtml = $page->getPageHtml();
			echo json_encode(array(
					'result' => $list,
					'pageHtml' => $pageHtml
			));
				
		}else {
			echo '1';
		}
	}

	//查看当天
	public function getCurrentLog(){
		if ($this->ip&&$this->codeValue){
			$obj = D('game_base');
			$db = $obj -> table('gamedb') -> where("g_flag = 1 and g_id=$this->ip") -> find();
			$path = LPATH . $db['g_ip'] . '/' . date('Y-m-d') . '/';	//日志文件所在目录路径
			$filePath = $path.'log-type-20.log';
			//$filePath = LPATH.'192.168.0.64/2014-07-29/log-type-20.log';
			$data = $this->getFileDate($filePath, $this->code, $this->codeValue);
			if (empty($data)){
				echo 1;exit;
			}
			
			echo json_encode(array(
					'result' => $data
			));
		}else {
			echo '1';
		}
	}

	/**
	 * 获取日记文件数据
	 * @param unknown $path	路径	
	 * @param int $code 玩家搜索类型
	 * @param unknown $playid		玩家id
	 * @param unknown $subtype		搜索类型（铜币、元宝。。。）
	 * @return multitype:mixed |multitype:string
	 */
	public function getFileDate($path,$code,$playid){
		if (file_exists($path)) {
			$fp = fopen($path, "r");							//读取日志文件
			$log_data = array();								//保存日志分析信息
			while(!feof($fp)) {
				$line = fgets($fp,2048);
				if(!empty($line)) {
					$INFO  = trim(substr($line, 21));
					$INFO  = str_replace("'", '"', $INFO );
					$arr = json_decode($INFO , true);
					if(is_array($arr)) {
						if ($code==2&&$arr['playername']==$playid){
							$log_data[] = $arr;
						}elseif ($code==3&&$arr['playid']==$playid) {
							$log_data[] = $arr;
						}
					}
				}
			}
			fclose($fp);										//关闭文件指针
			return $log_data;
		}else {
			return array();
		}
	}
}

 