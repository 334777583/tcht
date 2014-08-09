<?php /* Smarty version 2.6.18, created on 2014-08-01 15:25:38
         compiled from system/user_model.html */ ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/skin.css" rel="stylesheet" type="text/css">
<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/jquery-ui.css" rel="stylesheet" type="text/css">
<title>用户管理</title>
</head>
<body>
	<div class="container">
		<div>
			<div  id="user-tabs">
				<span id="1" class="user-gray">基本信息</span>
				<span id="2" class="user-gray">角色设置</span>
				<span id="3">模块设置</span>
			</div>
			<hr/>
			<div id="tabs-1" class="tabitem">
 					<div style="float:right">
 						<img src="<?php echo $this->_tpl_vars['res']; ?>
/images/add-btn2.png" style="cursor: pointer;" id="addbtn"/>
 					</div>
			 	</div>
				<table class="mytable" id="mytable" cellspacing="0" align="center">
					<thead>
						<tr>
							<th>序号</th>
							<th>模块代码</th>
							<th>模块名称</th>
							<th>创建时间</th>
							<th>修改时间</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody id="mbody">
					</tbody>
				</table>
				<div id="pagehtml" style="float:right;margin-right:20px;display:block"></div>
				<div style="clear:both"></div>
				<div style="height:30px">&nbsp;</div>
			</div>
		</div>	
	</div>
	<div id="confirm"  style="display:none">
		<div style="text-align: center;">确定要永久删除吗？</div>
	</div>
	
	<div id="editform"  style="display:none">
		<div class="ajaxform">
			<table width="80%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
				<tbody>
					<tr>
						<td align="right">模块代码：</td>
						<td><input type="text" class="input-1" id="mcode" ></td>
					</tr>
					<tr>
						<td align="right">模块名称：</td>
						<td><input type="text" class="input-1" id="mname" ></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	
</body>

	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery-ui.js" type="text/javascript"></script> 
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script>
	<script>
	$(function() {
		//获取基本信息 
		getdata(1);
		
		showTitle("GM工具：权限管理");
		
		//切换标签
		$("#user-tabs span").click(function(){
			window.location = "<?php echo $this->_tpl_vars['app']; ?>
/system/show/pageId/"+this.id;
		})
		
		var editdata = function(id,code,flag,obj){
			$.post(
					'<?php echo $this->_tpl_vars['app']; ?>
/system/editModel',
					{
						id : id,
						code :code,
						flag : flag
					},
					function(data){
						if(data == "success"){
							if(flag == 1){
								obj.prev().removeClass("stopicon").addClass("yesicon");
								obj.text("启用");
								obj.removeClass("stopbtn").addClass("yesbtn");
							}else if(flag == 0){
								obj.prev().removeClass("yesicon").addClass("stopicon");
								obj.text("停用");
								obj.removeClass("yesbtn").addClass("stopbtn");
							}
						}else if(data == "error"){
							alert("操作失败！");
						}
					},
					'json'
				);	
		}
		//停用
		$(".stopbtn").live("click",function(){
			var id = $(this).closest(".m_tr").attr("id");
			var code = $(this).closest(".m_tr").find(".m_code").html();
			editdata(id,code,1,$(this));
		})
		
		//启用
		$(".yesbtn").live("click",function(){
			var id = $(this).closest(".m_tr").attr("id");
			var code = $(this).closest(".m_tr").find(".m_code").html();
			editdata(id,code,0,$(this))
		})
		
		//编辑
		$(".editbtn").live("click",function(){
			var id = $(this).closest(".m_tr").attr("id");
			$.get(
					'<?php echo $this->_tpl_vars['app']; ?>
/system/getModelById',
					{
						id : id,
						time : Date.parse(new Date())
					},
					function(data){
						if(data){
							$("#mcode").val(data["cf_code"]);
							$("#mname").val(data["cf_name"]);
							$("#editform").dialog({
								height: 300,
								width: 400,
								buttons: {
									'保存' : function(){
										$.post(
											'<?php echo $this->_tpl_vars['app']; ?>
/system/saveModel',
											{
												id : id,
												mcode : $("#mcode").val(),
												mname : $("#mname").val()
											},
											function(data){
												if(data == "error"){
													alert("更新失败!");
												}else if(data == "success"){
													window.location.reload();
												}
											},
											'json'
										)
									},
									'取消' : function(){
										$(this).dialog("close");
									}
								}
							})
						}else{
							alert("出错啦！");
						}
					},
					'json'
			)
		})
		
		//添加 
		$("#addbtn").click(function(){
			$("#mcode").val("");
			$("#mname").val("");
			
			$("#editform").dialog({
				height: 300,
				width: 400,
				buttons: {
					'确定' : function(){
						$.post(
							'<?php echo $this->_tpl_vars['app']; ?>
/system/addModel',
							{
								mcode : $("#mcode").val(),
								mname : $("#mname").val()
							},
							function(data){
								if(data != "success"){
									alert(data);
								} else {
									window.location.reload();
								}
							},
							'json'
						)
					},
					'取消' : function(){
						$(this).dialog("close");
					}
				}
			})
		
		})
		
		//删除
		$(".deletebtn").live("click",function(){
			var id = $(this).closest(".m_tr").attr("id");
			
			$("#confirm").dialog({
				height: 150,
				width: 300,
				buttons: {
					'确定' : function(){
						$.post(
							'<?php echo $this->_tpl_vars['app']; ?>
/system/deleteModel',
							{
								id : id
							},
							function(data){
								if(data == "error"){
									alert("删除失败!");
								}else if(data == "success"){
									window.location.reload();
								}
							},
							'json'
						)
					},
					'取消' : function(){
						$(this).dialog("close");
					}
				}
			})
		});
		
	});

	//ajax获取用户数据 
	var getdata = function(page){
		$.get(
			'<?php echo $this->_tpl_vars['app']; ?>
/system/getModel',
			function(data){
				var list =  [];
				if(data){
					list = data;
				}
				var tbody = "";
				if(list.length>0){
					for(var i in list){
						tbody += "<tr id=" + list[i]["cf_id"] + " class='m_tr'>";
						tbody += "<td>" + list[i]["cf_id"] + "</td>";
						tbody += "<td class='m_code'>" + list[i]["cf_code"] + "</td>";
						tbody += "<td>" + list[i]["cf_name"] + "</td>";
						tbody += "<td>" + list[i]["cf_createtime"] + "</td>";
						tbody += "<td>" + list[i]["cf_updatetime"] + "</td>";
					
						if(list[i]["cf_flag"] == '0'){
							tbody += "<td><label class=\"stopicon\"></label><span class=\"stopbtn\">停用</span><label class=\"editicon\"></label><span class=\"editbtn\">编辑</span><label class=\"deleteicon\"></label><span class=\"deletebtn\">删除</span></td>";
						}else if(list[i]["cf_flag"] == '1'){
							tbody += "<td><label class=\"yesicon\"></label><span class=\"yesbtn\">启用</span><label class=\"editicon\"></label><span class=\"editbtn\">编辑</span><label class=\"deleteicon\"></label><span class=\"deletebtn\">删除</span></td>";
						}
						tbody += "</tr>";
					}
					
					$("#mbody").html(tbody);
					//table单双行交叉样式
					$(".mytable tr:odd").css("background-color", "#edf2f7"); 
					$(".mytable tr:even").css("background-color","#e0f0f0"); 
				}else{
					$("#mbody").html("<tr><td colspan='6'>没有记录！</td></tr>");
				}
			},
			'json'
		);	
	}

	</script>
</html>