<?php
/**
 * FileName: analyxml.class.php
 * Description:解析xml道具ID数据
 * Author: jan,hjt
 * Date:2013-9-23 9:53:13
 * Version:1.02
 */
class uploadtxt{
	private $point	;
	/**
	 * 将excel数据导入数据表
	 */
	public function upload(){
		ini_set('upload_max_filesize','10M');
		ini_set('post_max_size','10M');
		if ((($_FILES["file"]["type"] == "text/plain"))&& ($_FILES["file"]["size"] < 20000)){  
			$type = get_var_value('fileToUpload');
			$sip = get_var_value('sip');
			$freezetime = get_var_value('freezetime');
			$reason = get_var_value('reason');
			if ($_FILES["file"]["error"] > 0){  
				echo "Return Code: " . $_FILES["file"]["error"] . "<br>";  
			}else{  
				if (file_exists("upload/" . $_FILES["file"]["name"])){  
					echo $_FILES["file"]["name"] . " already exists. ";  
				} else{  
					move_uploaded_file($_FILES["file"]["tmp_name"],UPLOAD.'filetoupload.txt'); 
					$path = UPLOAD.'filetoupload.txt';
					$file = file_get_contents($path);
					
					$file = explode(';',$file);
					$arr = array();
					$con = mysql_connect('203.195.182.47:3307','jianhh','wm_zhans_2014_tc_jianhh',true) or die ("数据库连接失败");
					mysql_select_db('game',$con) or die ("数据表连接失败");
					mysql_query('set names utf8',$con);
					
					if($type == 1){		//禁言
						$ins_data = "insert into php_cmd(GmCmd,ServerId,stype,pHandled) values ";
						foreach($file as $key => $name){
							$time = strtotime("now") + $freezetime;
							$gm = '{'.' "cmd":"forbidchat","name":"'.iconv('GB2312', 'UTF-8',$name).'","time":'.$time.'}';
							$ins_data .= "('".addslashes($gm)."',".$sip.",4,1),";
						}
					}else if($type == 3){		//下线
						$ins_data = "insert into php_cmd(GmCmd,ServerId,stype,pHandled) values ";
						foreach($file as $key => $name){
							$gm = '{'.' "cmd":"kickplayer","name":"'.iconv('GB2312', 'UTF-8',$name).'","info":"'.$reason.'"}';
							$ins_data .= "('".addslashes($gm)."',".$sip.",4,1),";
						}
					}else if($type == 2){				//冻结
						$name = '"'.implode('","',$file).'"';
						$name = iconv('GB2312', 'UTF-8',$name);
						$ins_data = "insert into forbid_login(Account,forbid_time) values ";
						$sql = 'select g.account ,g.id from game_user g join player_table p on g.id = p.accountid where p.RoleName in ('.$name.')';
						$account = mysql_query($sql);
						while($row = mysql_fetch_assoc($account)){
							$ro[]= $row;
						}
						
						$del = 'delete from forbid_login where Account in (';
						foreach($ro as $key => $acc){
							$time = strtotime("now") + $freezetime;
							$ins_data .= "('".$acc['account']."','".$time."'),";
							$del .= '"'.$acc['account'].'",';
						}
						$del = rtrim($del, ',');
						$del .=')';
						$del_str = mysql_query($del);
						
					}
				}
			}
			$ins_data = rtrim($ins_data, ',');
			$ins_str = mysql_query($ins_data);
			if(!$ins_str){
				echo 'false';
				exit;
			}
		mysql_close($con);
		$this->assign('ty',$type);
		$this->display("gmtools/jump");
		}else{  
			echo "Invalid file";  
		}  
	}
	
	
	
	
	
	
}