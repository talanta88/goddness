$(function() {
	$('form[name="permission"]').on('submit',function() {
		var name 			= $('#name'),
			contro_name 	= $('#contro_name'),
			action_name 	= $('#action_name'),
			parent_id 		= $('#parent_id');

		if(name.val().length < 1 || name.val().length > 30){
			$.dialog_show('e','权限名称1-30位之间');
			name.focus();
			return false;
		}

		if(contro_name.val().length < 1 || contro_name.val().length > 30){
			$.dialog_show('e','控制器名称1-30位之间');
			contro_name.focus();
			return false;
		}

		if(parent_id.val() != 0) {
			if(action_name.val().length < 1 || action_name.val().length > 30){
				$.dialog_show('e','方法名称1-30位之间');
				action_name.focus();
				return false;
			}
		}
	});


	$('.get_son').on('click',function() {
		var sonrole 			= $(this).parent().parent().attr('sonrole');
		var son 				= $('tr[role="'+sonrole+'"]');
		if(son.attr('style') == 'display: none;')
			son.show();
		else 
			son.hide();
	});

	$('.del').on('click',function() {
		var id 				= $(this).attr('id');
		var parent_id 		= $(this).attr('parent_id');
		var _this 			= $(this);
		$.ajax({
			url 		: Think.URL + '/ajaxDel?id=' + id,
			type 		: 'get',
			beforeSend 	: function() {
				// $.screen_lock();
			},
			success 	: function(res) {
				if(res.substr(0,5) == 'error') {
					$.dialog_show('e',res);
					// $.screen_unlock();
				}else {
					_this.parent().parent().remove();
					if(parent_id == 0) {
						$('tr[role="'+id+'"]').remove();
					}
					$.dialog_show('s','处理成功');
					// $.screen_unlock();
				}
			}
		});
	});


	$('a.show').on('click',function() {
		var _this 			= $(this);
		var text			= _this.text();
		var id 				= _this.attr('id');
		if(text == '是') {
			var value 		= 0;
			var str 		= '否';
		}else {
			var value 		= 1;
			var str 		= '是';
		}
		$.ajax({
			url 		: Think.URL + '/ajaxShow',
			type 		: 'get',
			data 		: {'id':id,'value':value},
			success 	: function(res) {
				if(res == -1)
					$.dialog_show('e','修改失败，服务器错误');
				else if(res == 0)
					$.dialog_show('e','修改失败，非法传值');
				else if(res.substr(0,5) == 'error')
					$.dialog_show('e',res);
				else {
					$.dialog_show('s','修改成功');
					_this.text(str);
				}
			}
		});
	});


	$('.per_checkbox').on('change',function() {
		var _this 			= $(this);
		var parent_id 		= _this.attr('parent_id');
		var value 			= _this.attr('value');
		if(_this.prop('checked') == true) {
			if(parent_id == 0) 
				//选中所有的子类
				$('input[parent_id="'+value+'"]').prop('checked',true);
			else 
				$('input[value="'+parent_id+'"]').prop('checked',true);
			
		}else if(_this.prop('checked') == false) {
			if(parent_id == 0) 
				//放弃所有的子类
				$('input[parent_id="'+value+'"]').prop('checked',false);
			else {
				$('input[value="'+parent_id+'"]').prop('checked',false);
				$.each($('input[parent_id="'+parent_id+'"]'),function(i,n) {
					if($(n).prop('checked') == true)
						$('input[value="'+parent_id+'"]').prop('checked',true);
				});
			} 
		}
	});


	$('form[name="role"]').on('submit',function() {
		var name_len 			= $('#name').val().length;
		if(name_len <= 0 || name_len > 30) {
			$.dialog_show('e','角色名称不能为空或者大于30位');
			$('#name').focus();
			return false;
		}
		if($(':checked').length == 0) {
			$.dialog_show('e','您必须为该角色分配权限');
			return false;
		}

		return true;
	});


	$('a.role_del').on('click',function() {
		var _this 			= $(this);
		var value 			= _this.parent().parent().attr('value');
		if(confirm('确认删除该角色吗？')) {
			$.ajax({
				url 		: Think.URL + '/ajaxDelRole',
				type 		: 'get',
				data 		: {'value':value},
				success		: function(res) {
					if(res == -1)
						$.dialog_show('e','删除失败，服务器错误');
					else if(res == 0)
						$.dialog_show('e','删除失败，非法传值');
					else if(res.substr(0,5) == 'error')
						$.dialog_show('e',res);
					else if(res == 1) {
						_this.parent().parent().remove();
						$.dialog_show('s','删除成功');
					}else 
						$.dialog_show('e','删除失败，管理员<' + res + '>正在使用该角色，请先修改该管理员的角色');
				}
			});
		}
	});

	$('form[name="admin"]').on('submit',function() {

		var name_len 		= $('#name').val().length;
		if(name_len <= 0 || name_len > 30) {
			$.dialog_show('e','管理员账号不能为空或者大于30位');
			$('#name').focus();
			return false;
		}

		var pass_len 			= $('#pass').val().length;
		if(pass_len > 0) {
			if(pass_len < 6) {
				$.dialog_show('e','密码至少6位');
				$('#pass').focus();
				return false;
			}
		}

		if($('input[name="is_super"]:checked').val() == 0) {
			if($('input[name="rol[]"]:checked').val() == undefined){
				$.dialog_show('e','请为该管理选择角色');
				$('input[name="rol[]"]').focus();
				return false;
			}
		}

		return true;
	});

	$('a.admin_del').on('click',function() {
		var _this 			= $(this);
		var value 			= _this.attr('value');
		if(confirm('确认删除该管理员么？')) {
			$.ajax({
				url 		: Think.URL + '/adminDel',
				type		: 'get',
				data 		: {'value':value},
				success 	: function(res) {
					if(res == -1)
						$.dialog_show('e','删除失败，服务器错误');
					else if(res == 0)
						$.dialog_show('e','删除失败，非法传值');
					else if(res.substr(0,5) == 'error')
						$.dialog_show('e',res);
					else if(res == 1) {
						_this.parent().parent().remove();
						$.dialog_show('s','删除成功');
					}else {
						$.dialog_show('e',res);
					}
				}
			});
		}
	});


})