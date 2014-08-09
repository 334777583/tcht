<?php /* Smarty version 2.6.18, created on 2014-07-31 09:54:10
         compiled from gmtools/ip.html */ ?>
﻿<!DOCTYPE html>
<html>
<head>
	<title>ip链接查询</title>
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
	<div style="margin-top:20px;" id="user-tabs">
			<span class="iptimes">ip查询</span>
			<span class="playerip">玩家ip</span>
			<hr>
	</div>
	<div class="display1">
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
							<td width="95%" class="tableleft">1、********</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="topinfo">
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
					<input type="text" class="input1" id="startdate" value="<?php echo $this->_tpl_vars['startdate']; ?>
"/>
					<input type="button" value="查询" id="history" style="margin-left:20px"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div id="tabs-1" class="tabitem">	
				<div>
					<table  class="mytable" cellspacing="0" align="center" id="dtable">
						<thead>
							<tr>
								<th width="80">ip</th>
								<th width="80">登录账号数</th>
								<th width="80">登录角色数</th>
								<th width="80">登录次数</th>
								<th width="250">登录账号</th>
								<th width="40">操作</th>
							</tr>
						</thead>
						<tbody id="dtatr_body">
						</tbody>
					</table>
				</div>
			</div>
			
			<div style="clear:both"></div>
		</div>
		
		<div style="height:50px">&nbsp;</div>
		
	</div>
	
		<div class="display2" style="display:none;">
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
							<td width="95%" class="tableleft">1、********</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="topinfo">
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
					时间<input type="text" class="input1" id="startdate1" value="<?php echo $this->_tpl_vars['startdate']; ?>
"/>
					角色名<input type="text" id="rolename" />
					<input type="button" value="查询" id="playeripquery" style="margin-left:20px"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div id="tabs-1" class="tabitem">	
				<div>
					<table  class="mytable" cellspacing="0" align="center" id="dtable">
						<thead>
							<tr>
								<th width="60">服务器</th>
								<th width="80">玩家id</th>
								<th width="80">角色名</th>
								<th width="80">账号</th>
								<th>最近登录ip</th>
								<th width="80">操作</th>
							</tr>
						</thead>
						<tbody id="dtatr_body1">
						</tbody>
					</table>
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
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery-ui.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script> 	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#startdate").datepicker();
			$("#startdate1").datepicker();
			showTitle("GM工具:ip链接查询");
			$('#history').click(function(){
					show(1);
			});
			
			//查询玩家ip
			$('#playeripquery').click(function(){
				ipshow();
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
			
			$('.iptimes').click(function(){
				$('.display1').show();$('.display2').hide();
			});
			$('.playerip').click(function(){
				$('.display1').hide();$('.display2').show();
			});
			//show(1);
		});
		
		function show(page){
			$.ajax({
					type: 'POST',
					url: '<?php echo $this->_tpl_vars['logicApp']; ?>
/ipquery/getIpData',
					dataType: 'json',
					data: {
						ip: $(".display1 #sip").val(),
						startdate: $('#startdate').val()
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
								tbody += "<td>" + data[i]["ip"] + "</td>";
								tbody += "<td>" + data[i]["accountnum"] + "</td>";
								tbody += "<td>" + data[i]["playerid"] + "</td>";
								tbody += "<td>" + data[i]["count"] + "</td>";
								tbody += "<td>" + data[i]["account"] + "</td>";
								tbody += "<td>&nbsp;</td>";
								tbody += "</tr>";
							}
							$("#dtatr_body").html(tbody);
						}
						else {
							$("#dtatr_body").html("<tr><td colspan='6'>没有数据！</td></tr>");
						}
					},
					error: function(){
						$("#dtatr_body").html("<tr><td colspan='6'>没有数据！</td></tr>");
					}
				});
		}
		
		function ipshow(){
			var rolename = $("#rolename").val();
			if(!rolename){
				alert('请输入角色名');return false;
			}
			$.ajax({
					type: 'POST',
					url: '<?php echo $this->_tpl_vars['logicApp']; ?>
/ipquery/getPlayerIp',
					dataType: 'json',
					data: {
						ip: $(".display2 #sip").val(),
						startdate: $('#startdate1').val(),
						rolename:rolename
					},
					beforeSend: function(){
						$("#dtatr_body1").html("<tr><td colspan='6'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success: function(data){
						if (typeof(data) != 'undefined' && data.length > 0) {
							var tbody = "";
							var url = '';
							for (var i in data) {
								tbody += "<tr>";
								tbody += "<td>" + data[i]["serverid"] + "</td>";
								tbody += "<td>" + data[i]["playerid"] + "</td>";
								tbody += "<td>" + data[i]["rolename"] + "</td>";
								tbody += "<td>" + data[i]["account"] + "</td>";
								tbody += "<td>" + data[i]["ip"] + "</td>";
								tbody += "<td>&nbsp;</td>";
								tbody += "</tr>";
							}
							$("#dtatr_body1").html(tbody);
						}
						else {
							$("#dtatr_body1").html("<tr><td colspan='6'>没有数据！</td></tr>");
						}
					},
					error: function(){
						$("#dtatr_body1").html("<tr><td colspan='6'>没有数据！</td></tr>");
					}
				});
		}
	</script>
</body>
</html>