<?php
/**
   * FileName		: upgrade.class.php
   * Description	: 升级日志
   * Author	    : zwy
   * Date			: 2014-6-12
   * Version		: 1.00
   */
class upgrade{
	private $user;
	private $ip;
	private $startDate;
	private $endDate;
	private $code;//查询类型、角色名，账号。。。
	private $codeValue;
	private $pageSize;
	private $curPage;
	private $subtype;
	private $sub_type;
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00300700', $this->user['code'])){
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
		$this->sub_type = get_var_value('sub_type');
		$this->subtype = array (
				'101'=>'坐骑等级',
				'201'=>'武将等级','202'=>'武将资质','203'=>'武将成长值',
				'301'=>'神兵等级',
				'401'=>'星宿星级','402'=>'命格等级',
				'501'=>'玩家等级'
		);
	}

	/**
	 * 查看历史升级日志
	 */
	public function getHistoryLog(){
		$this->startDate = $this->startDate.' 00:00:00';
		$this->endDate = $this->endDate.' 23:59:59';
		if($this->ip&&$this->codeValue) {
			global $t_conf;
			$srever = 's'.$this->ip;
			$Server = F($t_conf[$srever]['db'], $t_conf[$srever]['ip'], $t_conf[$srever]['user'], $t_conf[$srever]['password'], $t_conf[$srever]['port']);
			$user = $Server->fquery("SELECT GUID,RoleName,account from player_table as a LEFT JOIN game_user as b on a.AccountId=b.id where GUID=$this->codeValue");
			
			$obj = D("game".$this->ip);
			$total =  $obj->table('upgrade')->where("date between '{$this->startDate}' and '{$this->endDate}' and sub_type=$this->sub_type and playid=$this->codeValue")->total();
				
			$upgrade = $obj->table('upgrade')
			->where("date between '{$this->startDate}' and '{$this->endDate}' and sub_type=$this->sub_type and playid=$this->codeValue")
			-> limit(intval(($this->curPage-1)*$this->pageSize),intval($this->pageSize))
			->order("pet_id asc,spirit asc,life_id asc,date asc")
			->select();
			
			$wujiang =ITEM."/pet.json";
			$lingjian = ITEM."/SpiritThing.json";
			$wujiangArr = json_decode(file_get_contents($wujiang),true);
			$lingjianArr = json_decode(file_get_contents($lingjian),true);
			
			$life=array('1'=>'命中','2'=>'闪避','3'=>'暴击','4'=>'坚韧','5'=>'物理防御','6'=>'法术防御','7'=>'法术攻击','8'=>'生命');
			
			foreach ($upgrade as $k=>$v){
				$upgrade[$k]['remark'] = '';
				if ($v['pet_id']>0){//武将
					$upgrade[$k]['remark'] = $wujiangArr[$v['pet_id']]['name'];
				}elseif ($v['spirit']>0){//神兵
					foreach ($lingjianArr as $v1){
						if ($v['spirit']==$v1['SpiritID'])
							$upgrade[$k]['remark'] = $v1['Name'];
					}
				}elseif ($v['life_id']>0) {//命格
					$upgrade[$k]['remark'] = $life[$v['life_id']];
				}
				
				if ($this->sub_type==101){
					$upgrade[$k]['value'] = $v['value'].'阶'.$v['star'].'星';
				}
			}
			
			$page = new autoAjaxPage($this->pageSize, $this->curPage, $total, "formAjax", "go","page");
			$pageHtml = $page->getPageHtml();
			echo json_encode(array(
					'result' => $upgrade,
					'user'=>$user,
					'subtype'=>$this->subtype,
					'pageHtml' => $pageHtml
			));
				
		}else {
			echo '1';
		}
	}

	//查看当天升级日记
	public function getCurrentLog(){
		if ($this->ip&&$this->codeValue){
			global $t_conf;
			$srever = 's'.$this->ip;
			$Server = F($t_conf[$srever]['db'], $t_conf[$srever]['ip'], $t_conf[$srever]['user'], $t_conf[$srever]['password'], $t_conf[$srever]['port']);
			$user = $Server->fquery("SELECT GUID,RoleName,account from player_table as a LEFT JOIN game_user as b on a.AccountId=b.id where GUID=$this->codeValue");
			
			$obj = D('game_base');
			$db = $obj -> table('gamedb') -> where("g_flag = 1 and g_id=$this->ip") -> find();
			$path = LPATH . $db['g_ip'] . '/' . date('Y-m-d') . '/';	//日志文件所在目录路径
			$file_type1 = $path.'log-type-16.log';
			$upgrade = $this->getFileDate($file_type1, $this->codeValue,$this->sub_type);
			
			$wujiang =ITEM."/pet.json";
			$lingjian = ITEM."/SpiritThing.json";
			$wujiangArr = json_decode(file_get_contents($wujiang),true);
			$lingjianArr = json_decode(file_get_contents($lingjian),true);
			
			$life=array('1'=>'命中','2'=>'闪避','3'=>'暴击','4'=>'坚韧','5'=>'物理防御','6'=>'法术防御','7'=>'法术攻击','8'=>'生命');
			
			foreach ($upgrade as $k=>$v){
				$upgrade[$k]['date'] = date("Y-m-d H:i:s",$v['time']);
				$upgrade[$k]['remark'] = '';
				if (!isset($v['plan'])){
					$upgrade[$k]['plan']='';
				}
				switch ($this->sub_type){
					case 101:
						$upgrade[$k]['value'] = $v['value'].'阶'.$v['star'].'星';
						break;
					case 201://武将
						$upgrade[$k]['remark'] = $wujiangArr[$v['pet_id']]['name'];
						break;
					case 202:
						$upgrade[$k]['remark'] = $wujiangArr[$v['pet_id']]['name'];
						break;
					case 203:
						$upgrade[$k]['remark'] = $wujiangArr[$v['pet_id']]['name'];
						break;
					case 301://神兵
						foreach ($lingjianArr as $v1){
							if ($v['spirit']==$v1['SpiritID'])
								$upgrade[$k]['remark'] = $v1['Name'];
						}
						break;
					case 402;//命格
						$upgrade[$k]['remark'] = $life[$v['life_id']];
						break;
				}
			}
			
			echo json_encode(array(
					'result' => $upgrade,
					'user'=>$user,
					'subtype'=>$this->subtype
			));
		}else {
			echo '1';
		}
	}

	/**
	 * 获取日记文件数据
	 * @param unknown $path	路径	
	 * @param unknown $playid		玩家id
	 * @param unknown $subtype		搜索类型（铜币、元宝。。。）
	 * @return multitype:mixed |multitype:string
	 */
	public function getFileDate($path,$playid,$subtype){
		if (file_exists($path)) {
			$fp = fopen($path, "r");							//读取日志文件
			$log_data = array();								//保存日志分析信息
			while(!feof($fp)) {
				$line = fgets($fp,2048);
				if(!empty($line)) {
					$INFO  = trim(substr($line, 21));
					$INFO  = str_replace("'", '"', $INFO );
					$arr = json_decode($INFO , true);
					if(is_array($arr)&&$arr['playid']==$playid&&$arr['sub_type']==$subtype) {
						$log_data[] = $arr;
					}
				}
			}
			fclose($fp);										//关闭文件指针
			return $log_data;
		}else {
			return array('no file');
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

 