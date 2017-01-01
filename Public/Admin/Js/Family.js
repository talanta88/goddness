$(function() {

	//成员关系
	$('.mem_relation').on('click',function() {
		var _this 			= $(this);
		var id 				= _this.attr('id'),
			relation_box 	= _this.next().find('tbody');

		if(relation_box.find('tr').length == 0) {
			$.ajax({
				url 		: Think.URL + '/getRelation/id/' + id,
				type 		: 'get',
				dataType 	: 'json',
				beforeSend 	: function() {
					relation_box.append('<tr class="get_commen_loading"><td colspan="6"><img src="'+Think.IMG+'/loading.gif" />请求数据中...</td></tr>');
				},
				success 	: function(res) {
					relation_box.find('.get_commen_loading').remove();
					if(typeof res.haserr != 'undefined')
						$.dialog_show('e',res.haserr);
					else
						relation_box.append(res.info);
				}
			});
		}
	});

	//物品
	$('.goods_detail').on('click',function() {
		var _this 			= $(this);
		var detail_body 	= _this.next(),
			id 				= _this.attr('id');
		if(detail_body.find('div').length == 0) {
			$.ajax({
				url 		: Think.URL + '/getFamilyGoods/id/' + id,
				type 		: 'get',
				dataType 	: 'JSON',
				beforeSend 	: function() {
					if(detail_body.find('.get_commen_loading').length == 0)
						detail_body.append('<p class="get_commen_loading"><img src="'+Think.IMG+'/loading.gif" />请求数据中...</p>');
				},
				success 	: function(res) {
					if(res.haserr != undefined) {
						$.dialog_show('e',res.haserr);
						detail_body.hide();
						return false;
					}else if(res.hasinfo != undefined) {
						detail_body.find('.get_commen_loading').hide();
						detail_body.append(res.hasinfo);
					}					
				}
			});
		}
	});


	//加载更多
	$(document).on('click','.more_add',function() {
		var _this 			= $(this);
		var id 				= _this.attr('id'),
			c_p 			= _this.attr('current_page');
		$.ajax({
			url 		: Think.URL + '/getFamilyGoods/id/' + id + '/c_p/' + c_p,
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

	//gettalk
	$('.gettalk').on('click',function() {
		var _this 		= $(this);
		var info 		= _this.attr('value'),
			next 		= _this.next();
		if(next.find('hr').length < 1) {
			$.ajax({
				url 		: Think.URL + '/getGoodsTalkInfo/info/' + info,
				type 		: 'get',
				dataType 	: 'json',
				beforeSend  : function() {
					if(next.find('.get_commen_loading').length == 0)
						next.append('<p class="get_commen_loading"><img src="'+Think.IMG+'/loading.gif" />请求数据中...</p>');
				},
				success 	: function(res) {
					if(res.haserr != undefined) {
						$.dialog_show('e',res.haserr);
						next.hide();
						return false;
					}else if(res.hasinfo != undefined) {
						next.find('.get_commen_loading').hide();
						next.append(res.hasinfo);
					}	
				}
			});
		}

	});






});