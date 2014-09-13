<?php
/**
   * FileName		: equipmentoperation.class.php
   * Description	: 玩家装备操作查询
   * Author	    : zwy
   * Date			: 2014-8-8
   */
class equipmentoperation{
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
			if(!in_array('00300900', $this->user['code'])){
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
				'1001'=>'装备强化',
				'1002'=>'装备洗炼',
				'1003'=>'装备分解',
				'1004'=>'装备升级',
		);
	}

	/**
	 * 查看历史
	 */
	public function getHistoryLog(){
		$this->startDate = strtotime($this->startDate);
		$this->endDate = strtotime($this->endDate)+24*60*60;
		if($this->ip&&$this->codeValue) {
			global $t_conf;
			$srever = 's'.$this->ip;
			$Server = F($t_conf[$srever]['db'], $t_conf[$srever]['ip'], $t_conf[$srever]['user'], $t_conf[$srever]['password'], $t_conf[$srever]['port']);
			if ($this->code==2){
				$user = $Server->fquery("SELECT GUID,RoleName from player_table where RoleName='{$this->codeValue}'");
			}elseif ($this->code==3) {
				$user = $Server->fquery("SELECT GUID,RoleName from player_table where GUID=$this->codeValue");
			}
			
			if (empty($user)){
				echo json_encode('用户不存在');exit;
			}
			
			$obj = D("game".$this->ip);
			$where = "time between '{$this->startDate}' and '{$this->endDate}' and sub_type={$this->sub_type} ";
			$where .= " and player_id=".$user[0]['GUID'];
			$total =  $obj->table('equipment_operation')->where($where)->total();

			if (!$total){
				echo 1;exit;
			}
			$equipment = $obj->table('equipment_operation')
			->where($where)
			-> limit(intval(($this->curPage-1)*$this->pageSize),intval($this->pageSize))
			->order("equip_id asc,id asc")
			->select();
			
// 			echo json_encode($equipment);exit;
// 			$goodsPath = ITEM."item.json";//物品配置jsoin文件路径
// 			$goods = json_decode(file_get_contents($goodsPath),true);
			$toolsDetail = $obj->table('tools_detail')->select();
			foreach ($toolsDetail as $k=>$v){
				$goods[$v['t_code']]['name'] = $v['t_name'];
			}
			
			$list = array();
			foreach ($equipment as $k=>$v){
				$list[$k]['date'] = date("Y-m-d H:i:s",$v['time']);
				$list[$k]['player_id'] = $v['player_id'];
				$list[$k]['player_name'] = $user[0]['RoleName'];
				$list[$k]['sub_type'] = $this->subtype[$v['sub_type']];
				$list[$k]['equip_name'] = $goods[$v['equip_id']]['name'];
				if ($v['pos_type']==1){
					$list[$k]['position'] = '身上('.$v['pos'].')';
				}else {
					$list[$k]['position'] = '背包('.$v['pos'].')';
				}
				$list[$k]['cost_coin'] = $v['cost_coin'];
				if ($v['prop_id']){
					$list[$k]['goods'] = $goods[$v['prop_id']]['name'];
				}else {
					$list[$k]['goods'] = '';
				}
				if ($this->sub_type==1001){
					$list[$k]['before'] = "强化加".$v['old_level'];
					$list[$k]['after'] = "强化加".$v['now_level'];
					$list[$k]['remark'] = "强化总次数".$v['count'];
				}
				if ($this->sub_type==1002){
					$attrPath = ITEM."attributesName.json";//属性配置文件路径
					$attr = json_decode(file_get_contents($attrPath),true);
					if ($v['pre_attr']){
						$str = '';
						$tem1 = json_decode($v['pre_attr'],true);
						foreach ($tem1 as $tk1=>$tv1){
							$tem2 = explode(',', $tv1);
							$str .= '属性'.($tk1+1).':'.$attr[$tem2[0]]['sName'].'+'.$tem2[1].'<br/>';
						}
						$list[$k]['before'] = $str;
					}else {
						$list[$k]['before'] = "";
					}
					if ($v['after_attr']){
						$str = '';
						$tem1 = json_decode($v['after_attr'],true);
						foreach ($tem1 as $tk1=>$tv1){
							$str .= '<span style="color:red;">第'.($tk1+1).'组属性</span><br/>';
							foreach ($tv1 as $tk2=>$tv2){
								$tem2 = explode(',', $tv2);
								$str .= '属性'.($tk2+1).':'.$attr[$tem2[0]]['sName'].'+'.$tem2[1].'<br/>';
							}
						}
						$list[$k]['after'] = $str;
					}else {
						$list[$k]['after'] = "";
					}
					$str = '';
					if ($v['lock_attr']){
						$str .= '<span style="color:red;">锁住属性</span><br/>';
						$tem1 = json_decode($v['lock_attr'],true);
						foreach ($tem1 as $tk1=>$tv1){
							if (empty($tv1)){
								continue;
							}
							$tem2 = explode(',', $tv1);
							$str .= '属性'.($tk1+1).':'.$attr[$tem2[0]]['sName'].'+'.$tem2[1].'<br/>';
						}
					}
					if ($v['record_result']){
						$str .= '<span style="color:red;">替换时间：</span>'.date('Y-m-d H:i:s',$v['record_time']);
					}else {
						$str .= '<span style="color:red;">没有替换</span>';
					}
					$list[$k]['remark'] = $str;
				}
				if ($this->sub_type==1003){
					$list[$k]['before'] = $goods[$v['equip_id']]['name'];
					$tem1 = json_decode($v['gainlist'],true);
					$str = '获得：<br/>';
					foreach ($tem1 as $tk1=>$tv1){
						$tem2 = explode(',', $tv1);
						$str .= "物品".($tk1+1).":".$goods[$tem2[0]]['name'].';数量：'.$tem2[1].'<br/>';
					}
					$list[$k]['after'] = $str;
					$list[$k]['remark'] = "失去装备:".$goods[$v['equip_id']]['name'];
				}
				if ($this->sub_type==1004){
					$list[$k]['before'] = $goods[$v['equip_id']]['name'];
					$list[$k]['after'] = $goods[$v['equip_newid']]['name'];
					$list[$k]['remark'] = "消耗".$v['prop_num'].'个'.$goods[$v['prop_id']]['name'].$v['prop_num2'].'个'.$goods[$v['prop_id2']]['name'];
				}
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
			global $t_conf;
			$srever = 's'.$this->ip;
			$Server = F($t_conf[$srever]['db'], $t_conf[$srever]['ip'], $t_conf[$srever]['user'], $t_conf[$srever]['password'], $t_conf[$srever]['port']);
			if ($this->code==2){
				$user = $Server->fquery("SELECT GUID,RoleName from player_table where RoleName='{$this->codeValue}'");
			}elseif ($this->code==3) {
				$user = $Server->fquery("SELECT GUID,RoleName from player_table where GUID=$this->codeValue");
			}
				
			if (empty($user)){
				echo json_encode('用户不存在');exit;
			}
			
			$obj = D('game_base');
			$db = $obj -> table('gamedb') -> where("g_flag = 1 and g_id=$this->ip") -> find();
			$path = LPATH . $db['g_ip'] . '/' . date('Y-m-d') . '/';	//日志文件所在目录路径
			$filePath = $path.'log-type-19.log';
// 			$filePath = LPATH.'192.168.0.64/2014-07-29/log-type-19.log';
			$data = $this->getFileDate($filePath, $user[0]['GUID'],$this->sub_type);
			if (empty($data)){
				echo 1;exit;
			}
// 			$goodsPath = ITEM."item.json";//物品配置jsoin文件路径
// 			$goods = json_decode(file_get_contents($goodsPath),true);
			$toolsDetail = $obj->table('tools_detail')->select();
			foreach ($toolsDetail as $k=>$v){
				$goods[$v['t_code']]['name'] = $v['t_name'];
			}
			
			$list = array();
			foreach ($data as $k=>$v){
				$list[$k]['date'] = date("Y-m-d H:i:s",$v['time']);
				$list[$k]['player_id'] = $v['player_id'];
				$list[$k]['player_name'] = $user[0]['RoleName'];
				$list[$k]['sub_type'] = $this->subtype[$v['sub_type']];
				$list[$k]['equip_name'] = $goods[$v['equip_id']]['name'];
				if ($v['pos_type']==1){
					$list[$k]['position'] = '身上('.$v['pos'].')';
				}else {
					$list[$k]['position'] = '背包('.$v['pos'].')';
				}
				$list[$k]['cost_coin'] = $v['cost_coin'];
				if (isset($v['prop_id'])&&$v['prop_id']){
					$list[$k]['goods'] = $goods[$v['prop_id']]['name'];
				}else {
					$list[$k]['goods'] = '';
				}
				if ($this->sub_type==1001){
					$list[$k]['before'] = "强化加".$v['old_level'];
					$list[$k]['after'] = "强化加".$v['now_level'];
					$list[$k]['remark'] = "强化总次数".$v['count'];
				}
				if ($this->sub_type==1002){
					$attrPath = ITEM."attributesName.json";//属性配置文件路径
					$attr = json_decode(file_get_contents($attrPath),true);
					if (isset($v['pre_attr'])){
						$str = '';
						$tem1 = explode('|', $v['pre_attr']);
						foreach ($tem1 as $tk1=>$tv1){
							$tem2 = explode(',', $tv1);
							$str .= '属性'.($tk1+1).':'.$attr[$tem2[0]]['sName'].'+'.$tem2[1].'<br/>';
						}
						$list[$k]['before'] = $str;
					}else {
						$list[$k]['before'] = "";
					}
					if (isset($v['after_attr'])){
						$str = '';
						$tem1 = explode('#', $v['after_attr']);
						foreach ($tem1 as $tk1=>$tv1){
							$str .= '<span style="color:red;">第'.($tk1+1).'组属性</span><br/>';
							$tv1 = explode('|', $tv1);
							foreach ($tv1 as $tk2=>$tv2){
								$tem2 = explode(',', $tv2);
								$str .= '属性'.($tk2+1).':'.$attr[$tem2[0]]['sName'].'+'.$tem2[1].'<br/>';
							}
						}
						$list[$k]['after'] = $str;
					}else {
						$list[$k]['after'] = "";
					}
					$str = '';
					if (isset($v['lock_attr'])){
						$str .= '<span style="color:red;">锁住属性</span><br/>';
						$tem1 = explode('|', $v['lock_attr']);
						foreach ($tem1 as $tk1=>$tv1){
							if (empty($tv1)){
								continue;
							}
							$tem2 = explode(',', $tv1);
							$str .= '属性'.($tk1+1).':'.$attr[$tem2[0]]['sName'].'+'.$tem2[1].'<br/>';
						}
					}
					if (isset($v['record_result'])){
						$str .= '<span style="color:red;">替换时间：</span>'.date('Y-m-d H:i:s',$v['record_time']);
					}else {
						$str .= '<span style="color:red;">没有替换</span>';
					}
					$list[$k]['remark'] = $str;
				}
				if ($this->sub_type==1003){
					$list[$k]['before'] = $goods[$v['equip_id']]['name'];
					$tem1 = explode('|', $v['gainList']);
					$str = '获得：<br/>';
					foreach ($tem1 as $tk1=>$tv1){
						$tem2 = explode(',', $tv1);
						$str .= "物品".($tk1+1).":".$goods[$tem2[0]]['name'].';数量：'.$tem2[1].'<br/>';
					}
					$list[$k]['after'] = $str;
					$list[$k]['remark'] = "失去装备:".$goods[$v['equip_id']]['name'];
				}
				if ($this->sub_type==1004){
					$list[$k]['before'] = $goods[$v['equip_id']]['name'];
					$list[$k]['after'] = $goods[$v['equip_newId']]['name'];
					$list[$k]['remark'] = "消耗".$v['prop_num'].'个'.$goods[$v['prop_id']]['name'].$v['prop_num2'].'个'.$goods[$v['prop_id2']]['name'];
				}
			}
			
			echo json_encode(array(
					'result' => $list
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
					if(is_array($arr)&&$arr['sub_type']==$subtype) {
						if ($arr['player_id']==$playid) {
							$log_data[] = $arr;
						}
					}
				}
			}
			fclose($fp);										//关闭文件指针
			return $log_data;
		}else {
			echo 3;exit;
			return array();
		}
	}
}

 