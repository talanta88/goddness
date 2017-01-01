$(function() {
	$('.detail_panel').on('click',function() {
		var _this 			= $(this);
		var nums 			= _this.attr('nums'),
			user_id 		= _this.attr('who'),
			next 			= _this.next();

		if(nums == 0){
			$.dialog_show('e','无记录可以查询');
			next.hide();
			return false;
		}

		if(next.find('dl').length > 0 )
			return false;

		$.ajax({
			url 		: Think.URL + '/getDiary/user_id/' + user_id,
			type 		: 'get',
			dataType 	: 'JSON',
			beforeSend  : function() {
				if(next.find('.get_commen_loading').length == 0)
					next.append('<p class="get_commen_loading"><img src="'+Think.IMG+'/loading.gif" />请求数据中...</p>');
			},
			success 	: function(res) {
				if(res.haserr != undefined) {
					$.dialog_show('e',res.haserr);
					return false;
				}else if(res.hasinfo != undefined) {
					next.find('.get_commen_loading').hide();
					next.append(res.hasinfo);
				}
			}
		});

	});



	//加载更多
	$(document).on('click','.more_add',function() {
		var _this 			= $(this);
		var user_id 		= _this.attr('who'),
			c_p 			= _this.attr('current_page'),
			m_p 			= _this.attr('max_page');
		$.ajax({
			url 		: Think.URL + '/getMore/user_id/' + user_id + '/c_p/' + c_p + '/m_p/' + m_p,
			type 		: 'get',
			dataType 	: 'JSON',
			beforeSend 	: function() {
				_this.find('button').removeClass('more_add').html('请稍后..');
			},
			success 	: function(res) {
				if(res.haserr != undefined) {
					$.dialog_show('e',res.haserr);
					return false;
				}else if(res.hasinfo != undefined) {
					_this.parent().append(res.hasinfo);
				}
				_this.remove();

			}
		});
	})

	//加载文章内容
	$(document).on('click','.diary_detail',function() {
		var _this 			= $(this);
		var dnum 			= _this.attr('dnum'),
			box 			= $('.diary_detail_box');

		$.ajax({
			type 			: 'get',
			url 			: Think.URL + '/getDiaryDetail/id/' + dnum,
			dataType 		: 'json',
			beforeSend 		: function() {
				$.screen_lock();
				$.dialog_show('l','加载中，请稍后....');
			},
			success 		: function(res) {
				$.dialog_hide();
				if(res.haserr != undefined) {
					$.screen_unlock();
					$.dialog_show('e',res.haserr);
					return false;
				}else if(res.hasinfo != undefined) {
					box.show();
					box.find('.diary_detail_title').append(res.hasinfo);
				}
			}
		});
	});

	//关闭文章详情
	$(document).on('click','.diary_detail_close',function() {
		var box 		= $('.diary_detail_box');

		box.find('.diary_detail_title').html('');
		box.hide();
		$.screen_unlock();
	});


	$('.my_goods').on('click',function() {
		var _this 			= $(this);
		var user_info 		= _this.attr('user_info'),
			next 			= _this.next(),
			id 				= _this.attr('id');

		if(next.find('dt').length < 1) {
			$.ajax({
				url 		: Think.MODULE + '/Family/getFamilyGoods/id/' + id + '/user_info/' + user_info,
				type 		: 'get',
				dataType	: 'json',
				beforeSend  : function() {
				if(next.find('.get_commen_loading').length == 0)
					next.append('<p class="get_commen_loading"><img src="'+Think.IMG+'/loading.gif" />请求数据中...</p>');
				},
				success 	: function(res) {
					next.find('.get_commen_loading').hide();
					if(res.haserr != undefined) {
						$.dialog_show('e',res.haserr);
						next.hide();
						return false;
					}else if(res.hasinfo != undefined) {
						next.append(res.hasinfo);
					}
				}
			});
		}


	});

















});