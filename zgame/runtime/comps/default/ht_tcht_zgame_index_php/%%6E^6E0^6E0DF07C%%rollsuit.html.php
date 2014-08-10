<?php /* Smarty version 2.6.18, created on 2014-08-05 10:24:00
         compiled from system/rollsuit.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>滚服统计</title>
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
								<?php if ($this->_tpl_vars['ip']['s_id'] > 1): ?>
									<option value="<?php echo $this->_tpl_vars['ip']['s_id']; ?>
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
								<?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
							<option value="0" >全部</option>
						</select>
					</label>
					<input type="button" value="查询" id="history" style="margin-left:20px"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div id="tabs-1" class="tabitem">	
				<div>
					<table  class="mytable" cellspacing="0" align="center" id="dtable">
						<thead>
							<tr>
								<th>服务器</th>
								<th>总创角（去重）</th>
								<th>滚服创角（去重）</th>
								<th>滚服创角率</th>
								<th>总付费数</th>
								<th>滚服付费数</th>
								<th>滚服付费率</th>
								<th>总充值元宝</th>
								<th>滚服充值元宝</th>
								<th>滚服付费比</th>
							</tr>
						</thead>
						<tbody id="dtatr_body">
						</tbody>
					</table>
					<table  class="mytable" cellspacing="0" align="center" id="dtable">
						<thead>
							<tr>
								<th>首创游戏服</th>
								<th>游戏服</th>
								<th>账号ID</th>
								<th>玩家账号</th>
								<th>角色ID</th>
								<th>角色名</th>
								<th>创建时间</th>
								<th>最后登录时间</th>
								<th>充值次数</th>
								<th>充值元宝（总）</th>
							</tr>
						</thead>
						<tbody id="dtatr_body1">
						</tbody>
					</table>
					<div id="pagehtml" style="float:right;margin-right:20px"></div>
					<div id="example_length" class="dataTables_length"  style="display:none">
						<label>每页显示
							<select id="menu" name="example_length" size="1" aria-controls="example">
							<option value="10">10</option>
							<option value="25">25</option>
							<option value="50" selected="selected">50</option>
							<option value="100">100</option>
							</select> 条记录
						</label>
					</div>
					
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
					<?php if ($this->_tpl_vars['ip']['s_id'] > 1): ?>
							<?php if ($this->_tpl_vars['key']%5 == '0'): ?>
							<br/><br/>
							<?php endif; ?>
					<input type="radio" name="db" value='<?php echo $this->_tpl_vars['ip']['s_id']; ?>
'  class="cbox"/><span><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php endif; ?>
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
			showTitle("游戏数据统计：滚服统计");
			
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
			
			$("#history").click(function(){
				historyshow(1);
			});
			
		});
		
		
		function historyshow(page){
            $.ajax({
                    type: 'POST',
                    url: '<?php echo $this->_tpl_vars['logicApp']; ?>
/rollsuit/getData',
                    dataType: 'json',
                    data: {
                        ip: $("#sip").val(),
                        pageSize: $("#menu").val(),
                        curPage: page
                    },
                    beforeSend: function(){
                        $("#dtatr_body").html("<tr><td colspan='10'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
						$("#dtatr_body1").html("<tr><td colspan='10'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
                    },
                    success: function(data){
                        //$("#example_length").show();
                        var result = data.result;
						var accountList = data.accountList;
                        if (typeof(result) != 'undefined') {
                            var tbody = "";
                            tbody += "<tr>";
                                tbody += "<td>" + result["sid"] + "</td>";
                                tbody += "<td>" + result["create_all"] + "("+result["create_all_1"]+")" + "</td>";
                                tbody += "<td>" + result["create"] + "("+result["create_1"]+")" +  "</td>";
                                tbody += "<td>" + result["create_per"] + "</td>";
                                tbody += "<td>" + result["pay_count_all"] + "</td>";
								tbody += "<td>" + result["pay_count"] + "</td>";
								tbody += "<td>" + result["pay_count_per"] + "</td>";
								tbody += "<td>" + result["pay_all"] + "</td>";
								tbody += "<td>" + result["pay"] + "</td>";
								tbody += "<td>" + result["pay_per"] + "</td>";
                                tbody += "</tr>";
                            $("#dtatr_body").html(tbody);
							
						var tbody1 = "";
                            for (var i in accountList) {
                                tbody1 += "<tr>";
                                tbody1 += "<td>" + accountList[i]["oldServerId"] + "</td>";
                                tbody1 += "<td>" + accountList[i]["ServerId"] + "</td>";
                                tbody1 += "<td>" + accountList[i]["id"] + "</td>";
                                tbody1 += "<td>" + accountList[i]["account"] + "</td>";
                                tbody1 += "<td>" + accountList[i]["GUID"] + "</td>";
								tbody1 += "<td>" + accountList[i]["RoleName"] + "</td>";
								tbody1 += "<td>" + accountList[i]["CreateTime"] + "</td>";
								tbody1 += "<td>" + accountList[i]["LoginTime"] + "</td>";
								tbody1 += "<td>" + accountList[i]["paynum"] + "</td>";
								tbody1 += "<td>" + accountList[i]["paymoney"] + "</td>";
                                tbody1 += "</tr>";
                            }
							$("#dtatr_body1").html(tbody1);
                            //$("#pagehtml").html(data.pageHtml); //分页
                            $('#orderKey').val('');
                        }else {
                            $("#example_length").hide();
                            $("#pagehtml").html("");
                            $("#dtatr_body").html("<tr><td colspan='10'>没有数据！</td></tr>");
							$("#dtatr_body1").html("<tr><td colspan='10'>没有数据！</td></tr>");
                        }
                    },
                    error: function(){
                        $("#example_length").hide();
                        $("#pagehtml").html("");
                        $("#dtatr_body").html("<tr><td colspan='10'>没有数据！</td></tr>");
						$("#dtatr_body1").html("<tr><td colspan='10'>没有数据！</td></tr>");
                    }
                })
		}
		
		//跳到相应页面 
		var go = function(){
			var pagenum = $("#page").val();
			if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
				alert('请输入一个正整数！');
				$("#page").val(1);
			}else{
				//recharge_query.show(pagenum);
				formAjax(pagenum);
			}
		}
		
		//分页ajax函数
		var formAjax = function(page){
			recharge_query.historyshow(page);
		}
	</script>
</body>
</html>