<?php
/**
 * FileName: gmgrow.class.php
 * Description:用户成长日志页面
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-3-28 上午11:36:42
 * Version:1.00
 */
class gmgrow{
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
	 * 服务器IP
	 * @var string
	 */
	private $ip;
	
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
	 * 检索模式（0：账号；1：昵称；2：ID）
	 * @var int
	 */
	private $type;
	
	/**
	 * 查询内容
	 * @var string;
	 */
	private $text;
	
	/**
	 * 是否模糊查询（0：是；1：否）
	 * @var int
	 */
	private $fuzzy;
	
	/**
	 * gm接口类
	 * @var class
	 */
	public $gm;
	
	/**
	 * 用户信息
	 */
	public $user;
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo "not available!";
			exit();
		}else{
			if(!in_array("00401000", $this->user["code"])){
				echo "not available!";
				exit();
			}
		}
		$this->pageSize = get_var_value("pageSize") == NULL?2:get_var_value("pageSize");
		$this->curPage =  get_var_value("curPage") == NULL?1:get_var_value("curPage");
		$this->ip =  get_var_value("ip") == NULL?-1:get_var_value("ip");
		$this->type =  get_var_value("type") == NULL?0:get_var_value("type");
		$this->text =  get_var_value("text") == NULL?"":get_var_value("text");
		$this->fuzzy =  get_var_value("fuzzy") == NULL?0:get_var_value("fuzzy");
		$this->gm = new autogm();
		$this->startdate = get_var_value("startDate") == NULL?date("Y-m-d",strtotime("-7 day")):get_var_value("startDate");
		$this->enddate = get_var_value("endDate") == NULL?date("Y-m-d"):get_var_value("endDate");
	}
	
	/**
	 * 获取玩家信息
	 */
	public function getrole(){
		$obj = D('game'.$this->ip);
		$num = get_var_value('num');
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rolelist = $point->field('guid,accountid,serverid,rolename,seconddata,roleheadid,level,sceneid,camp,sex,carrer,guildid,viplevel,gmlevel')->table('player_table')->order('level desc')->select();
		$guild = $point ->field('GuildId,GuildName')->table('guild')->select();
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj->field('o_userid,o_date,o_level')->table('online_sec')->where(array('o_date >='=> $this->startdate.' 00:00:00','o_date <='=> $this->enddate.' 23：59：59'))->order('o_level desc')->limit(0,$time)->select();	
		if(!empty($list)){
			foreach($list as $key => $value){
				if(!empty($rolelist[$key]['guid']) && $rolelist[$key]['guid'] = $value['o_userid']){
					$role[$key] = $rolelist[$key];
					if($rolelist[$key]['carrer'] == 1){
						$role[$key]['carrer'] = '战士';
					}else if($rolelist[$key]['carrer'] == 2){
						$role[$key]['carrer'] = '剑客';
					} else if($rolelist[$key]['carrer'] == 3){
						$role[$key]['carrer'] = '谋士';
					}else{
						$role[$key]['carrer'] = '无职业';
					}
					$role[$key]['guid']=$value['o_userid'];
					$role[$key]['on_time']=$value['o_date'];
					//$role[$key]['country']='暂无';
					$role[$key]['boss']=0;
					$role[$key]['fried']=0;
					$role[$key]['kill']=0;
					$role[$key]['die']=0;
					if($rolelist[$key]['camp'] == 1){
						$role[$key]['country']='魏';
					}else if($rolelist[$key]['camp'] == 2){
						$role[$key]['country']='蜀';
					}else if($rolelist[$key]['camp'] == 3){
						$role[$key]['country']='吴';
					}else{
						$role[$key]['country']='无';
					}
					$role[$key]['guildid'] =  '无';
					if(!empty($guild[$key])){
						if($rolelist[$key]['guildid'] = $guild[$key]['GuildId']){
							$role[$key]['guildid'] =  $guild[$key]['GuildName'];
						}
					
					}
				}
			}
			echo json_encode(array('list'=>$role,'startDate'=>$this->startdate,'endDate'=>$this->enddate));
			exit;
		}else{
			echo "error";
			exit;
		}
		
		
	}
	
	/**
	 * 获取战斗信息
	 */
	public function getbattle(){
		
		list($ip, $id, $sid) = autoConfig::getConfig($this->ip);
		$obj = D('game'.$this->ip);
		$num = get_var_value('num');
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rolelist = $point->field('guid,accountid,serverid,rolename,seconddata,roleheadid,level,sex,carrer')->table('player_table')->order('level desc')->select();
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj->table('online_sec')->where(array('o_date >='=> $this->startdate.' 00:00:00','o_date <='=> $this->enddate.' 23：59：59'))->order('o_level desc')->limit(0,$time)->select();	
		
		if($list) {
			foreach($list as $key => $item){
				if(!empty($rolelist[$key])) {
					$data = $rolelist[$key]['seconddata'];
				
					$da = json_decode($data,true);
					if(is_array($da) && $item['o_userid'] = $rolelist[$key]['guid']){
						$bag[$key]['uid']=$rolelist[$key]['guid'];
						$bag[$key]['o_date'] = date('Y-m-d',strtotime($item['o_date']));
						$bag[$key]['accountid']=$rolelist[$key]['accountid'];
						$bag[$key]['u_name']=$rolelist[$key]['rolename'];
						$bag[$key]['BattlePower'] = $da['BattlePower'];
						$bag[$key]['HP'] = $da['HP'];
						$bag[$key]['MP'] = $da['MP'];
						$bag[$key]['PhyAttack'] = $da['PhyAttack'];
						$bag[$key]['PhyDefense'] = $da['PhyDefense'];
						$bag[$key]['MagicDefense'] = $da['MagicDefense'];
						$bag[$key]['MagicAttack'] = $da['MagicAttack'];
						$bag[$key]['Power'] = $da['Power'];
						$bag[$key]['Nimble'] = $da['Nimble'];
						$bag[$key]['Physical'] = $da['Physical'];
						$bag[$key]['Intelligence'] = $da['Intelligence'];
						$bag[$key]['Spirit'] = $da['Spirit'];
						$bag[$key]['Hit'] = $da['Hit'];
						$bag[$key]['Dodge'] = $da['Dodge'];
						$bag[$key]['Cruel'] = $da['Cruel'];
						$bag[$key]['GodWakan'] = $da['GodWakan'];
						$bag[$key]['Tenacity'] = $da['DeCruel'];
						$bag[$key]['Minus'] = $da['ReHurt'];
					}
				}
			}
		}
		$result = array(
					'list' => $bag,
					'startDate'=>$this->startdate,
					'endDate'=>$this->enddate
			);
		echo json_encode($result);
		exit;
		
		
	}
	
	/**
	 * 获取装备信息
	 */
	public function getequip(){
		list($ip, $id, $sid) = autoConfig::getConfig($this->ip);
		$obj = D('game'.$this->ip);
		$num = get_var_value('num');
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rolelist = $point->field('guid,accountid,serverid,rolename,seconddata,roleheadid,level,sex,carrer,equipsdata')->table('player_table')->order('level desc')->select();
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj->table('online_sec')->where(array('o_date >='=> $this->startdate.' 00:00:00','o_date <='=> $this->enddate.' 23：59：59'))->order('o_level desc')->limit(0,$time)->select();	
		
		$file =ITEM."/equipment.json";
		if (file_exists($file)) {
			$get_str = '';//获取道具日志文件内容	
			clearstatcache();
			$fs = fopen($file,'r');
			while(!feof($fs)){
					$get_str .= fgets($fs);
				}
			fclose($fs);
			$str = json_decode($get_str,true);
			$resource = '';
			
			if($list) {
				foreach($list as $k => $item){
				if(!empty($rolelist[$k]) && $rolelist[$k]['accountid']>0){
					if(!empty($rolelist[$k])){
						$data = $rolelist[$k]['equipsdata'];
					}
					$da = '';
					$da = json_decode($data,true);
					$da = $da['BagCellList'];
					
					$bag[$k]['head'] = "空";
					$bag[$k]['headlevel'] = 0;
					$bag[$k]['clothes'] = "空";
					$bag[$k]['clotheslevel'] = 0;
					$bag[$k]['belt'] = "空";
					$bag[$k]['beltlevel'] = 0;
					$bag[$k]['necklace'] = "空";
					$bag[$k]['necklacelevel'] = 0;
					$bag[$k]['bracers'] = "空";
					$bag[$k]['bracerslevel'] = 0;
					$bag[$k]['ring'] = "空";
					$bag[$k]['ringlevel'] = 0;
					$bag[$k]['arm'] = "空";
					$bag[$k]['armlevel'] = 0;
					$bag[$k]['shoes'] = "空";
					$bag[$k]['shoeslevel'] = 0;
					$bag[$k]['pant'] = "空";
					$bag[$k]['pantlevel'] = 0;
					
					if(!empty($da) && $item['o_userid']=$rolelist[$k]['guid']){
						
						$bag[$k]['uid']=$rolelist[$k]['guid'];
						$bag[$k]['o_date'] = date('Y-m-d',strtotime($item['o_date']));
						$bag[$k]['accountid']=$rolelist[$k]['accountid'];
						$bag[$k]['u_name']=$rolelist[$k]['rolename'];
						foreach($da as $key => $value){
							if($da[$key]['CellID'] == 0){	//头部
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['head'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['headlevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 1){	//衣服
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['clothes'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['clotheslevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 2){	//腰带
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['belt'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['beltlevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 3){	//项链
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['necklace'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['necklacelevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 12){	//护腕
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['bracers'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['bracerslevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 8){	//戒指
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['ring'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['ringlevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 10){	//鞋子
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['shoes'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['shoeslevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 6){	//武器
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['arm'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['armlevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 11){	//裤子
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['pant'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['pantlevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							
						}
					}
				}
				}
			}
			$result = array(
						'list' =>  $bag,
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate
				);
			echo json_encode($result);
			exit;
		}else{
			echo "文件不存在";
			exit;
		}
		
	}
	
	
	/**
	 * 获取武将属性
	 */
	public function getpie() {
		
		list($ip, $id, $sid) = autoConfig::getConfig($this->ip);
		$obj = D('game'.$this->ip);
		$num = get_var_value('num');
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rolelist = $point->field('guid,accountid,serverid,rolename,seconddata,roleheadid,level,sex,carrer,rolepet')->table('player_table')->order('level desc')->select();
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj->table('online_sec')->where(array('o_date >='=> $this->startdate.' 00:00:00','o_date <='=> $this->enddate.' 23：59：59'))->order('o_level desc')->limit(0,$time)->select();	
		//fopen
		// $skfile = TPATH."/zs/skill.json";
		$skfile = ITEM."/skill.json";
		if (file_exists($skfile)) {
			$get_str = '';//获取道具日志文件内容	
			clearstatcache();
			$fs = fopen($skfile,'r');
			while(!feof($fs)){
					$get_str .= fgets($fs);
				}
			fclose($fs);
			$skill_json = json_decode($get_str,true);
			
		}
		//print_R($skill_json);
		$petfile = ITEM."/pet.json";
		if (file_exists($petfile)) {
			$get_str = '';//获取道具日志文件内容	
			clearstatcache();
			$fs = fopen($petfile,'r');
			while(!feof($fs)){
					$get_str .= fgets($fs);
				}
			fclose($fs);
			$pet_json = json_decode($get_str,true);
			
		}
		$bookfile = ITEM."/PetBook.json";
		if (file_exists($bookfile)) {
			$get_str = '';//获取道具日志文件内容	
			clearstatcache();
			$fs = fopen($bookfile,'r');
			while(!feof($fs)){
					$get_str .= fgets($fs);
				}
			fclose($fs);
			$book_json = json_decode($get_str,true);
			
		}
		foreach($book_json as $key => $value){
			foreach($value['LevelData'] as $k => $v){
				$book[$key][$k]['Upgradealue'] = $v['Upgradealue'];
				$book[$key][$k]['level']=$v['Level'];
			}
			$book[$key]['BookName'] = $value['BookName'];
		}
		// print_R($book_json);
		// exit;
		if($list) {
			foreach($list as $k =>$item){
				if(!empty($rolelist[$k])){
					$data = $rolelist[$k]['rolepet'];
					$da = json_decode($data,true);
					$pet = $da['PetList'];
					$book = $da['PetBooks'];
					if($rolelist[$k]['accountid']>0 && $rolelist[$k]['guid'] = $item['o_userid']){
						$bag[$k]['p_date'] = date('Y-m-d',strtotime($item['o_date']));
						$bag[$k]['account'] = $rolelist[$k]['accountid'];
						$bag[$k]['playid'] = $rolelist[$k]['guid'];
						$bag[$k]['rolename'] = $rolelist[$k]['rolename'];
						
						if(!empty($pet) && $rolelist[$k]['guid']>0){
							foreach($pet as $key =>$value){
							if($value['PetState'] == 0){
								$bag[$k]['info'][$key]=$value['SecondData'];
								$bag[$k]['info'][$key]['NO'] = $key +1;
								if($value['templateid'] == $pet_json[$value['templateid']]['iconId']){
									$bag[$k]['info'][$key]['templateid'] = $pet_json[$value['templateid']]['name'];
								}
								$bag[$k]['info'][$key]['PetGrow'] = 0;
								if(!empty($value['PetGrow'])){
									foreach($value['PetGrow'] as $bbq){
										$petgrow = $bbq['growlevel'];
										$petgrow += $petgrow; 
										$bag[$k]['info'][$key]['PetGrow'] = round($petgrow/6);
									}
									
								}
								
								$bag[$k]['info'][$key]['Level']=$value['Level'];
								$bag[$k]['info'][$key]['TrunCount']=$value['TrunCount'];
								
								$bag[$k]['info'][$key]['gif2'] = '无';
								$bag[$k]['info'][$key]['gif1'] = '无';
								if(!empty($value['PetGiftSkillList']['v0']['nSkillID'])){
									if($value['PetGiftSkillList']['v0']['nSkillID'] = $skill_json[$value['PetGiftSkillList']['v0']['nSkillID']]['SkilltemplateID']){
										$bag[$k]['info'][$key]['gif1'] = $skill_json[$value['PetGiftSkillList']['v0']['nSkillID']]['SkillName'];
										$bag[$k]['info'][$key]['gif1level'] = $skill_json[$value['PetGiftSkillList']['v0']['nSkillID']]['SkillLevel'];
									}
								}
								if(!empty($value['PetGiftSkillList']['v1']['nSkillID'])){
									if($value['PetGiftSkillList']['v1']['nSkillID'] = $skill_json[$value['PetGiftSkillList']['v1']['nSkillID']]['SkilltemplateID']){
										$bag[$k]['info'][$key]['gif2'] = $skill_json[$value['PetGiftSkillList']['v1']['nSkillID']]['SkillName'];
										$bag[$k]['info'][$key]['gif2level'] = $skill_json[$value['PetGiftSkillList']['v1']['nSkillID']]['SkillLevel'];
									}
								}
								// $bag[$key]['gif1']=$value['PetGiftSkillList']['v0']['nSkillID'];
								// $bag[$key]['gif2']=$value['PetGiftSkillList']['v1']['nSkillID'];
								if(!empty($value['BattleData'][0]['SkillTemplateId'])){
									$bag[$k]['info'][$key]['bskill1']=$value['BattleData'][0]['SkillTemplateId'];
								}else{
									$bag[$k]['info'][$key]['bskill1'] =0;
								}
								if(!empty($value['BattleData'][0]['SkillTemplateId'])){
									$bag[$k]['info'][$key]['bskill2']=$value['BattleData'][1]['SkillTemplateId'];
								}else{
									$bag[$k]['info'][$key]['bskill2'] =0;
								}
								if(!empty($value['BattleData'][0]['SkillTemplateId'])){
									$bag[$k]['info'][$key]['bskill3']=$value['BattleData'][2]['SkillTemplateId'];
								}else{
									$bag[$k]['info'][$key]['bskill3'] =0;
								}
								
								$bag[$key]['RoleBattleAttributeValue']=0;
								foreach($book as $i =>$v){
									if($v['nBookID'] > 0 && $v['nBookID'] = $book_json[$v['nBookID']-10000]['BookID'] && $v['nLevel'] = $book_json[$v['nBookID']-10000]['LevelData'][$key]['Level']){
										$bag[$k]['info'][$key]['points'.$i] =  $book_json[$v['nBookID']-10000]['LevelData'][$key]['Upgradealue'];
										$bag[$k]['info'][$key]['book'.$i] = $book_json[$v['nBookID']-10000]['BookName'];
										$bag[$k]['info'][$key]['nLevel'.$i]=$v['nLevel'];
									}else{
										$bag[$k]['info'][$key]['points'.$i] = 0;
										$bag[$k]['info'][$key]['book'.$i] ='无';
										$bag[$k]['info'][$key]['nLevel'.$i] = 0;
									}
								}
								
								// if(!empty($petgrow)){
									// $bag[$key]['Growone']=$value['PetGrow']['v0']['growlevel'];
									// $bag[$key]['Growtwo']=$value['PetGrow']['v1']['growlevel'];
									// $bag[$key]['Growthree']=$value['PetGrow']['v2']['growlevel'];
									// $bag[$key]['Growfour']=$value['PetGrow']['v3']['growlevel'];
									// $bag[$key]['Growfive']=$value['PetGrow']['v4']['growlevel'];
									// $bag[$key]['Growsix']=$value['PetGrow']['v5']['growlevel'];
									
								// }else{
									$bag[$k]['info'][$key]['petGrow'] = 0;
								//}
								// if(is_array($value['PetEquipList'])){
									// $bag[$key]['']=$value['PetEquipList']['v0'][''];
								// }
							}
						// }else{
							// $bag = array('fuck'=>'fuck');
							}
						}
						
					}
				}
			}
		}
		$result = array(
					'list' =>  $bag,
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate
			);
		echo json_encode($result);
		exit;
		
		
	}
	
	/**
	 * 获取坐骑信息
	 */
	public function getskill(){
		list($ip, $id, $sid) = autoConfig::getConfig($this->ip);
		$obj = D('game'.$this->ip);
		$num = get_var_value('num');
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rolelist = $point->field('guid,accountid,serverid,rolename,seconddata,roleheadid,level,sex,carrer,battledata')->table('player_table')->order('level desc')->select();
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj->table('online_sec')->where(array('o_date >='=> $this->startdate.' 00:00:00','o_date <='=> $this->enddate.' 23：59：59'))->order('o_level desc')->limit(0,$time)->select();	
		
		
		$file =ITEM."/skill.json";
		if (file_exists($file)) {
			$get_str = '';//获取道具日志文件内容	
			clearstatcache();
			$fs = fopen($file,'r');
			while(!feof($fs)){
					$get_str .= fgets($fs);
				}
			fclose($fs);
			$str = json_decode($get_str,true);
			$resource = '';
			foreach($str as $k => $value){
				$resource[$k]['t_code']= $k;
				$resource[$k]['t_name']= $value['SkillName'];
				$resource[$k]['t_level'] = $value['SkillLevel'];
			}
			if($list) {
				foreach($list as $k =>$item){
					if(!empty($rolelist[$k]) && $item['o_userid'] =  $rolelist[$k]['guid']) {
						$data = $rolelist[$k]['battledata'];
					}
					$da = json_decode($data,true);
					$da = $da['SkillList'];
					$ga = '';
					foreach($da as $key => $value){
						
							if( $da[$key]['SkillTemplateId'] >=4000101 && $da[$key]['SkillTemplateId'] <=4001205 ){
								//践踏
								if($da[$key]['SkillTemplateId']>=4000101 && $da[$key]['SkillTemplateId']<= 4000105){
									//$bag[$key]['qt'] = $resource[$value['SkillTemplateId']]['t_name'];
									$bag[$k]['qtlevel'] = $resource[$value['SkillTemplateId']]['t_level'];
								}else{
									//$bag[$key]['qt'] = 0;
									$bag[$k]['qtlevel'] = 0;
								}
								//冲锋
								if($da[$key]['SkillTemplateId']>=4000201 && $da[$key]['SkillTemplateId']<= 4000205){
									//$bag[$key]['cf'] = $resource[$value['SkillTemplateId']]['t_name'];
									$bag[$k]['cflevel'] = $resource[$value['SkillTemplateId']]['t_level'];
								}else{
									//$bag[$key]['cf'] = 0;
									$bag[$k]['cflevel'] = 0;
								}
								//千里奔袭
								if($da[$key]['SkillTemplateId']>=4000301 && $da[$key]['SkillTemplateId']<= 4000305){
									//$bag[$key]['ql'] = $resource[$value['SkillTemplateId']]['t_name'];
									$bag[$k]['qllevel'] = $resource[$value['SkillTemplateId']]['t_level'];
								}else{
									//$bag[$key]['ql'] = 0;
									$bag[$k]['qllevel'] = 0;
								}
								//疾风破
								if($da[$key]['SkillTemplateId']>=4000401 && $da[$key]['SkillTemplateId']<= 4000405){
									//$bag[$key]['jf'] = $resource[$value['SkillTemplateId']]['t_name'];
									$bag[$k]['jflevel'] = $resource[$value['SkillTemplateId']]['t_level'];
								}else{
									//$bag[$key]['jf'] = 0;
									$bag[$k]['jflevel'] = 0;
								}
								//惊雷破
								if($da[$key]['SkillTemplateId']>=4000501 && $da[$key]['SkillTemplateId']<= 4000505){
									//$bag[$key]['jl'] = $resource[$value['SkillTemplateId']]['t_name'];
									$bag[$k]['jllevel'] = $resource[$value['SkillTemplateId']]['t_level'];
								}else{
									//$bag[$key]['jl'] = 0;
									$bag[$k]['jllevel'] = 0;
								}
								//天火破
								if($da[$key]['SkillTemplateId']>=4000601 && $da[$key]['SkillTemplateId']<= 4000605){
									//$bag[$key]['th'] = $resource[$value['SkillTemplateId']]['t_name'];
									$bag[$k]['thlevel'] = $resource[$value['SkillTemplateId']]['t_level'];
								}else{
									//$bag[$key]['th'] = 0;
									$bag[$k]['thlevel'] = 0;
								}
								//金戈铁马
								if($da[$key]['SkillTemplateId']>=4000701 && $da[$key]['SkillTemplateId']<= 4000705){
									//$bag[$key]['jg'] = $resource[$value['SkillTemplateId']]['t_name'];
									$bag[$k]['jglevel'] = $resource[$value['SkillTemplateId']]['t_level'];
								}else{
									//$bag[$key]['jg'] = 0;
									$bag[$k]['jglevel'] = 0;
								}
								//八门金锁
								if($da[$key]['SkillTemplateId']>=4000801 && $da[$key]['SkillTemplateId']<= 4000805){
									//$bag[$key]['bm'] = $resource[$value['SkillTemplateId']]['t_name'];
									$bag[$k]['bmlevel'] = $resource[$value['SkillTemplateId']]['t_level'];
								}else{
									//$bag[$key]['bm'] = 0;
									$bag[$k]['bmlevel'] = 0;
								}
								//一气化千
								if($da[$key]['SkillTemplateId']>=4000901 && $da[$key]['SkillTemplateId']<= 4000905){
									//$bag[$key]['yqhq'] = $resource[$value['SkillTemplateId']]['t_name'];
									$bag[$k]['yqhqlevel'] = $resource[$value['SkillTemplateId']]['t_level'];
								}else{
									//$bag[$key]['yqhq'] = 0;
									$bag[$k]['yqhqlevel'] = 0;
								}
								//疾行
								if($da[$key]['SkillTemplateId']>=4001001 && $da[$key]['SkillTemplateId']<= 4001005){
									//$bag[$key]['jx'] = $resource[$value['SkillTemplateId']]['t_name'];
									$bag[$k]['jxlevel'] = $resource[$value['SkillTemplateId']]['t_level'];
								}else{
									//$bag[$key]['jx'] = 0;
									$bag[$k]['jxlevel'] = 0;
								}
								//归元
								if($da[$key]['SkillTemplateId']>=4001101 && $da[$key]['SkillTemplateId']<= 4001105){
									//$bag[$key]['gy'] = $resource[$value['SkillTemplateId']]['t_name'];
									$bag[$k]['gylevel'] = $resource[$value['SkillTemplateId']]['t_level'];
								}else{
									//$bag[$key]['gy'] = 0;
									$bag[$k]['gylevel'] = 0;
								}
								//御气
								if($da[$key]['SkillTemplateId']>=4001201 && $da[$key]['SkillTemplateId']<= 4001205){
									//$bag[$key]['yq'] = $resource[$value['SkillTemplateId']]['t_name'];
									$bag[$k]['yqlevel'] = $resource[$value['SkillTemplateId']]['t_level'];
								}else{
									//$bag[$key]['yq'] = 0;
									$bag[$k]['yqlevel'] = 0;
								}
								$bag[$k]['count']= count($da[$key]['SkillTemplateId']>=4000101 && $da[$key]['SkillTemplateId']<= 4001205);
								$bag[$k]['level'] = $rolelist[$k]['rolename'];
								$bag[$k]['hh'] =0;
								$bag[$k]['nd'] =0;
							}
						
					}
					
						// $st[$k]['qtlevel']=0;
						// $st[$k]['cflevel']=0;
						// $st[$k]['qllevel']=0;
						// $st[$k]['jflevel']=0;
						// $st[$k]['jllevel']=0;
						// $st[$k]['thlevel']=0;
						// $st[$k]['jglevel']=0;
						// $st[$k]['bmlevel']=0;
						// $st[$k]['yqhqlevel']=0;
						// $st[$k]['jxlevel']=0;
						// $st[$k]['gylevel']=0;
						// $st[$k]['yqlevel']=0;
						// $st[$k]['count']=0;
						// $st[$k]['level']=$rolelist[$k]['rolename'];
					// if(!empty($bag[$k])){	
						// if($st[$k]['level'] == $bag[$k]['level']){
							// $st[$k] = $bag[$k];
						// }
					// }
					
				}
			}
			//print_R($st);
			$result = array(
						'list' =>  $bag,
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate
				);
			echo json_encode($result);
			exit;
		}else{
			echo "文件不存在";
			exit;
		}
	}
	
	/**
	 * 获取星宿信息
	 */
	public function getstar() {
		list($ip, $id, $sid) = autoConfig::getConfig($this->ip);
		$obj = D('game'.$this->ip);
		$num = get_var_value('num');
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rolelist = $point->field('guid,accountid,serverid,rolename,seconddata,roleheadid,level,sex,carrer,stardrom')->table('player_table')->order('level desc')->select();
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj->table('online_sec')->where(array('o_date >='=> $this->startdate.' 00:00:00','o_date <='=> $this->enddate.' 23：59：59'))->order('o_level desc')->limit(0,$time)->select();	
		
		if($list) {
			foreach($list as $key => $value){
				if(!empty($rolelist[$key]) && $list['o_userid'] =$rolelist[$key]['guid']) {
					$data = $rolelist[$key]['stardrom'];
					
					$da = json_decode($data,true);
					$da = $da['StarLifeLevelList'];
					if(is_array($da)){
						$bag[$key]['u_name'] = $rolelist[$key]['rolename'];
						$bag[$key]['one'] = $da[0]['nLifeLevel'];
						$bag[$key]['two'] = $da[1]['nLifeLevel'];
						$bag[$key]['three'] = $da[2]['nLifeLevel'];
						$bag[$key]['four'] = $da[3]['nLifeLevel'];
						$bag[$key]['five'] = $da[4]['nLifeLevel'];
						$bag[$key]['six'] = $da[5]['nLifeLevel'];
						$bag[$key]['seven'] = $da[6]['nLifeLevel'];
						$bag[$key]['eight'] = $da[7]['nLifeLevel'];
					}
				}
			}
		}
		$result = array(
					'list' =>  $bag,
					'startDate'=>$this->startdate,
					'endDate'=>$this->enddate
			);
		echo json_encode($result);
		exit;
		
		
	}
	
	/**
	 * 获取神兵信息
	 */
	public function getmagic() {
		
		list($ip, $id, $sid) = autoConfig::getConfig($this->ip);
		$obj = D('game'.$this->ip);
		$num = get_var_value('num');
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rolelist = $point->field('guid,accountid,serverid,rolename,seconddata,roleheadid,level,sex,carrer,forevermapdata')->table('player_table')->order('level desc')->select();
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj->table('online_sec')->where(array('o_date >='=> $this->startdate.' 00:00:00','o_date <='=> $this->enddate.' 23：59：59'))->order('o_level desc')->limit(0,$time)->select();	
		
		if($list) {
			foreach($list as $key => $value){
				if(!empty($rolelist[$key])){
				if(!empty($rolelist[$key]['guid']) && $list['o_userid'] =$rolelist[$key]['guid']) {
					$data = $rolelist[$key]['forevermapdata'];
				}
				
				$da = json_decode($data,true);
				
				foreach($da as $k =>$item){
					// if($item['k']== 43){
					// $bag[$key]['slevel'] = $item['v'];
					// }else{
						// $bag[$key]['slevel'] = 0;
					// }
					$bag[$key]['slevel'] = $rolelist[$key]['rolename'];
					if($item['k'] == 58){
						$bag[$key]['ling'] = $item['v'];
					}else{
						$bag[$key]['ling'] = 0;
					}
					if($item['k'] == 45){
						if($item['v'] == 1){
							$bag[$key]['mskill'] = '沉默效果';
						}else if($item['v'] == 2){
							$bag[$key]['mskill'] = '定身效果';
						}else if($item['v'] == 3){
							$bag[$key]['mskill'] = '击退效果';
						}else if($item['v'] == 4){
							$bag[$key]['mskill'] = '连击效果';
						}else if($item['v'] == 5){
							$bag[$key]['mskill'] = '晕眩效果';
						}
					}else{
						$bag[$key]['mskill'] = '无';
					}
					if($item['k'] == 46){
						$bag[$key]['ming'] = 0;
					}else{
						$bag[$key]['ming'] = 0;
					}
					if($item['k'] == 48){
						$gaga = str_replace('=',':',$item['v']);
						$gaga = str_replace('[','"',$gaga);
						$gaga = str_replace(']','"',$gaga);
						$gaga = str_replace(',}','}',$gaga);
						$gaga = json_decode($gaga,true);
						foreach($gaga as $im){
							$tem = $im[1];
							if($tem == 1201){
								$bag[$key]['one']=	round($im[2]/1600000 * 100 , 3).'%';
							}else if($tem == 2201){
								$bag[$key]['two']=	round($im[2]/1600000 * 100 , 3).'%';
							}else if($tem == 3201){
								$bag[$key]['three']=round($im[2]/1600000 * 100 , 3).'%';
							}else if($tem == 4201){
								$bag[$key]['four']=round($im[2]/1600000 * 100 , 3).'%';
							}else if($tem == 5201){
								$bag[$key]['five']=round($im[2]/1600000 * 100 , 3).'%';
							}else if($tem == 6201){
								$bag[$key]['six']=	round($im[2]/1600000 * 100 , 3).'%';
							}
						}
					}
				}
			}	
			}
		}
		$result = array(
					'list' =>  $bag,
					'startDate'=>$this->startdate,
					'endDate'=>$this->enddate
			);
		echo json_encode($result);
		exit;
		
		
	}
	
	/**
	 * 获取国家属性
	 */
	public function gettrick() {
		
		list($ip, $id, $sid) = autoConfig::getConfig($this->ip);
		$obj = D('game'.$this->ip);
		$num = get_var_value('num');
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rolelist = $point->field('guid,accountid,serverid,rolename,seconddata,roleheadid,level,sex,carrer,forevermapdata')->table('player_table')->order('level desc')->select();
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj->table('online_sec')->where(array('o_date >='=> $this->startdate.' 00:00:00','o_date <='=> $this->enddate.' 23：59：59'))->order('o_level desc')->limit(0,$time)->select();	
		
		if($list) {
			foreach($list as $k => $item){
				if(!empty($rolelist[$k]) && $list['o_userid'] =$rolelist[$k]['guid']) {
					$data = $rolelist[$k]['forevermapdata'];
				
					$da = json_decode($data,true);
					foreach($da as $key => $value){
						if($value['k'] == 151){
							$bag[$k]['sm'] = $value['v'];
						}else{
							$bag[$k]['sm'] = 0;
						}
						if($value['k'] == 152){
							$bag[$k]['mz'] = $value['v'];
						}else{
							$bag[$k]['mz'] = 0;
						}
						if($value['k'] == 153){
							$bag[$k]['sb'] = $value['v'];
						}else{
							$bag[$k]['sb'] = 0;
						}
						if($value['k'] == 154){
							$bag[$k]['bj'] = $value['v'];
						}else{
							$bag[$k]['bj'] = 0;
						}
						if($value['k'] == 155){
							$bag[$k]['jr'] = $value['v'];
						}else{
							$bag[$k]['jr'] = 0;
						}
						$bag[$k]['u_name'] = $rolelist[$k]['rolename'];
					}	
				}
			}
		}
		$result = array(
					'list' =>  $bag,
					'startDate'=>$this->startdate,
					'endDate'=>$this->enddate
			);
		echo json_encode($result);
		exit;
		
		
	}
	
	
	/**
	 * 导出玩家信息excel
	 */
	public function taskexcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$obj = D('game'.$this->ip);
		$num = get_var_value('num');
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rolelist = $point->field('guid,accountid,serverid,rolename,seconddata,roleheadid,level,sceneid,camp,sex,carrer,guildid,viplevel,gmlevel')->table('player_table')->order('level desc')->select();
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj->field('o_userid,o_date')->table('online_sec')->where(array('o_date >='=> $startdate.' 00:00:00','o_date <='=> $enddate.' 23：59：59'))->order('o_level desc')->limit(0,$time)->select();	
		
		
		if(!empty($list)){
			foreach($list as $key => $value){
				if(!empty($rolelist[$key]['guid']) && $rolelist[$key]['guid'] = $value['o_userid']){
					$role[$key] = $rolelist[$key];
					$role[$key]['guid']=$value['o_userid'];
					$role[$key]['on_time']=$value['o_date'];
					$role[$key]['country']='暂无';
					$role[$key]['boss']=0;
					$role[$key]['fried']=0;
					$role[$key]['kill']=0;
					$role[$key]['die']=0;
					
				}
			}

			// require_once(AClass.'phpexcel/PHPExcel.php');
			
			// $objPHPExcel = new PHPExcel();
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
							 
		$objPHPExcel->setActiveSheetIndex(0);	
			$objPHPExcel->getActiveSheet()->setCellValue('A1', '日期'); 
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '账号');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '角色id');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '角色名称');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '职业');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '国家');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '性别');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '角色等级');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', 'vip等级');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '称号');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', '帮派');
			$objPHPExcel->getActiveSheet()->setCellValue('L1', '击杀世界boss数');
			$objPHPExcel->getActiveSheet()->setCellValue('M1', '好友数量');
			$objPHPExcel->getActiveSheet()->setCellValue('N1', '击杀玩家数');
			$objPHPExcel->getActiveSheet()->setCellValue('O1', '被击杀');
			
			if (is_array($list)) {
				foreach($role as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["on_time"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["accountid"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["guid"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["rolename"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["carrer"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["country"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["sex"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["level"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["viplevel"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["rolename"]);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["guildid"]);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["boss"]);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item["fried"]);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.($k+2), $item["kill"]);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.($k+2), $item["die"]);
				}	
			}
		$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "角色概况_".$startdate;
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
		
		}

	}
	
	/**
	 * 导出战斗信息excel
	 */
	public function battleexcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		list($ip, $id, $sid) = autoConfig::getConfig($this->ip);
		$obj = D('game'.$this->ip);
		$num = get_var_value('num');
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rolelist = $point->field('guid,accountid,serverid,rolename,seconddata,roleheadid,level,sex,carrer')->table('player_table')->order('level desc')->select();
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj->table('online_sec')->where(array('o_date >='=> $this->startdate.' 00:00:00','o_date <='=> $this->enddate.' 23：59：59'))->order('o_level desc')->limit(0,$time)->select();	
		
		if(!empty($list)){
			foreach($list as $key => $item){
				if(!empty($rolelist[$key])) {
					$data = $rolelist[$key]['seconddata'];
				
					$da = json_decode($data,true);
					if(is_array($da) && $item['o_userid'] = $rolelist[$key]['guid']){
						$bag[$key]['uid']=$rolelist[$key]['guid'];
						$bag[$key]['o_date'] = date('Y-m-d',strtotime($item['o_date']));
						$bag[$key]['accountid']=$rolelist[$key]['accountid'];
						$bag[$key]['u_name']=$rolelist[$key]['rolename'];
						$bag[$key]['BattlePower'] = $da['BattlePower'];
						$bag[$key]['HP'] = $da['HP'];
						$bag[$key]['MP'] = $da['MP'];
						$bag[$key]['PhyAttack'] = $da['PhyAttack'];
						$bag[$key]['PhyDefense'] = $da['PhyDefense'];
						$bag[$key]['MagicDefense'] = $da['MagicDefense'];
						$bag[$key]['MagicAttack'] = $da['MagicAttack'];
						$bag[$key]['Power'] = $da['Power'];
						$bag[$key]['Nimble'] = $da['Nimble'];
						$bag[$key]['Physical'] = $da['Physical'];
						$bag[$key]['Intelligence'] = $da['Intelligence'];
						$bag[$key]['Spirit'] = $da['Spirit'];
						$bag[$key]['Hit'] = $da['Hit'];
						$bag[$key]['Dodge'] = $da['Dodge'];
						$bag[$key]['Cruel'] = $da['Cruel'];
						$bag[$key]['GodWakan'] = $da['GodWakan'];
						$bag[$key]['Tenacity'] = $da['DeCruel'];
						$bag[$key]['Minus'] = $da['ReHurt'];
					}
				}
			}

			require_once(AClass.'phpexcel/PHPExcel.php');
			
			$objPHPExcel = new PHPExcel();
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
							 
		$objPHPExcel->setActiveSheetIndex(0);	
			$objPHPExcel->getActiveSheet()->setCellValue('A1', '日期'); 
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '账号');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '角色id');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '角色名称');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '战力');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '生命值');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '物理攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '物理防御');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '法术攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '法术防御');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', '力量');
			$objPHPExcel->getActiveSheet()->setCellValue('L1', '敏捷');
			$objPHPExcel->getActiveSheet()->setCellValue('M1', '体质');
			$objPHPExcel->getActiveSheet()->setCellValue('N1', '智力');
			$objPHPExcel->getActiveSheet()->setCellValue('O1', '精神');
			$objPHPExcel->getActiveSheet()->setCellValue('P1', '命中');
			$objPHPExcel->getActiveSheet()->setCellValue('Q1', '闪避');
			$objPHPExcel->getActiveSheet()->setCellValue('R1', '暴击');
			$objPHPExcel->getActiveSheet()->setCellValue('S1', '坚韧');
			$objPHPExcel->getActiveSheet()->setCellValue('T1', '减伤');
			
			if (is_array($bag)) {
				foreach($bag as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["o_date"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["accountid"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["uid"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["u_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["BattlePower"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["HP"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["PhyAttack"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["PhyDefense"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["MagicAttack"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["MagicDefense"]);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["Power"]);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["Nimble"]);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item["Physical"]);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.($k+2), $item["Intelligence"]);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.($k+2), $item["Spirit"]);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.($k+2), $item["Hit"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.($k+2), $item["Dodge"]);
					$objPHPExcel->getActiveSheet()->setCellValue('R'.($k+2), $item["Cruel"]);
					$objPHPExcel->getActiveSheet()->setCellValue('S'.($k+2), $item["Tenacity"]);
					$objPHPExcel->getActiveSheet()->setCellValue('T'.($k+2), $item["Minus"]);
				}	
			}

			$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "战斗信息_".$startdate;
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
		
		}

	}
	
	/**
	 * 导出武将属性excel
	 */
	public function pieexcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('endDate');
		$obj = D('game'.$this->ip);
		$num = get_var_value('num');
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rolelist = $point->field('guid,accountid,serverid,rolename,seconddata,roleheadid,level,sex,carrer,rolepet')->table('player_table')->order('level desc')->select();
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj->table('online_sec')->where(array('o_date >='=> $this->startdate,'o_date <='=> $this->enddate))->order('o_level asc')->limit(0,$time)->select();	
		//fopen
		// $skfile = TPATH."/zs/skill.json";
		$skfile = ITEM."/skill.json";
		if (file_exists($skfile)) {
			$get_str = '';//获取道具日志文件内容	
			clearstatcache();
			$fs = fopen($skfile,'r');
			while(!feof($fs)){
					$get_str .= fgets($fs);
				}
			fclose($fs);
			$skill_json = json_decode($get_str,true);
			
		}
		//print_R($skill_json);
		$petfile = ITEM."/pet.json";
		if (file_exists($petfile)) {
			$get_str = '';//获取道具日志文件内容	
			clearstatcache();
			$fs = fopen($petfile,'r');
			while(!feof($fs)){
					$get_str .= fgets($fs);
				}
			fclose($fs);
			$pet_json = json_decode($get_str,true);
			
		}
		$bookfile = ITEM."/PetBook.json";
		if (file_exists($bookfile)) {
			$get_str = '';//获取道具日志文件内容	
			clearstatcache();
			$fs = fopen($bookfile,'r');
			while(!feof($fs)){
					$get_str .= fgets($fs);
				}
			fclose($fs);
			$book_json = json_decode($get_str,true);
			
		}
		foreach($book_json as $key => $value){
			foreach($value['LevelData'] as $k => $v){
				$bookes[$key][$k]['Upgradealue'] = $v['Upgradealue'];
				$bookes[$key][$k]['level']=$v['Level'];
			}
			$bookes[$key]['BookName'] = $value['BookName'];
		}
		if(!empty($list)){
			foreach($list as $k =>$item){
				if(!empty($rolelist[$k])){
					$data = $rolelist[$k]['rolepet'];
					$da = json_decode($data,true);
					$pet = $da['PetList'];
					$book = $da['PetBooks'];
					if($rolelist[$k]['accountid']>0 && $rolelist[$k]['guid'] = $item['o_userid']){
						$bag[$k]['p_date'] = date('Y-m-d',strtotime($item['o_date']));
						$bag[$k]['account'] = $rolelist[$k]['accountid'];
						$bag[$k]['playid'] = $rolelist[$k]['guid'];
						$bag[$k]['rolename'] = $rolelist[$k]['rolename'];
						
						if(!empty($pet) && $rolelist[$k]['guid']>0){
							foreach($pet as $key =>$value){
								$bag[$k]['info'][$key]=$value['SecondData'];
								$bag[$k]['info'][$key]['NO'] = $key +1;
								if($value['templateid'] == $pet_json[$value['templateid']]['iconId']){
									$bag[$k]['info'][$key]['templateid'] = $pet_json[$value['templateid']]['name'];
								}
								$bag[$k]['info'][$key]['Level']=$value['Level'];
								$bag[$k]['info'][$key]['TrunCount']=$value['TrunCount'];
								
								$bag[$k]['info'][$key]['gif2'] = '无';
								$bag[$k]['info'][$key]['gif1'] = '无';
								if(!empty($value['PetGiftSkillList']['v0']['nSkillID'])){
									if($value['PetGiftSkillList']['v0']['nSkillID'] = $skill_json[$value['PetGiftSkillList']['v0']['nSkillID']]['SkilltemplateID']){
										$bag[$k]['info'][$key]['gif1'] = $skill_json[$value['PetGiftSkillList']['v0']['nSkillID']]['SkillName'];
										$bag[$k]['info'][$key]['gif1level'] = $skill_json[$value['PetGiftSkillList']['v0']['nSkillID']]['SkillLevel'];
									}
								}
								if(!empty($value['PetGiftSkillList']['v1']['nSkillID'])){
									if($value['PetGiftSkillList']['v1']['nSkillID'] = $skill_json[$value['PetGiftSkillList']['v1']['nSkillID']]['SkilltemplateID']){
										$bag[$k]['info'][$key]['gif2'] = $skill_json[$value['PetGiftSkillList']['v1']['nSkillID']]['SkillName'];
										$bag[$k]['info'][$key]['gif2level'] = $skill_json[$value['PetGiftSkillList']['v1']['nSkillID']]['SkillLevel'];
									}
								}
								if(!empty($value['BattleData'][0]['SkillTemplateId'])){
									$bag[$k]['info'][$key]['bskill1']=$value['BattleData'][0]['SkillTemplateId'];
								}else{
									$bag[$k]['info'][$key]['bskill1'] =0;
								}
								if(!empty($value['BattleData'][0]['SkillTemplateId'])){
									$bag[$k]['info'][$key]['bskill2']=$value['BattleData'][1]['SkillTemplateId'];
								}else{
									$bag[$k]['info'][$key]['bskill2'] =0;
								}
								if(!empty($value['BattleData'][0]['SkillTemplateId'])){
									$bag[$k]['info'][$key]['bskill3']=$value['BattleData'][2]['SkillTemplateId'];
								}else{
									$bag[$k]['info'][$key]['bskill3'] =0;
								}
								
								$bag[$key]['RoleBattleAttributeValue']=0;
								foreach($book as $i =>$v){
									if($v['nBookID'] > 0 && $v['nBookID'] = $book_json[$v['nBookID']-10000]['BookID'] && $v['nLevel'] = $book_json[$v['nBookID']-10000]['LevelData'][$key]['Level']){
										$bag[$k]['info'][$key]['points'.$i] =  $book_json[$v['nBookID']-10000]['LevelData'][$key]['Upgradealue'];
										$bag[$k]['info'][$key]['book'.$i] = $book_json[$v['nBookID']-10000]['BookName'];
										$bag[$k]['info'][$key]['nLevel'.$i]=$v['nLevel'];
									}else{
										$bag[$k]['info'][$key]['points'.$i] = 0;
										$bag[$k]['info'][$key]['book'.$i] ='无';
										$bag[$k]['info'][$key]['nLevel'.$i] = 0;
									}
								}
								$bag[$k]['info'][$key]['petGrow'] = 0;
							}
						}
					}
				}
			}
			require_once(AClass.'phpexcel/PHPExcel.php');
			
			$objPHPExcel = new PHPExcel();
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
							 
		$objPHPExcel->setActiveSheetIndex(0);	
			$objPHPExcel->getActiveSheet()->setCellValue('A1', '日期'); 
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '账号');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '角色id');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '角色名称');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '武将名称');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '序号');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '武将等级');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '武将战力');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '转生次数');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '资质');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', '成长');
			$objPHPExcel->getActiveSheet()->setCellValue('L1', '生命值');
			$objPHPExcel->getActiveSheet()->setCellValue('M1', '物理攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('N1', '物理防御');
			$objPHPExcel->getActiveSheet()->setCellValue('O1', '法术攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('P1', '法术防御');
			$objPHPExcel->getActiveSheet()->setCellValue('Q1', '命中');
			$objPHPExcel->getActiveSheet()->setCellValue('R1', '闪避');
			$objPHPExcel->getActiveSheet()->setCellValue('S1', '暴击');
			$objPHPExcel->getActiveSheet()->setCellValue('T1', '坚韧');
			$objPHPExcel->getActiveSheet()->setCellValue('U1', '力量');
			$objPHPExcel->getActiveSheet()->setCellValue('V1', '敏捷');
			$objPHPExcel->getActiveSheet()->setCellValue('W1', '体质');
			$objPHPExcel->getActiveSheet()->setCellValue('X1', '智力');
			$objPHPExcel->getActiveSheet()->setCellValue('Y1', '精神');
			$objPHPExcel->getActiveSheet()->setCellValue('Z1', '兵书个数');
			$objPHPExcel->getActiveSheet()->setCellValue('AA1', '兵书评分');
			$objPHPExcel->getActiveSheet()->setCellValue('AB1', '太公兵法等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AC1', '六韬兵书等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AD1', '尉缭子等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AE1', '黄帝内经等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AF1', '论语等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AG1', '战国策等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AH1', '技能个数');
			$objPHPExcel->getActiveSheet()->setCellValue('AI1', '天赋技能1名称等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AJ1', '天赋技能2名称等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AK1', '通用技能名称等级');
			
			if (is_array($bag)) {
				foreach($bag as $k => $item){
					if(!empty($item['info'])){
					 foreach($item['info'] as $ke => $ite){
					// print_R($ite);
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["p_date"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2+$ke), $item["account"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2+$ke), $item["playid"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2+$ke), $item["rolename"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2+$ke), $item['info'][$ke]['templateid']);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2+$ke), $ite["NO"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2+$ke), $ite["Level"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2+$ke), $ite["BattlePower"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2+$ke), $ite["TrunCount"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2+$ke), "暂无");
					$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2+$ke), $ite["Aptitude"]);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2+$ke), $ite["HP"]);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2+$ke), $ite["PhyAttack"]);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.($k+2+$ke), $ite["PhyDefense"]);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.($k+2+$ke), $ite["MagicAttack"]);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.($k+2+$ke), $ite["MagicDefense"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.($k+2+$ke), $ite["Hit"]);
					$objPHPExcel->getActiveSheet()->setCellValue('R'.($k+2+$ke), $ite["Dodge"]);
					$objPHPExcel->getActiveSheet()->setCellValue('S'.($k+2+$ke), $ite["Cruel"]);
					$objPHPExcel->getActiveSheet()->setCellValue('T'.($k+2+$ke), $ite["DeCruel"]);
					$objPHPExcel->getActiveSheet()->setCellValue('U'.($k+2+$ke), $ite["Power"]);
					$objPHPExcel->getActiveSheet()->setCellValue('V'.($k+2+$ke), $ite["Nimble"]);
					$objPHPExcel->getActiveSheet()->setCellValue('W'.($k+2+$ke), $ite["Physical"]);
					$objPHPExcel->getActiveSheet()->setCellValue('X'.($k+2+$ke), $ite["Intelligence"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Y'.($k+2+$ke), $ite["Spirit"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Z'.($k+2+$ke), "暂无");
					$objPHPExcel->getActiveSheet()->setCellValue('AA'.($k+2+$ke), "暂无");
					$objPHPExcel->getActiveSheet()->setCellValue('AB'.($k+2+$ke), "暂无");
					$objPHPExcel->getActiveSheet()->setCellValue('AC'.($k+2+$ke), "暂无");
					$objPHPExcel->getActiveSheet()->setCellValue('AD'.($k+2+$ke), "暂无");
					$objPHPExcel->getActiveSheet()->setCellValue('AE'.($k+2+$ke), "暂无");
					$objPHPExcel->getActiveSheet()->setCellValue('AF'.($k+2+$ke), "暂无");
					$objPHPExcel->getActiveSheet()->setCellValue('AG'.($k+2+$ke), "暂无");
					$objPHPExcel->getActiveSheet()->setCellValue('AH'.($k+2+$ke), "暂无");
					$objPHPExcel->getActiveSheet()->setCellValue('AI'.($k+2+$ke), $ite["gif1"]."--".$ite["gif1"]."级");
					$objPHPExcel->getActiveSheet()->setCellValue('AJ'.($k+2+$ke), $ite["gif2"]."--".$ite["gif1"]."级");
					$objPHPExcel->getActiveSheet()->setCellValue('AK'.($k+2+$ke), $ite["bskill1"]."--".$ite["gif1"]."级");
					}	
					}
				}	
			//print_R($bag);
				//exit;
				$objPHPExcel->getActiveSheet()->setTitle('Simple');

				$objPHPExcel->setActiveSheetIndex(0);
				$file_name = "武将属性_".$startdate;
				
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				
				exit;
			}
		}

	}
	
	/**
	 * 导出装备信息excel
	 */
	public function equiexcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$obj = D('game'.$this->ip);
		$num = get_var_value('num');
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rolelist = $point->field('guid,accountid,serverid,rolename,seconddata,roleheadid,level,sex,carrer,equipsdata')->table('player_table')->order('level desc')->select();
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj->table('online_sec')->where(array('o_date >='=> $this->startdate,'o_date <='=> $this->enddate))->order('o_level desc')->limit(0,$time)->select();	
		
		$file =ITEM."/equipment.json";
		if (file_exists($file)) {
			$get_str = '';//获取道具日志文件内容	
			clearstatcache();
			$fs = fopen($file,'r');
			while(!feof($fs)){
					$get_str .= fgets($fs);
				}
			fclose($fs);
			$str = json_decode($get_str,true);
			$resource = '';
			}
		if(!empty($list)){
			foreach($list as $k => $item){
				if(!empty($rolelist[$k]) && $rolelist[$k]['accountid']>0){
					if(!empty($rolelist[$k])){
						$data = $rolelist[$k]['equipsdata'];
					}
					$da = '';
					$da = json_decode($data,true);
					$da = $da['BagCellList'];
					
					$bag[$k]['head'] = "空";
					$bag[$k]['headlevel'] = 0;
					$bag[$k]['clothes'] = "空";
					$bag[$k]['clotheslevel'] = 0;
					$bag[$k]['belt'] = "空";
					$bag[$k]['beltlevel'] = 0;
					$bag[$k]['necklace'] = "空";
					$bag[$k]['necklacelevel'] = 0;
					$bag[$k]['bracers'] = "空";
					$bag[$k]['bracerslevel'] = 0;
					$bag[$k]['ring'] = "空";
					$bag[$k]['ringlevel'] = 0;
					$bag[$k]['arm'] = "空";
					$bag[$k]['armlevel'] = 0;
					$bag[$k]['shoes'] = "空";
					$bag[$k]['shoeslevel'] = 0;
					$bag[$k]['pant'] = "空";
					$bag[$k]['pantlevel'] = 0;
					
					if(!empty($da) && $item['o_userid']=$rolelist[$k]['guid']){
						
						$bag[$k]['uid']=$rolelist[$k]['guid'];
						$bag[$k]['o_date'] = date('Y-m-d',strtotime($item['o_date']));
						$bag[$k]['accountid']=$rolelist[$k]['accountid'];
						$bag[$k]['u_name']=$rolelist[$k]['rolename'];
						foreach($da as $key => $value){
							if($da[$key]['CellID'] == 0){	//头部
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['head'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['headlevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 1){	//衣服
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['clothes'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['clotheslevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 2){	//腰带
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['belt'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['beltlevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 3){	//项链
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['necklace'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['necklacelevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 12){	//护腕
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['bracers'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['bracerslevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 8){	//戒指
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['ring'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['ringlevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 10){	//鞋子
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['shoes'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['shoeslevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 6){	//武器
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['arm'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['armlevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							if($da[$key]['CellID'] == 11){	//裤子
								if($value['Cell']['TemplateID'] = $str[$value['Cell']['TemplateID']]['ID']){
									$bag[$k]['pant'] = $str[$value['Cell']['TemplateID']]['name'];
									$bag[$k]['pantlevel'] = $str[$value['Cell']['TemplateID']]['strengthenLevel'];
								}
							}
							
						}
					}
				}
			}
			require_once(AClass.'phpexcel/PHPExcel.php');
			
			$objPHPExcel = new PHPExcel();
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
							 
		$objPHPExcel->setActiveSheetIndex(0);	
			$objPHPExcel->getActiveSheet()->setCellValue('A1', '日期'); 
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '账号');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '角色id');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '角色名称');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '武器名称强化等级');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '护手名称强化等级');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '衣服名称强化等级');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '裤子名称强化等级');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '腰带名称强化等级');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '鞋子名称强化等级');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', '项链名称强化等级');
			$objPHPExcel->getActiveSheet()->setCellValue('L1', '戒指名称强化等级');
			$objPHPExcel->getActiveSheet()->setCellValue('M1', '武器附加属性');
			$objPHPExcel->getActiveSheet()->setCellValue('N1', '头盔附加属性');
			$objPHPExcel->getActiveSheet()->setCellValue('O1', '护手附加属性');
			$objPHPExcel->getActiveSheet()->setCellValue('P1', '衣服附加属性');
			$objPHPExcel->getActiveSheet()->setCellValue('Q1', '裤子附加属性');
			$objPHPExcel->getActiveSheet()->setCellValue('R1', '腰带附加属性');
			$objPHPExcel->getActiveSheet()->setCellValue('S1', '鞋子附加属性');
			$objPHPExcel->getActiveSheet()->setCellValue('T1', '项链附加属性');
			$objPHPExcel->getActiveSheet()->setCellValue('U1', '戒指附加属性');
			if (is_array($bag)) {
				foreach($bag as $k => $item){
					// $objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["o_date"]); 
					// $objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["accountid"]);
					// $objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["uid"]);
					// $objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["u_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["arm"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["bracers"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["clothes"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["pant"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["belt"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["shoes"]);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["necklace"]);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["ring"]);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item["armlevel"]);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.($k+2), $item["headlevel"]);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.($k+2), $item["bracerslevel"]);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.($k+2), $item["clotheslevel"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.($k+2), $item["pantlevel"]);
					$objPHPExcel->getActiveSheet()->setCellValue('R'.($k+2), $item["beltlevel"]);
					$objPHPExcel->getActiveSheet()->setCellValue('S'.($k+2), $item["shoeslevel"]);
					$objPHPExcel->getActiveSheet()->setCellValue('T'.($k+2), $item["necklacelevel"]);
					$objPHPExcel->getActiveSheet()->setCellValue('U'.($k+2), $item["ringlevel"]);
				}	
			}
			$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "装备_".$startdate;
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			exit;
			//}
		}
		
	
	}
	
	/**
	 * 导出坐骑信息excel
	 */
	public function skillexcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$obj = D('game'.$this->ip);
		$num = get_var_value('num');
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rolelist = $point->field('guid,accountid,serverid,rolename,seconddata,roleheadid,level,sex,carrer,battledata')->table('player_table')->order('level desc')->select();
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj->table('online_sec')->where(array('o_date >='=> $this->startdate,'o_date <='=> $this->enddate))->order('o_level desc')->limit(0,$time)->select();	
		
		
		$file =ITEM."/skill.json";
		if (file_exists($file)) {
			$get_str = '';//获取道具日志文件内容	
			clearstatcache();
			$fs = fopen($file,'r');
			while(!feof($fs)){
					$get_str .= fgets($fs);
				}
			fclose($fs);
			$str = json_decode($get_str,true);
			$resource = '';
			foreach($str as $k => $value){
				$resource[$k]['t_code']= $k;
				$resource[$k]['t_name']= $value['SkillName'];
				$resource[$k]['t_level'] = $value['SkillLevel'];
			}
		}	
			
			if(!empty($list)){
			
				foreach($list as $k =>$item){
					if(!empty($rolelist[$k]) && $item['o_userid'] =  $rolelist[$k]['guid']) {
						$data = $rolelist[$k]['battledata'];
					}
					$da = json_decode($data,true);
					$da = $da['SkillList'];
					//print_R($da);
					$ga = '';
					foreach($da as $key => $value){
						
						if( $da[$key]['SkillTemplateId'] >=4000101 && $da[$key]['SkillTemplateId'] <=4001205 ){
							//践踏
							if($da[$key]['SkillTemplateId']>=4000101 && $da[$key]['SkillTemplateId']<= 4000105){
								//$bag[$key]['qt'] = $resource[$value['SkillTemplateId']]['t_name'];
								$bag[$k]['qtlevel'] = $resource[$value['SkillTemplateId']]['t_level'];
							}else{
								//$bag[$key]['qt'] = 0;
								$bag[$k]['qtlevel'] = 0;
							}
							//冲锋
							if($da[$key]['SkillTemplateId']>=4000201 && $da[$key]['SkillTemplateId']<= 4000205){
								//$bag[$key]['cf'] = $resource[$value['SkillTemplateId']]['t_name'];
								$bag[$k]['cflevel'] = $resource[$value['SkillTemplateId']]['t_level'];
							}else{
								//$bag[$key]['cf'] = 0;
								$bag[$k]['cflevel'] = 0;
							}
							//千里奔袭
							if($da[$key]['SkillTemplateId']>=4000301 && $da[$key]['SkillTemplateId']<= 4000305){
								//$bag[$key]['ql'] = $resource[$value['SkillTemplateId']]['t_name'];
								$bag[$k]['qllevel'] = $resource[$value['SkillTemplateId']]['t_level'];
							}else{
								//$bag[$key]['ql'] = 0;
								$bag[$k]['qllevel'] = 0;
							}
							//疾风破
							if($da[$key]['SkillTemplateId']>=4000401 && $da[$key]['SkillTemplateId']<= 4000405){
								//$bag[$key]['jf'] = $resource[$value['SkillTemplateId']]['t_name'];
								$bag[$k]['jflevel'] = $resource[$value['SkillTemplateId']]['t_level'];
							}else{
								//$bag[$key]['jf'] = 0;
								$bag[$k]['jflevel'] = 0;
							}
							//惊雷破
							if($da[$key]['SkillTemplateId']>=4000501 && $da[$key]['SkillTemplateId']<= 4000505){
								//$bag[$key]['jl'] = $resource[$value['SkillTemplateId']]['t_name'];
								$bag[$k]['jllevel'] = $resource[$value['SkillTemplateId']]['t_level'];
							}else{
								//$bag[$key]['jl'] = 0;
								$bag[$k]['jllevel'] = 0;
							}
							//天火破
							if($da[$key]['SkillTemplateId']>=4000601 && $da[$key]['SkillTemplateId']<= 4000605){
								//$bag[$key]['th'] = $resource[$value['SkillTemplateId']]['t_name'];
								$bag[$k]['thlevel'] = $resource[$value['SkillTemplateId']]['t_level'];
							}else{
								//$bag[$key]['th'] = 0;
								$bag[$k]['thlevel'] = 0;
							}
							//金戈铁马
							if($da[$key]['SkillTemplateId']>=4000701 && $da[$key]['SkillTemplateId']<= 4000705){
								//$bag[$key]['jg'] = $resource[$value['SkillTemplateId']]['t_name'];
								$bag[$k]['jglevel'] = $resource[$value['SkillTemplateId']]['t_level'];
							}else{
								//$bag[$key]['jg'] = 0;
								$bag[$k]['jglevel'] = 0;
							}
							//八门金锁
							if($da[$key]['SkillTemplateId']>=4000801 && $da[$key]['SkillTemplateId']<= 4000805){
								//$bag[$key]['bm'] = $resource[$value['SkillTemplateId']]['t_name'];
								$bag[$k]['bmlevel'] = $resource[$value['SkillTemplateId']]['t_level'];
							}else{
								//$bag[$key]['bm'] = 0;
								$bag[$k]['bmlevel'] = 0;
							}
							//一气化千
							if($da[$key]['SkillTemplateId']>=4000901 && $da[$key]['SkillTemplateId']<= 4000905){
								//$bag[$key]['yqhq'] = $resource[$value['SkillTemplateId']]['t_name'];
								$bag[$k]['yqhqlevel'] = $resource[$value['SkillTemplateId']]['t_level'];
							}else{
								//$bag[$key]['yqhq'] = 0;
								$bag[$k]['yqhqlevel'] = 0;
							}
							//疾行
							if($da[$key]['SkillTemplateId']>=4001001 && $da[$key]['SkillTemplateId']<= 4001005){
								//$bag[$key]['jx'] = $resource[$value['SkillTemplateId']]['t_name'];
								$bag[$k]['jxlevel'] = $resource[$value['SkillTemplateId']]['t_level'];
							}else{
								//$bag[$key]['jx'] = 0;
								$bag[$k]['jxlevel'] = 0;
							}
							//归元
							if($da[$key]['SkillTemplateId']>=4001101 && $da[$key]['SkillTemplateId']<= 4001105){
								//$bag[$key]['gy'] = $resource[$value['SkillTemplateId']]['t_name'];
								$bag[$k]['gylevel'] = $resource[$value['SkillTemplateId']]['t_level'];
							}else{
								//$bag[$key]['gy'] = 0;
								$bag[$k]['gylevel'] = 0;
							}
							//御气
							if($da[$key]['SkillTemplateId']>=4001201 && $da[$key]['SkillTemplateId']<= 4001205){
								//$bag[$key]['yq'] = $resource[$value['SkillTemplateId']]['t_name'];
								$bag[$k]['yqlevel'] = $resource[$value['SkillTemplateId']]['t_level'];
							}else{
								//$bag[$key]['yq'] = 0;
								$bag[$k]['yqlevel'] = 0;
							}
							$bag[$k]['count']= count($da[$key]['SkillTemplateId']>=4000101 && $da[$key]['SkillTemplateId']<= 4001205);
							$bag[$k]['level'] = $rolelist[$k]['rolename'];
							$bag[$k]['hh'] =0;
							$bag[$k]['nd'] =0;
						}
					}
				}
				require_once(AClass.'phpexcel/PHPExcel.php');
				$objPHPExcel = new PHPExcel();
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
								 
				$objPHPExcel->setActiveSheetIndex(0);	
				$objPHPExcel->getActiveSheet()->setCellValue('A1', '角色'); 
				$objPHPExcel->getActiveSheet()->setCellValue('B1', '坐骑技能总和');
				$objPHPExcel->getActiveSheet()->setCellValue('C1', '践踏等级');
				$objPHPExcel->getActiveSheet()->setCellValue('D1', '冲锋等级');
				$objPHPExcel->getActiveSheet()->setCellValue('E1', '千里奔袭等级');
				$objPHPExcel->getActiveSheet()->setCellValue('F1', '疾风破等级');
				$objPHPExcel->getActiveSheet()->setCellValue('G1', '惊雷破等级');
				$objPHPExcel->getActiveSheet()->setCellValue('H1', '天火破等级');
				$objPHPExcel->getActiveSheet()->setCellValue('I1', '金戈铁马等级');
				$objPHPExcel->getActiveSheet()->setCellValue('J1', '八门金锁等级');
				$objPHPExcel->getActiveSheet()->setCellValue('K1', '一气化千等级');
				$objPHPExcel->getActiveSheet()->setCellValue('L1', '疾行等级');
				$objPHPExcel->getActiveSheet()->setCellValue('M1', '归元等级');
				$objPHPExcel->getActiveSheet()->setCellValue('N1', '御风等级');
				$objPHPExcel->getActiveSheet()->setCellValue('O1', '幻化个数');
				$objPHPExcel->getActiveSheet()->setCellValue('P1', '内丹等级');
				
				if (is_array($bag)) {
					foreach($bag as $k => $item){
						$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["level"]); 
						$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["count"]);
						$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["qtlevel"]);
						$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["cflevel"]);
						$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["qllevel"]);
						$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["jflevel"]);
						$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["jllevel"]);
						$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["thlevel"]);
						$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["jglevel"]);
						$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["bmlevel"]);
						$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["yqhqlevel"]);
						$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["jxlevel"]);
						$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item["gylevel"]);
						$objPHPExcel->getActiveSheet()->setCellValue('N'.($k+2), $item["yqlevel"]);
						$objPHPExcel->getActiveSheet()->setCellValue('O'.($k+2), $item["hh"]);
						$objPHPExcel->getActiveSheet()->setCellValue('P'.($k+2), $item["nd"]);
					}	
				}

				$objPHPExcel->getActiveSheet()->setTitle('Simple');

				$objPHPExcel->setActiveSheetIndex(0);
				$file_name = "坐骑_".$startdate;
				
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				exit;
			}
		

	}
	
	/**
	 * 导出星宿信息excel
	 */
	public function starexcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$obj = D('game'.$this->ip);
		$num = get_var_value('num');
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rolelist = $point->field('guid,accountid,serverid,rolename,seconddata,roleheadid,level,sex,carrer,stardrom')->table('player_table')->order('level desc')->select();
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj->table('online_sec')->where(array('o_date >='=> $this->startdate,'o_date <='=> $this->enddate))->order('o_level desc')->limit(0,$time)->select();	
		if(!empty($list)){
		
			foreach($list as $key => $value){
				if(!empty($rolelist[$key]) && $list['o_userid'] =$rolelist[$key]['guid']) {
					$data = $rolelist[$key]['stardrom'];
					
					$da = json_decode($data,true);
					$da = $da['StarLifeLevelList'];
					if(is_array($da)){
						$bag[$key]['u_name'] = $rolelist[$key]['rolename'];
						$bag[$key]['one'] = $da[0]['nLifeLevel'];
						$bag[$key]['two'] = $da[1]['nLifeLevel'];
						$bag[$key]['three'] = $da[2]['nLifeLevel'];
						$bag[$key]['four'] = $da[3]['nLifeLevel'];
						$bag[$key]['five'] = $da[4]['nLifeLevel'];
						$bag[$key]['six'] = $da[5]['nLifeLevel'];
						$bag[$key]['seven'] = $da[6]['nLifeLevel'];
						$bag[$key]['eight'] = $da[7]['nLifeLevel'];
					}
				}
			}

			require_once(AClass.'phpexcel/PHPExcel.php');
			
			$objPHPExcel = new PHPExcel();
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
							 
			$objPHPExcel->setActiveSheetIndex(0);	
			$objPHPExcel->getActiveSheet()->setCellValue('A1', '角色'); 
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '命格一星等级');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '命格二星等级');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '命格三星等级');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '命格四星等级');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '命格五星等级');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '命格六星等级');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '命格七星等级');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '命格八星等级');
			
			if (is_array($bag)) {
				foreach($bag as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["u_name"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["one"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["two"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["three"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["four"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["five"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["six"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["seven"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["eight"]);
				}

				$objPHPExcel->getActiveSheet()->setTitle('Simple');

				$objPHPExcel->setActiveSheetIndex(0);
				$file_name = "星宿_".$startdate;
				
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				exit;
			
			}

		}
	}
	
	/**
	 * 导出神兵信息excel
	 */
	public function magicexcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$obj = D('game_info');
		$num = get_var_value('ran');
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj-> table("heart_list") ->where(array("creattime >="=>$this->startdate,"creattime <="=>$this->enddate))->limit(0,$time)-> select();
		if(!empty($list)){

			require_once(AClass.'phpexcel/PHPExcel.php');
			
			$objPHPExcel = new PHPExcel();
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
							 
			$objPHPExcel->setActiveSheetIndex(0);	
			$objPHPExcel->getActiveSheet()->setCellValue('A1', '日期'); 
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '账号');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '角色id');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '角色名称');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '心法名称');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '基础等级');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '卓越等级');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["creattime"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["h_code"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["u_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["u_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["h_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["base"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["excell"]);
				}

				$objPHPExcel->getActiveSheet()->setTitle('Simple');

				$objPHPExcel->setActiveSheetIndex(0);
				$file_name = "心法_".$startdate;
				
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				exit;
			
			}

		}
	}
	
	/**
	 * 导出国家属性excel
	 */
	public function trickexcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$obj = D('game'.$this->ip);
		$num = get_var_value('num');
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rolelist = $point->field('guid,accountid,serverid,rolename,seconddata,roleheadid,level,sex,carrer,forevermapdata')->table('player_table')->order('level desc')->select();
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj->table('online_sec')->where(array('o_date >='=> $this->startdate,'o_date <='=> $this->enddate))->order('o_level desc')->limit(0,$time)->select();	
		
		if(!empty($list)){
			foreach($list as $k => $item){
				if(!empty($rolelist[$k]) && $list['o_userid'] =$rolelist[$k]['guid']) {
					$data = $rolelist[$k]['forevermapdata'];
				
					$da = json_decode($data,true);
					foreach($da as $key => $value){
						if($value['k'] == 151){
							$bag[$k]['sm'] = $value['v'];
						}else{
							$bag[$k]['sm'] = 0;
						}
						if($value['k'] == 152){
							$bag[$k]['mz'] = $value['v'];
						}else{
							$bag[$k]['mz'] = 0;
						}
						if($value['k'] == 153){
							$bag[$k]['sb'] = $value['v'];
						}else{
							$bag[$k]['sb'] = 0;
						}
						if($value['k'] == 154){
							$bag[$k]['bj'] = $value['v'];
						}else{
							$bag[$k]['bj'] = 0;
						}
						if($value['k'] == 155){
							$bag[$k]['jr'] = $value['v'];
						}else{
							$bag[$k]['jr'] = 0;
						}
						$bag[$k]['u_name'] = $rolelist[$k]['rolename'];
					}	
				}
			}

			require_once(AClass.'phpexcel/PHPExcel.php');
			
			$objPHPExcel = new PHPExcel();
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
							 
			$objPHPExcel->setActiveSheetIndex(0);	
			$objPHPExcel->getActiveSheet()->setCellValue('A1', '角色'); 
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '生命增强等级');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '命中增强等级');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '闪避增强等级');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '暴击增强等级');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '坚韧增强等级');
			
			if (is_array($bag)) {
				foreach($bag as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["u_name"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["sm"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["mz"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["sb"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["bj"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["jr"]);
				}

				$objPHPExcel->getActiveSheet()->setTitle('Simple');

				$objPHPExcel->setActiveSheetIndex(0);
				$file_name = "国家_".$startdate;
				
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				exit;
			
			}

		}
	}
	
}