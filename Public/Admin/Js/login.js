$(function() {
	$('img').on('click',function() {
		var src         = $(this).attr('src');
		$(this).attr({'src':src + '?'});
	});

	//初始化dialog的位置
	$(document).on('ready',function() {
		var dialog 		= $('.dialog');
		var height 		= ($(window).height() - dialog.height()) / 2;
		var width 		= ($(window).width() - dialog.width()) / 2;
		$('.dialog').css({'left':width,'top':height});
	});

	$('form').on('submit',function() {
		var username 		= $('#username'),
			password 		= $('#password'),
			code 			= $('#code'),
			dialog 			= $('.dialog')
			button 			= $('button');

		if(username.val().length < 2 || username.val().length > 30) {
			dialog.html('用户名必须在2-30位之间').show();
			setTimeout(function() {
				dialog.html('').hide();
			},800);
			username.focus();
			return false;
		}

		if(password.val().length < 6) {
			dialog.html('密码至少6位').show();
			setTimeout(function() {
				dialog.html('').hide();
			},800);
			password.focus();
			return false;
		}

		if(code.val().length != 4) {
			dialog.html('验证码必须是4位').show();
			setTimeout(function() {
				dialog.html('').hide();
			},800);
			code.focus();
			return false;
		}

		$.ajax({
			url 		: Think.URL + '/checkCode?code=' + code.val(),
			type 		: 'get',
			beforeSend 	: function() {
				button.html('检查验证码中...').attr({'disabled':'disabled'});
			},
			success 	: function(res) {
				if(res == 0) {
					dialog.html('验证码错误').show();
					code.val('');
					setTimeout(function() {
						dialog.html('').hide();
						button.html('登录').removeAttr('disabled');
					},800);
					code.focus();
				}else {
					$.ajax({
						url 		: Think.URL + '/login',
						type 		: 'post',
						data 		: {
							username 	: username.val(),
							password 	: password.val()
						},
						beforeSend 	: function() {
							button.html('登录中...').attr({'disabled':'disabled'});
						},
						success 	: function(res) {
							if(res.substr(0,5) == 'error') {
								dialog.html(res).show();
								setTimeout(function() {
									dialog.html('').hide();
									button.html('登录').removeAttr('disabled');
								},800);
							}else 
								location.href= Think.MODULE;	
						}						
					});
				}
			}
		});
		return false;
	});







});