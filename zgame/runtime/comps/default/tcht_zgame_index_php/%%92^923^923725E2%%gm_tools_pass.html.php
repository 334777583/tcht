<?php /* Smarty version 2.6.18, created on 2014-08-08 09:30:15
         compiled from gmtools/gm_tools_pass.html */ ?>
<!DOCTYPE html>
<html>
<head>
<title>运营工具-道具审批</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/skin.css" rel="stylesheet" type="text/css">
<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/jquery-ui.css" rel="stylesheet" type="text/css">
</script>
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
.mytable td {
	font-size: 12px;
}
-->
</style>
</head>
<body>
	<div>
		<div id="tabs-1" class="tabitem">
			<div>
				<table class="explain">
					<thead>
					</thead>
					<tbody style="font-family:Mingliu">
						<tr>
							<td width="5%"  class="tableleft"><b>说明：</b></td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1、谨慎处理运营申请道具奖励，<font color = "red"><b>通过</b></font>前请仔细查看道具详情,点击<font color = "red"><b>明细</b></font>查看！</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">2、批量操作只处理未审核的，已处理过的会跳过该条记录不处理</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="topinfo">
				<div>
					<span>服务器</span>
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
					<span>审核状态</span>
					<select id="s_type">
						<option value="1">未审核</option>
						<option value="2">不通过</option>
						<option value="4">发送成功</option>
						<option value="3">发送失败</option>
						<option value="10">全部</option>
					</select>
					<span>开始日期：</span>
					<input type="text" id="startdate" class="input1"/>
					<span>结束日期：</span>
					<input type="text" id="enddate" class="input1"/>
					<input type="button" value="搜索" style="margin-left:20px" id="serach"/>
					<input type="button" value="批量通过" style="margin-left:20px" id="pass_all"/>
					<input type="button" value="批量不通过" style="margin-left:20px" id="fail_all"/>
					<input type="button" value="刷新页面查看处理结果" onclick="showTable(1);"/>
				</div>
			</div>
			<div style="clear:both"></div>
			<div>
				<table  class="mytable">
					<thead>
						<tr>
							<th><input type='checkbox' id='c_all'/></th>
							<th>服务器</th>
							<th>申请者</th>
							<th>申请时间</th>
							<th>元宝</th>
							<th>铜钱</th>
							<th>申请原因</th>
							<th>信件内容</th>
							<th>赠送玩家列表</th>
							<th>道具详情</th>
							<th>审核状态</th>
							<th>审核人</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody id="passBody">
					</tbody>
				</table>
				<div id="pagehtml" style="float:right;margin-right:20px"></div>
				<div id="example_length" class="dataTables_length" style="display:none">
					<label>每页显示
						<select id="menu" name="example_length" size="1" aria-controls="example">
						<option value="10">10</option>
						<option value="25" selected="selected">25</option>
						<option value="50">50</option>
						<option value="100">100</option>
						</select> 条记录
					</label>
				</div>
			</div>

		</div>
		<div style="height:50px">&nbsp;</div>
	</div>
	
	<!--弹出覆盖层-->
	<div class="overlay" style="display:none">
		<table style="width:220px;height:100%;margin:0 auto;">
			<tr>
				<td style="text-align:center">
					<img src='<?php echo $this->_tpl_vars['res']; ?>
