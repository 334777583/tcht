<?php
/**
   * FileName		: playerEmail.class.php
   * Description	: 玩家击杀boss查询
   * Author	    : zwy
   * Date			: 2014-11-5
   */
class playerEmail{
	private $user;
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00301100', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}

	public function getData(){
		$serverId = get_var_value('serverId');
		$sendName = get_var_value('sendname');
		$recieveName = get_var_value('recievename');
		$goodsName = get_var_value('goodsname');
		$pageSize = get_var_value('pageSize');
		$curPage = get_var_value('curPage');
		
		if (empty($serverId)){
			echo '0';exit;
		}
		
		global $t_conf;
		$srever = 's'.$serverId;
		$point = DF($t_conf[$srever]);
		
		$obj = D("game".$serverId);
		
		$where = '(sub_type=2000 or sub_type=2001)';
		
		$map = '';
		$goodsArr = array();
		if (!empty($sendName)){
			$map = "send_name like '%{$sendName}%'";
		}
		if (!empty($recieveName)){
			if (empty($map)){
				$map .= " recieve_name like '%{$recieveName}%'";
			}else {
				$map .= " or recieve_name like '%{$recieveName}%'";
			}
		}
		if (!empty($goodsName)){
			$goods = $obj->table('tools_detail')->where("t_code like '%{$goodsName}%' or t_name like '%{$goodsName}%'")->select();
			if (!empty($goods)){
				$goodsIds = array();
				foreach ($goods as $v){
					$goodsIds[] = $v['t_code'];
					$goodsArr[$v['t_code']] = $v['t_name'];
				}
				unset($goods);
				if (empty($map)){
					$map .= " item_id in (".implode(',', $goodsIds).")";
				}else {
					$map .= " or item_id in (".implode(',', $goodsIds).")";
				}
			}
		}
		
		if (!empty($map)){
			$where .= " and ($map)";
		}
		
		$total = $obj->table("water_log")->where($where)->total();
		
		if (empty($total)){
			echo '1';exit;
		}
		$totalPage = ceil($total/$pageSize);
		if ($curPage>$totalPage){
			$curPage = $totalPage;
		}
		
		$list = $obj->table("water_log")->where($where)
		-> limit(intval(($curPage-1)*$pageSize),intval($pageSize))->order("time desc")->select();
		
		if (empty($goodsArr)){
			$goods = $obj->table('tools_detail')->select();
			foreach ($goods as $v){
				$goodsArr[$v['t_code']] = $v['t_name'];
			}
			unset($goods);
		}
		
		//匹配物品名称
		foreach ($list as $k=>$v){
			$list[$k]['datetime'] = date('Y-m-d H:i:s',$v['time']);
			
			if (isset($goodsArr[$v['item_id']])){
				$list[$k]['goodsname'] = $goodsArr[$v['item_id']];
			}else {
				$list[$k]['goodsname'] = $v['item_id'];
			}
			
			if (empty($v['send_name'])){
				$list[$k]['send_name'] = '后台发送邮件';
			}
			
			if ($v['sub_type']==2000){
				$list[$k]['type'] = '发送邮件';
			}elseif ($v['sub_type']==2001){
				$list[$k]['type'] = '提取邮件';
			}else {
				$list[$k]['type'] = '未知';
			}
		}
		
		$page = new autoAjaxPage($pageSize, $curPage, $total, "getData", "go","page");
		$pageHtml = $page->getPageHtml();
		echo json_encode(array(
				'list' => $list,
				'pageHtml' => $pageHtml
		));exit;
	}
	
	public function getCurData(){
		$serverId = get_var_value('serverId',1,'intval');
		$sendName = get_var_value('sendname');
		$recieveName = get_var_value('recievename');
		$goodsName = get_var_value('goodsname');
	
		if (empty($serverId)){
			echo '0';exit;
		}
		
		$db = D('game_base')-> table('gamedb')-> where("g_flag = 1 and g_id=$serverId")-> find();
		$path = LPATH . $db['g_ip'] . '/' . date('Y-m-d') . '/';	//日志文件所在目录路径
		$filePath = $path.'log-type-17.log';
		//$filePath = LPATH.'192.168.0.64/2014-07-29/log-type-17.log';
		if (!is_file($filePath)){
			echo '2';exit;
		}
		
		$searchKey = array('sub_type');
		$searchVal = array(array(2000,20001));
		if (!empty($sendName)){
			$searchKey[] = 'send_name';
			$searchVal[] = $sendName;
		}
		if (!empty($recieveName)){
			$searchKey[] = 'recieve_name';
			$searchVal[] = $recieveName;
		}
		$goodsArr = array();
		if (!empty($goodsName)){
			$goods = D('game'.$serverId)->table('tools_detail')->where("t_code like '%{$goodsName}%' or t_name like '%{$goodsName}%'")->select();
			if (!empty($goods)){
				$goodsIds = array();
				foreach ($goods as $v){
					$goodsIds[] = $v['t_code'];
					$goodsArr[$v['t_code']] = $v['t_name'];
				}
				unset($goods);
				$searchKey[] = 'item_id';
				$searchVal[] = $goodsIds;
			}
		}
		
		$list = $this->getFileData($filePath,$searchKey,$searchVal);
		if (empty($list)){
			echo '3';exit;
		}
		
		if (empty($goodsArr)){
			$goods = D('game'.$serverId)->table('tools_detail')->select();
			foreach ($goods as $v){
				$goodsArr[$v['t_code']] = $v['t_name'];
			}
			unset($goods);
		}
	
		//匹配物品名称
		foreach ($list as $k=>$v){
			$list[$k]['datetime'] = date('Y-m-d H:i:s',$v['time']);
				
			if (isset($goodsArr[$v['item_id']])){
				$list[$k]['goodsname'] = $goodsArr[$v['item_id']];
			}else {
				$list[$k]['goodsname'] = $v['item_id'];
			}
				
			if (empty($v['send_name'])){
				$list[$k]['send_name'] = '后台发送邮件';
			}
				
			if ($v['sub_type']==2000){
				$list[$k]['type'] = '发送邮件';
			}elseif ($v['sub_type']==2001){
				$list[$k]['type'] = '提取邮件';
			}else {
				$list[$k]['type'] = '未知';
			}
		}
		echo json_encode(array('list'=>$list));exit;
	}
	
	/**
	 * 获取日记文件数据
	 * @param string $path	文件路径
	 * @param string $field	条件
	 * @param string $value 值
	 * @return multitype: 返回数据
	 */
	private function getFileData($path='',$field=array(),$value=array()){
		if (file_exists($path)) {
			$fp = fopen($path, "r");	//读取日志文件
			$logData = array();	//保存日志分析信息
			while(!feof($fp)) {
				$line = fgets($fp,2048);
				if(!empty($line)) {
					$INFO  = trim(substr($line, 21));
					$INFO  = str_replace("'", '"', $INFO );
					$arr = json_decode($INFO , true);
					if(is_array($arr)) {
						if (empty($field)||empty($value)){
							$logData[] = $arr;
						}else {
							$bool = true;
							foreach ($field as $k=>$v){
								if (is_array($value[$k])){
									$bool = $bool&&(in_array($arr[$v], $value[$k]));
								}else {
									$bool = $bool&&($arr[$v]==$value[$k]);
								}
							}
							if ($bool){
								$logData[] = $arr;
							}
						}
					}
				}
			}
			fclose($fp);										//关闭文件指针
			return $logData;
		}else {
			return array();
		}
	}
}