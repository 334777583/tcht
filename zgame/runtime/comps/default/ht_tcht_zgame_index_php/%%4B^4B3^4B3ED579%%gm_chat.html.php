<?php /* Smarty version 2.6.18, created on 2014-07-31 10:56:00
         compiled from gmtools/gm_chat.html */ ?>
﻿<!DOCTYPE html>
<html>
<head>
	<title>聊天监控</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/skin.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/jquery-ui.css" rel="stylesheet" type="text/css">
	<style type="text/css">
	<!--
	body {
		margin-left: 0px;
		margin-top: 0px;
		margin-right: 0px;
		margin-bottom: 0px;
		background-color: #EEF2FB;
		font-size: 12px;
	}
	-->
	</style>
</head>
<body>
	<div>
		<div>
			<div>
				<table class="explain">
					<thead>
					</thead>
					<tbody style="font-family:Mingliu">
						<tr>
							<td width="5%"  class="tableleft"><b>说明：</b></td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1、**************</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="topinfo">
				<div>
					查看聊天类型：
					<input type="checkbox" name="chatType[]" checked="checked" value="0">私聊
					<input type="checkbox" name="chatType[]" checked="checked" value="1">队伍
					<input type="checkbox" name="chatType[]" checked="checked" value="2">帮派
					<input type="checkbox" name="chatType[]" checked="checked" value="3">世界
					<input type="checkbox" name="chatType[]" checked="checked" value="10">国家聊天
					<!--目前只输出 私聊 帮派 世界 国家聊天 组队
					<input type="checkbox" name="chatType[]" value="4">喇叭
					<input type="checkbox" name="chatType[]" value="5">系统
					<input type="checkbox" name="chatType[]" value="6">中央屏幕
					<input type="checkbox" name="chatType[]" value="7">好友聊天
					<input type="checkbox" name="chatType[]" value="8">陌生人消息
					<input type="checkbox" name="chatType[]" value="9">场景聊天
					<input type="checkbox" name="chatType[]" value="11">传闻(强化)
					<input type="checkbox" name="chatType[]" value="12">组队招募广播
					--->
					<br/><br/>
				</div>
				<div>
					<label>
						<span>服务器:</span>
						<select id="sip">
							<?php $_from = $this->_tpl_vars['ipList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ip']):