/images/ajax-loader.gif'/>
				</td>
			</tr>
		</table>
	</div>
	
	
	<!-- 道具详情 -->
	<div id="form"  style="display:none">
		<div class="ajaxform">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
				<tbody>
					<tr id="lv_info" style="display:none">
						<td>
							<table cellspacing="0" cellpadding="0" style="border: 1px solid #859497;" width="100%">
								<tr>
									<td>
										<span>最小等级：</span><span id="t_minlv">0</span>
									</td>
								</tr>
								<tr>
									<td>
										<span>最大等级：</span><span id="t_maxlv">0</span>
									</td>
								</tr>
								<tr>
									<td>
										<span>邮件接收截止时间：</span><span id="t_endtime">0</span>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>道具列表：</td>
					</tr>
					<tr>
						<td>
							<table class="tooltable">
								<thead>
									<tr>
										<th>道具ID</th>
										<th>道具名称</th>
										<th>数量</th>
										<th>绑定状态（0为非绑定，1为绑定）</th>
									</tr>
								</thead>
								<tbody id="form_tools">
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
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
		//时间插件
		$("#startdate").datepicker();
		$("#enddate").datepicker();
		showTitle("GM工具:道具审批");
		
		$("#sip").change(function() {
			if($("#sip").val() == 0){
				$("#dform").dialog({
					height: 500,
					width: 700,
					buttons :{
						"确认": function(){
							var item = $(':radio[name="db"]:checked').val();
							$("#sip").val(item);
							$(this).dialog("close");
						},
						"关闭" : function(){
							$(this).dialog("close");
						}
					}
				})
			}
		})
				
		//验证数据
		var validator = function(){
			var isok = true;
			var reg = /\d{4}-\d{2}-\d{2}/;
			var start = $("#startdate").val();
			var end = $("#enddate").val();
			if (start != "" && end != ""){
				if(!reg.test(start) || !reg.test(end)){
					alert("请输入格式为YYYY-MM-DD的时间");
					isok = false;
				}else if(start>end){
					alert("结束时间要大于开始时间！");
					isok = false;
				}
			}
			return isok;
		}
		
		//搜索
		$("#serach").click(function(){
			if(validator()){
				showTable(1);
			}
		})
		
		
		//显示申请审核列表
		var showTable =  function(page){
			$.ajax({
				type:"GET",
				dataType:"json",
				url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gmtoolspass/getPassTable",
				data:
				{
					pageSize : $("#menu").val(),
					curPage : page,
					startdate : $("#startdate").val(),
					enddate : $("#enddate").val(),
					ip : $("#sip").val(),
					stype : $("#s_type").val()
				},
				beforeSend:function(){
					$("#passBody").html("<tr><td colspan='15'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
				},
				success:function(data){
					var list = [];
					$("#passBody").html("");//清空表格，防止叠加
					$("#pagehtml").html("");//清除分页 
					if(typeof(data.list) != 'undefined'){
						list = data.list;
					}
					if(list.length > 0 ){
						$("#example_length").show();//显示每页
						var tbody = "";
						for(var i in list){
							var model = "noclass";
							var text = "";
							switch(parseInt(list[i]["t_status"])){
								case 1: 
									text = "未审核";
									break;
								case 2: 
									text = "不通过";
									model="fail";
									break;
								case 3:
									text = "已审核但发送失败";
									model="fail";
									break;
								case 4:	
									text = "已审核并发送成功";
									model="pass";
									break;
								case -1:
									text = "申请已取消";
									model="fail";
									break;
								case -2:	
									text = "正在处理";
									info = "<span class='pass'>正在处理</span>";
									break;		
								default: 
									text = "未知";
									model="fail";		
									
							}
							
							tbody += "<tr id='"+list[i]["t_id"]+"'>";
							tbody += "<td><input type='checkbox' name='user' value='"+list[i]["t_id"]+";"+list[i]["t_role_name"]+"'/></td>";
							tbody += "<td>"+data.ipList[list[i]["t_ip"]]+"</td>";
							tbody += "<td>"+list[i]["t_operaor"]+"</td>";
							tbody += "<td>"+list[i]["t_inserttime"]+"</td>";
							tbody += "<td>"+list[i]["t_gold"]+"</td>";
							tbody += "<td>"+list[i]["t_copper"]+"</td>";
							tbody += "<td>"+list[i]["t_reason"]+"</td>";
							tbody += "<td>"+list[i]["t_content"]+"</td>";
							tbody += "<td>"+list[i]["t_role_name"]+"</td>";
							tbody += "<td class='tdetial'>明细</td>";
							tbody += "<td class='"+model+"' id='status_"+list[i]["t_id"]+"'>"+text+"</td>";
							tbody += "<td>"+list[i]["t_auditor"]+"</td>";
							if(parseInt(list[i]["t_status"]) == 3){
								tbody += "<td><span class='tpass'>重发</span><span class='notpass'>不通过</span></td>";
							}else if(parseInt(list[i]["t_status"]) == 2){
								tbody += "<td><span class='fail'>不通过</span></td>";
							}else if(parseInt(list[i]["t_status"]) == -1) { 
								tbody += "<td><span class='fail'>已取消</span></td>";
							}else if(parseInt(list[i]["t_status"]) == 4){
								tbody += "<td><span class='pass'>已发送</span></td>";
							}else if(parseInt(list[i]["t_status"]) == 1){
								tbody += "<td><span class='tpass'>通过</span><span class='notpass'>不通过</span></td>";
							}else if(parseInt(list[i]["t_status"]) == -2){
								tbody += "<td><span class='pass'>正在处理</span></td>";
							}else {
								tbody += "<td></td>";
							}
							tbody += "</tr>";
						}
						$("#passBody").html(tbody);
						$("#passBody tr:odd").css("background-color", "#edf2f7"); 
						$("#passBody tr:even").css("background-color","#e0f0f0");
						$("#pagehtml").html(data.pageHtml);
					}else{
						$("#passBody").html("");
						$("#passBody").html("<tr><td colspan='15'>没有数据！</td></tr>");
					}
				},
				error:function(){
					$("#passBody").html("");
					$("#passBody").html("<tr><td colspan='15'>没有数据！</td></tr>");
				}
			})	
		}
		
		//页面加载显示table
		showTable(1);
		
		//禁言每页显示
		$("#menu").change(function(){
			showTable(1);
		});
		
		//分页ajax函数
		var pageAjax = function(page){
			showTable(page);
		}

		//跳到相应页面 
		var go = function(){
			var pagenum = $("#page").val();
			if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
				alert('请输入一个正整数！');
				$("#page").val(1);
			}else{
				pageAjax(pagenum);
			}
		}
		
		//全选
		$("#c_all").click(function(){
			if($(this).is(":checked")) {
				$("input[name=user]").attr("checked", true);
			} else {
				$("input[name=user]").removeAttr("checked");
			}
		})
		
		//明细
		$(".tdetial").live("click",function(){
			var id = $(this).parent().attr("id");
			var str = $(this).prev().text();
			if(str == "全服") {
				$("#lv_info").show();
			} else {
				$("#lv_info").hide();
			}
			
			$.ajax({
				type : "get",
				url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmtoolsask/getDetail",
				dataType : "json",
				data : {
					id : id
				},
				beforeSend : function(){
					$(".overlay").show();
				},
				complete : function(){
					$(".overlay").hide();
				},
				success : function(data){
					if(typeof(data.toolList) != 'undefined'){
						var html = "";
						if(typeof(data.moneyList) != 'undefined'){
							if(data.moneyList["t_minlv"] == '0') {
								data.moneyList["t_minlv"] = "无限制"
							}
							if(data.moneyList["t_maxlv"] == '0') {
								data.moneyList["t_maxlv"] = "无限制"
							}
							if(data.moneyList["t_endtime"] == '0') {
								data.moneyList["t_endtime"] = "系统默认"
							} 
							
							$("#t_minlv").html(data.moneyList["t_minlv"]);
							$("#t_maxlv").html(data.moneyList["t_maxlv"]);
							$("#t_endtime").html(data.moneyList["t_endtime"]);
						}
						
						if(data.toolList.length >0){
							for(var i in data.toolList){
								html += "<tr>";
								html += "<td>"+data.toolList[i]["t_tid"]+"</td>";
								html += "<td>"+data.toolList[i]["t_name"]+"</td>";
								html += "<td>"+data.toolList[i]["t_num"]+"</td>";
								html += "<td>"+data.toolList[i]["t_bstatus"]+"</td>";
								html += "</tr>";
							}
							$("#form_tools").html(html);
						}else{
							$("#form_tools").html("<tr><td colspan='7'>没有记录！</td></tr>");
						}
					}else{
						$("#form_tools").html("<tr><td colspan='7'>没有记录！</td></tr>");
					}
					
					$("#form").dialog({
						height: 500,
						width: 700,
						buttons :{
							"关闭" : function(){
								$(this).dialog("close");
							}
						}
					})
				},
				error:function(){
					$("#form_tools").html("<tr><td colspan='7'>没有记录！</td></tr>");
				}
			})
		})
		
		
		//批量通过
		$("#pass_all").click(function() {
			var user_list = [];
			var flag = true;
			$("input[name=user]:checked").each(function(){
				var id = "#status_"+$(this).parent().parent().attr('id');
				var text = $(id).text();
				if(text == '未审核') {
					user_list.push($(this).val());
				}
				flag = false;
			});
			if(flag) {
				alert('请先选择');
				return false;
			} else if(user_list.length == 0){
				alert('勾选中不存在未审核记录！');
				return false;
			}
			for(var i in user_list) {
				var arr = user_list[i].split(";");
				var id = arr[0];
				var roleName = arr[1];
				$.ajax({
					type : "post",
					url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmtoolspass/changeStatus",
					dataType : "json",
					data : {
						id : id,
						roleName : roleName,
						ip : $("#sip").val()
					},
					beforeSend : function(){
						$(".overlay").show();
					},
					complete : function(){
						$(".overlay").hide();
					},
					success : function(data){
						if(data != 'error'){
							var id = data.id;
							var status = data.status;
							var model = "noclass";
							var text = "";
							var info = "";
							switch(parseInt(status)){
								case -1 :
									text = "申请已取消";
									model="fail";
									info = "<span class='fail'>已取消</span>";
									break;
								case 1: 
									text = "未审核";
									info = "<span class='tpass'>通过</span><span class='notpass'>不通过</span>";
									break;
								case 2: 
									text = "不通过";
									model="fail";
									info = "<span class='fail'>不通过</span>";
									break;
								case 3:
									text = "已审核但发送失败";
									model="fail";
									info = "<span class='tpass'>重发</span><span class='notpass'>不通过</span>";
									break;
								case 4:	
									text = "已审核并发送成功";
									model="pass";
									info = "<span class='pass'>已发送</span>";
									break;
								case -2:	
									text = "正在处理";
									info = "<span class='pass'>正在处理</span>";
									break;		
								default: 
									text = "未知";
									info = "<span class='fail'>未知</span>";
									model="fail";	
									
							}
							$("#status_"+id).html(text);
							$("#status_"+id).next().html(data.auditor);
							$("#status_"+id).next().next().html(info);
							$("#status_"+id).attr("class",model);
							$("input[name=user]:checked").removeAttr("checked");	//取消选择  
						}else{
							alert('远程超时没响应！');
						}
					},
					error:function(){
						alert("error");
					}
				})
			}
		})
		
		//批量不通过
		$("#fail_all").click(function() {
			var user_list = [];
			var flag = true;
			$("input[name=user]:checked").each(function(){
				var id = "#status_"+$(this).parent().parent().attr('id');
				var text = $(id).text();
				if(text == '未审核') {
					user_list.push($(this).val());
				}
				flag = false;
			});
			if(flag) {
				alert('请先选择');
				return false;
			} else if(user_list.length == 0){
				alert('勾选中不存在未审核记录！');
				return false;
			}
			for(var i in user_list) {
				var arr = user_list[i].split(";");
				var id = arr[0];
				var roleName = arr[1];
				$.ajax({
					type : "post",
					url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmtoolspass/nopass",
					dataType : "json",
					data : {
						id : id,
						roleName : roleName,
						ip : $("#sip").val()
					},
					beforeSend : function(){
						$(".overlay").show();
					},
					complete : function(){
						$(".overlay").hide();
					},
					success : function(data){
						if(data != 'error'){
							var id = data.id;
							var status = data.status;
							var model = "noclass";
							var text = "";
							switch(parseInt(status)){
								case -1 :
									text = "申请已取消";
									model="fail";
									info = "<span class='fail'>已取消</span>";
									break;
								case 1: 
									text = "未审核";
									info = "<span class='tpass'>通过</span><span class='notpass'>不通过</span>";
									break;
								case 2: 
									text = "不通过";
									model="fail";
									info = "<span class='fail'>不通过</span>";
									break;
								case 3:
									text = "已审核但发送失败";
									model="fail";
									info = "<span class='tpass'>重发</span><span class='notpass'>不通过</span>";
									break;
								case 4:	
									text = "已审核并发送成功";
									model="pass";
									info = "<span class='pass'>已发送</span>";
									break;
								case -2:	
									text = "正在处理";
									info = "<span class='pass'>正在处理</span>";
									break;		
								default: 
									text = "未知";
									info = "<span class='fail'>未知</span>";
									model="fail";	
							}
							$("#status_"+id).html(text);
							$("#status_"+id).next().html(data.auditor);
							$("#status_"+id).next().next().html(info);
							$("#status_"+id).attr("class",model);
							$("input[name=user]:checked").removeAttr("checked");	//取消选择  
						}else{
							alert('远程超时没响应！');
						}
					},
					error:function(){
						alert("error");
					}
				})
			}
		})
		
		//审核通过
		$(".tpass").live("click",function(){
			var id = $(this).parent().parent().attr("id");
			var roleName = $(this).parent().prev().prev().prev().prev().html();
			$.ajax({
				type : "post",
				url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmtoolspass/changeStatus",
				dataType : "json",
				data : {
					id : id,
					roleName : roleName,
					ip : $("#sip").val()
				},
				beforeSend : function(){
					$(".overlay").show();
				},
				complete : function(){
					$(".overlay").hide();
				},
				success : function(data){
					if(data != 'error'){
						var id = data.id;
						var status = data.status;
						var model = "noclass";
						var text = "";
						var info = "";
						switch(parseInt(status)){
							case -1 :
								text = "申请已取消";
								model="fail";
								info = "<span class='fail'>已取消</span>";
								break;
							case 1: 
								text = "未审核";
								info = "<span class='tpass'>通过</span><span class='notpass'>不通过</span>";
								break;
							case 2: 
								text = "不通过";
								model="fail";
								info = "<span class='fail'>不通过</span>";
								break;
							case 3:
								text = "已审核但发送失败";
								model="fail";
								info = "<span class='tpass'>重发</span><span class='notpass'>不通过</span>";
								break;
							case 4:	
								text = "已审核并发送成功";
								model="pass";
								info = "<span class='pass'>已发送</span>";
								break;
							case -2:	
								text = "正在处理";
								info = "<span class='pass'>正在处理</span>";
								break;		
							default: 
								text = "未知";
								info = "<span class='fail'>未知</span>";
								model="fail";	
								
						}
						$("#status_"+id).html(text);
						$("#status_"+id).next().html(data.auditor);
						$("#status_"+id).next().next().html(info);
						$("#status_"+id).attr("class",model);
					}else{
						alert('远程超时没响应！');
					}
				},
				error:function(){
					alert("error");
				}
			})
			
		})
		
		
		//审核不通过
		$(".notpass").live("click",function(){
			var id = $(this).parent().parent().attr("id");
			var roleName = $(this).parent().prev().prev().prev().prev().html();
			$.ajax({
				type : "post",
				url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmtoolspass/nopass",
				dataType : "json",
				data : {
					id : id,
					roleName : roleName,
					ip : $("#sip").val(),
				},
				beforeSend : function(){
					$(".overlay").show();
				},
				complete : function(){
					$(".overlay").hide();
				},
				success : function(data){
					var id = data.id;
					var status = data.status;
					var model = "noclass";
					var text = "";
					var info = "";
					switch(parseInt(status)){
						case -1 :
							text = "申请已取消";
							model="fail";
							info = "<span class='fail'>已取消</span>";
							break;
						case 1: 
							text = "未审核";
							info = "<span class='tpass'>通过</span><span class='notpass'>不通过</span>";
							break;
						case 2: 
							text = "不通过";
							model="fail";
							info = "<span class='fail'>不通过</span>";
							break;
						case 3:
							text = "已审核但发送失败";
							model="fail";
							info = "<span class='tpass'>重发</span><span class='notpass'>不通过</span>";
							break;
						case 4:	
							text = "已审核并发送成功";
							model="pass";
							info = "<span class='pass'>已发送</span>";
							break;
						case -2:	
							text = "正在处理";
							info = "<span class='pass'>正在处理</span>";
							break;	
						default: 
							text = "未知";
							info = "<span class='fail'>未知</span>";
							model="fail";	
							
					}
					$("#status_"+id).html(text);
					$("#status_"+id).next().html(data.auditor);
					$("#status_"+id).next().next().html(info);
					$("#status_"+id).attr("class",model);
				},
				error:function(){
					alert("error");
				}
			})
		})
		
	</script>
</body>
</html>