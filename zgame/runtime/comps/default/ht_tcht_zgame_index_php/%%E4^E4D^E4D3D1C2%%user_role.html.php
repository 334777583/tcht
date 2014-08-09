<?php /* Smarty version 2.6.18, created on 2014-07-31 09:30:37
         compiled from system/user_role.html */ ?>
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
				<span id="2">角色设置</span>
				<span id="3" class="user-gray">模块设置</span>
			</div>
			<hr/>
		</div>
		
		<div id="tabs-1" class="tabitem">
			<div style="text-align:right">
				<img src="<?php echo $this->_tpl_vars['res']; ?>
/images/add-btn2.png" style="cursor: pointer;" id="addbtn"/>
			</div>
		</div>
		
		
		<div id="tabs-2" class="tabitem">
			<div>
				<table 	width="100%" border="0" cellspacing="0" cellpadding="0">
					<tbody>
						<?php $_from = $this->_tpl_vars['groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['group']):
?>
						<tr>
							<td>
								<table 	width="100%" border="0" cellspacing="0" cellpadding="0" class="roletable">
									<tr>
										<td id="<?php echo $this->_tpl_vars['group']['g_id']; ?>
">
											<span style="font-size: 25px;"><?php echo $this->_tpl_vars['group']['g_name']; ?>
</span>
											<?php if ($this->_tpl_vars['group']['g_flag'] == '0'): ?>
											<label class="stopicon" style="top:0px"></label>
											<span class="rolestop">停用</span>
											<?php elseif ($this->_tpl_vars['group']['g_flag'] == '1'): ?>
											<label class="yesicon" style="top:0px"></label>
											<span class="roleyes">启用</span>
											<?php endif; ?>
											<label class="deleteicon" style="top:0px"></label>
											<span class="roledelete">删除</span>
										<!-- 	<label class="addicon" style="top:0px"></label>
											<span class="roleadd">添加用户</span> -->
											<label class="saveicon" style="top:0px"></label>
											<span class="rolesave">保存</span>
										</td>
									</tr>
									<tr>
										<td class="auth">
											<span>所在用户:</span>
										</td>
									</tr>
									<tr>
										<td>
											<div>
												<?php $_from = $this->_tpl_vars['guser'][$this->_tpl_vars['group']['g_id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['user']):
?>
													<div class="usertab" id="<?php echo $this->_tpl_vars['user']['u_id']; ?>
" rel="{<$group.g_id>}">
														<span class="caption"><?php echo $this->_tpl_vars['user']['u_name']; ?>
</span><img id="login" class="closeicon" src="<?php echo $this->_tpl_vars['res']; ?>
/images/close.gif"/>
													</div>
												<?php endforeach; endif; unset($_from); ?>
											</div>
										</td>
									</tr>
									<tr>
										<td class="auth">
											<span>所属权限:</span>
										</td>
									</tr>
									<?php $_from = $this->_tpl_vars['modellist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['model']):
?>
										<tr bgcolor="#e0f0f0">
											<td>
												<div class="mainmod"><span><?php echo $this->_tpl_vars['model']['cf_name']; ?>
</span><input type="checkbox" class="tmodel" value="<?php echo $this->_tpl_vars['model']['cf_code']; ?>
" /></div>
											</td>
										</tr>
										<tr bgcolor="#edf2f7" class="u_<?php echo $this->_tpl_vars['group']['g_id']; ?>
">
											<td class="roleSelect">
												<?php $_from = $this->_tpl_vars['codelist'][$this->_tpl_vars['model']['cf_code']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['code']):
?>
													<?php if (in_array ( $this->_tpl_vars['code']['cf_code'] , $this->_tpl_vars['gcode'][$this->_tpl_vars['group']['g_id']] )): ?>
													<div  class="modelselect"><input type="checkbox" checked="checked" name="list" value="<?php echo $this->_tpl_vars['code']['cf_code']; ?>
"><span><?php echo $this->_tpl_vars['code']['cf_name']; ?>
</span></div>
													<?php else: ?>
													<div  class="modelselect"><input type="checkbox" name="list" value="<?php echo $this->_tpl_vars['code']['cf_code']; ?>
"><span><?php echo $this->_tpl_vars['code']['cf_name']; ?>
</span></div>
													<?php endif; ?>
												<?php endforeach; endif; unset($_from); ?>
											</td>
										</tr>
									<?php endforeach; endif; unset($_from); ?>
								</td>
							</tr>
							</table>
						<?php endforeach; endif; unset($_from); ?>
					</tbody>
				</table>
			</div>
			<div style="height:30px">&nbsp;<input type="hidden" id="closeId" value=""/></div>
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
						<td align="right">用户组名称：</td>
						<td><input type="text" class="input-1" id="group" ></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div id="userform"  style="display:none">
		<div class="ajaxform">
			<table width="80%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
				<tbody>
					<tr>
						<td align="right">全选：</td>
						<td><input type="checkbox" id="allUser"></td>
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
	
	<script type="text/javascript">
		showTitle("GM工具：权限管理");
	
		//角色设置中，子模块全选对应的大模块选中
		var len = $('body').find(".roleSelect").length;
		if(len > 0){
			$('body').find(".roleSelect").each(function(index,dom){
				var num = $(dom).find(".modelselect").length;	//当前子模块数量
				var snum = $(dom).find("input[name='list']:checked").length;	//选中子模块数量
				if(num ==  snum){
					//alert($(dom).parent().prev().find(".tmodel").html());
					$(dom).parent().prev().find(".tmodel").attr("checked",true);
				}
			})
		}
	</script>
	
	
	<script>
	$(function() {		
		var select = function(){
				var len = $('body').find(".roleSelect").length;
				if(len > 0){
					$('body').find(".roleSelect").each(function(index,dom){
						var num = $(dom).find(".modelselect").length;	//当前子模块数量
						var snum = $(dom).find("input[name='list']:checked").length;	//选中子模块数量
						if(num ==  snum){
							//alert($(dom).parent().prev().find(".tmodel").html());
							$(dom).parent().prev().find(".tmodel").attr("checked",true);
						}
					})
				}
		}
		
		select();
		
		//draggable
		$( ".usertab" ).draggable({
			revert: true,
			addClasses : false,
			opacity: 0.35
		});
		
		//切换标签
		$("#user-tabs span").click(function(){
			window.location = "<?php echo $this->_tpl_vars['app']; ?>
/system/show/pageId/"+this.id;
		})
		
		//模块选中，底下子模块全部选择
		$(".tmodel").click(function(){
			var $box = $(this).parent().parent().parent().next();
			if($(this).is(":checked")){
				$box.find("input[name=list]").attr("checked",true);
			}else{
				$box.find("input[name=list]").attr("checked",false);
			}
		});
		
		//停用角色
		$(".rolestop").click(function(){
			var id = $(this).parent().attr("id");
			$.post(
				'<?php echo $this->_tpl_vars['app']; ?>
/system/editRole',
				{
					id : id,
					flag : 1,
					time : Date.parse(new Date())
				},
				function(data){
					if(data == "error"){
						alert("更新失败!");
					}else if(data == "success"){
						window.location.reload();
					}
				},
				'json'
			);
		})
		
		
		//启用角色
		$(".roleyes").click(function(){
			var id = $(this).parent().attr("id");
			$.post(
				'<?php echo $this->_tpl_vars['app']; ?>
/system/editRole',
				{
					id : id,
					flag : 0,
					time : Date.parse(new Date())
				},
				function(data){
					if(data == "error"){
						alert("更新失败!");
					}else if(data == "success"){
						window.location.reload();
					}
				},
				'json'
			);
		})
		
		//保存角色
		$(".rolesave").click(function(){
			var id = $(this).parent().attr("id");
			var codeList =  new Array();
			var codeString = "";
			var userString = "";
			var model = ".u_"+id;
			$(model).find("input[name=list]:checked").each(function(index,dom){
				codeList.push($(dom).val());
			})
			
			$(model).each(function(index,dom){
				if($(dom).find("input[name=list]:checked").length > 0){
					codeList.push($(dom).prev().find(".tmodel").val());
				}
			})
			
			var userList = new Array();
			$(this).parent().parent().next().next().find(".usertab:hidden").each(function(index,dom){
				userList.push($(dom).attr("id"));
			})
			codeString = codeList.join(",");
			userString = userList.join(",");
			$.post(
					'<?php echo $this->_tpl_vars['app']; ?>
/system/saveRole',
					{
						id : id,
						codeString : codeString,
						userString : userString,
						time : Date.parse(new Date())
					},
					function(data){
						if(data == "error"){
							alert("保存失败!");
						}else if(data == "success"){
							alert("保存成功!");
							window.location.reload();
						}
					},
					'json'
			);
		})
		
		//关闭用户标签
		$(".closeicon").click(function(){
			var id = $(this).parent().attr("id");
			$(this).parent().hide();
		});
		
		//保存用户 
		$(".roleadd").click(function(){
			$("#userform").dialog({
				height: 300,
				width: 400,
				buttons: {
					'保存' : function(){
						$.post(
							'<?php echo $this->_tpl_vars['app']; ?>
/system/save',
							{
								id : cid,
								username : $("#username").val(),
								password : $("#password").val(),
								realname : $("#realname").val(),
								phone : $("#phone").val(),
								email : $("#email").val(),
								gid : 	$("#usergroup").val(),
								time : Date.parse(new Date())
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
		});
		
		//添加用户组 
		$("#addbtn").click(function(){
			$("#editform").dialog({
				height: 200,
				width: 400,
				buttons: {
					'添加' : function(){
						$.post(
							'<?php echo $this->_tpl_vars['app']; ?>
/system/addGroup',
							{
								group : $("#group").val()
							},
							function(data){
								if(data == "error"){
									alert("添加失败!");
								}else if(data == "success"){
									alert("添加成功!");
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
		
		
		//删除用户组
		$(".roledelete").click(function(){
			if(confirm("是否删除该用户和所属的用户？")) {
				var gid = $(this).parent().attr("id");
				$.post(
					'<?php echo $this->_tpl_vars['app']; ?>
/system/deleteGroup',
					{
						id : gid
					},
					function(data){
						if(data == "error"){
							alert("删除失败")
						}else if(data == "success"){
							alert("删除成功！");
							window.location.reload();
						}
					},
					'json'
				)
				
			}
		})
		
	});

	</script>
	
</html>