?>
								<option value="<?php echo $this->_tpl_vars['ip']['s_id']; ?>
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
							<?php endforeach; endif; unset($_from); ?>
							<option value="0" >全部</option>
						</select>
					</label>
					查看最近<select id="chatnum">
								<option value="100">100</option>
								<option value="200">200</option>
								<option value="300">300</option>
								<option value="400">400</option>
								<option value="500">500</option>
						</select>条聊天记录
					<input type="button" value="查询" id="history" style="margin-left:20px"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div id="tabs-1" class="tabitem">	
				<div>
					<table  class="mytable" cellspacing="0" align="center" id="dtable">
						<thead>
							<tr>
								<th width="60">聊天频道</th>
								<th width="60">玩家id</th>
								<th width="120">角色名</th>
								<th>说话内容</th>
								<th width="110">时间</th>
								<th width="90">操作</th>
							</tr>
						</thead>
						<tbody id="dtatr_body">
						</tbody>
					</table>
					
					<div id="pagehtml" style="float:right;margin-right:20px"></div>
				</div>
				
			</div>
			
			<div style="clear:both"></div>
		</div>
		
		<div style="height:50px">&nbsp;</div>
		
	</div>
	<!-- 服务器 -->
	<div id="dform"  style="display:none">
		<div class="ajaxform">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
				
				<?php $_from = $this->_tpl_vars['ipList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['ip']):
?>
					<?php if ($this->_tpl_vars['key']%5 == '0'): ?>
						<br/><br/>
					<?php endif; ?>
						<input type="radio" name="db" value='<?php echo $this->_tpl_vars['ip']['s_id']; ?>
'  class="cbox"/><span><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php endforeach; endif; unset($_from); ?>
			</table>
		</div>
	</div>
	
	<!-- 强制下线弹出框 -->	
<div id="xxform"  style="display:none">
	<div class="ajaxform">
		<table width="80%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
			<tbody>
				<tr>
					<td align="right" width="35%">角色名：</td>
					<td width="65%">
						<span id="rname3"></span>
					</td>
				</tr>
				<tr>
					<td align="right" width="35%">操作原因：</td>
					<td width="65%">
						<textarea class="input1" cols="20" rows="6" id="offreason">玩家因发布不文明信息，强制下线</textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<!-- 冻结弹出框 -->	
<div id="djform"  style="display:none">
	<div class="ajaxform">
		<table width="80%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
			<tbody>
				<tr>
					<td align="right" width="35%">角色名：</td>
					<td width="65%">
						<span id="rname2"></span>
					</td>
				</tr>
				<tr>
					<td align="right" width="35%">冻结时长：</td>
					<td width="65%">
						<select id="freezetime">
							<option value="315360000">永久</option>
							<option value="60">1分钟</option>
							<option value="300">5分钟</option>
							<option value="600">10分钟</option>
							<option value="1800">30分钟</option>
							<option value="3600">1小时</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right" width="35%">操作原因：</td>
					<td width="65%">
						<textarea class="input1" cols="20" rows="6" id="freezereason">玩家开挂，冻结</textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<!-- 禁言弹出框 -->	
<div id="jyform"  style="display:none">
	<div class="ajaxform">
		<table width="80%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
			<tbody>
				<tr>
					<td align="right" width="35%">角色名：</td>
					<td width="65%">
						<span id="rname"></span>
					</td>
				</tr>
				<tr>
					<td align="right" width="35%">禁言时长：</td>
					<td width="65%">
						<select id="stoptime">
							<option value="315360000">永久</option>
							<option value="60">1分钟</option>
							<option value="300">5分钟</option>
							<option value="600">10分钟</option>
							<option value="1800">30分钟</option>
							<option value="3600">1小时</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right" width="35%">操作原因：</td>
					<td width="65%">
						<textarea class="input1" cols="20" rows="6" id="reason">玩家因发布不文明信息，禁言</textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery-ui.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script> 	
	<script type="text/javascript">
		$(document).ready(function(){
			showTitle("GM工具:聊天监控");
			$('#history').click(function(){
					show();
			});
            $("#sip").change(function(){
                if ($("#sip").val() == 0) {
                    $("#dform").dialog({
                        height: 500,
                        width: 700,
                        buttons: {
                            "确认": function(){
                                var item = $(':radio[name="db"]:checked').val();
                                $("#sip").val(item);
                                $(this).dialog("close");
                            },
                            "关闭": function(){
                                $(this).dialog("close");
                            }
                        }
                    })
                }
            });
			//show();
			
		});
		
		function show(){
			var checked = [];
		    $('input:checkbox:checked').each(function() {
		            checked.push($(this).val());
		    });
			$.ajax({
					type: 'POST',
					url: '<?php echo $this->_tpl_vars['logicApp']; ?>
/gmchat/getChatContents',
					dataType: 'json',
					data: {
						ip: $("#sip").val(),
						chatnum:$("#chatnum").val(),
						chatType:checked
					},
					beforeSend: function(){
						$("#dtatr_body").html("<tr><td colspan='6'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success: function(data){
						if (typeof(data) != 'undefined' && data.length > 0) {
							var tbody = "";
							var url = '';
							for (var i in data) {
								tbody += "<tr>";
								tbody += "<td>" + data[i]["channel"] + "</td>";
								tbody += "<td>" + data[i]["playid"] + "</td>";
								tbody += "<td>" + data[i]["send_name"] + "</td>";
								tbody += "<td>" + data[i]["content"] + "</td>";
								tbody += "<td>" + data[i]["date"] + "</td>";
								tbody += "<td><a href='javascript:;' class='djbt' roleid="+data[i]['playid']+" rolename="+data[i]["send_name"]+">冻结</a>&nbsp;&nbsp;";
								tbody += "<a href='javascript:;' class='xxbt' roleid="+data[i]['playid']+" rolename="+data[i]["send_name"]+">下线</a>&nbsp;&nbsp;";
								tbody += "<a href='javascript:;' class='jybt' roleid="+data[i]['playid']+" rolename="+data[i]["send_name"]+">禁言</a></td>";
								tbody += "</tr>";
							}
							$("#dtatr_body").html(tbody);
							
											//快捷冻结
	$(".djbt").click(function(){
		var id = $(this).attr('roleid');
		var rolename = $(this).attr('rolename');
		var ip = $("#sip").val();
		//var freezetime = $("#freezetime").val();
		//var reason = $("#freezereason").val();
		$("#rname2").html(rolename);
		$("#djform").dialog({
			height: 300,
			width: 500,
			buttons: {
				'确定' : function(){
					$.ajax({
						type  : "post",
						url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/freeze",
						data :
						{
							ip : ip,
							rolename : rolename,
							freezetime :$("#freezetime").val(),
							reason : $("#freezereason").val()
						},
						cache : false,
						dataType : 'json',
						success : function(data){
							if(typeof(data.error) != 'undefined')
							{
								if(data.error != '')
								{
									alert(data.error);
								}
							}else{
								alert("冻结成功");
								$("#djform").dialog("close");
							}
						},
						error : function(){
							alert('error');
						}
					})
				},
				'取消' : function(){
					$(this).dialog("close");
				}
			}
		})
	});
	
		//强制下线 
	$(".xxbt").click(function(){
		var id = $(this).attr('roleid');
		var rolename = $(this).attr('rolename');
		var ip = $("#sip").val();
		//var reason = $("#offreason").val();
		$("#rname3").html(rolename);
		$("#xxform").dialog({
			height: 300,
			width: 500,
			buttons: {
				'确定' : function(){
					$.ajax({
						type  : "post",
						url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/offline",
						data :
						{
							ip : ip,
							rolename : rolename,
							reason : $("#offreason").val()
						},
						cache : false,
						dataType : 'json',
						success : function(data){
							if(typeof(data.error) != 'undefined')
							{
								if(data.error != '')
								{
									alert(data.error);
								}
							}else{
								alert("强制下线成功");
								$("#xxform").dialog("close");
							}
						},
						error : function(){
							alert('error');
						}
					})
				},
				'取消' : function(){
					$(this).dialog("close");
				}
			}
		})	
	})	;
	
		//快捷禁言
	$(".jybt").click(function(){
		var id = $(this).attr('roleid');
		var rolename = $(this).attr('rolename');
		var ip = $("#sip").val();
		//var stoptime = $("#stoptime").val();
		//var reason = $("#reason").val();
		var time = Date.parse(new Date());
		$("#rname").html(rolename);
		$("#jyform").dialog({
			height: 300,
			width: 500,
			buttons: {
				'确定' : function(){
					$.ajax({
						type  : "post",
						url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/stoptalk",
						data :
						{
							ip : ip,
							rolename : rolename,
							stoptime :$("#stoptime").val(),
							reason :  $("#reason").val(),
							time : time
						},
						dataType : 'json',
						success : function(data){
							if(typeof(data.error) != 'undefined')
							{
								if(data.error != '')
								{
									alert(data.error);
								}
							}else{
								alert("禁言成功");
								$("#jyform").dialog("close");
							}
						},
						error : function(){
							alert('error');
						}
					})
				},
				'取消' : function(){
					$(this).dialog("close");
				}
			}
		})
	});
							
						}
						else {
							$("#dtatr_body").html("<tr><td colspan='6'>没有数据！</td></tr>");
						}
					},
					error: function(){
						$("#example_length").hide();
						$("#pagehtml").html("");
						$("#dtatr_body").html("<tr><td colspan='6'>没有数据！</td></tr>");
					}
				});
		}
		
		
	
	</script>
</body>
</html>