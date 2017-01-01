$(function() {
	$('input[name="logo_img"]').on('change',function() {
		$.img_upload(this,'logo');
	});
	$('input[name="address"]').on('keyup',function(e) {
		//1-9 a-z
		var arr 		= [32,8,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,48,49,50,51,52,53,54,55,56,57];
		if($.inArray(e.keyCode,arr) != '-1') {
			var value 	= $(this).val();
			var ol 		= $('.add_message').find('ol');
			if(value.length <= 1){
				ol.html('');
				ol.hide();
				$('.add_message').hide();
				return false;
			}
			$('.add_message').hide();
			$('.add_message').find('ol').html('');

			if(value.search(/[\']/) != -1){
				console.log(value);
				return;
			}

			$.ajax({
				url 		: Think.URL + '/addressToNums/add/' + value,
				type 		: 'get',
				dataType 	: 'json',
				beforeSend 	: function() {
					$('.add_message').hide();
					ol.hide();
					$('.add_message').find('ol').html('');
					$('.add_loading').show();
				},
				success 	: function(res) {
					if(res.status == 0) {
						if(res.result.length <= 0) {
							$('.add_message').hide();
							ol.hide();
							$('.add_message').find('ol').html('');
							$('.add_loading').hide();
							return false;
						}
						var html 		= '';
						$.each(res.result,function(i,n) {
							if(typeof n.location != 'undefined') {
								html 	+= '<li class="add_li" lat="'+n.location.lat+'" lng="'+n.location.lng+'">'+ n.city+'-'+n.district+'-'+n.name+'</li>';
							}
						});
						$('.add_message').show();
						ol.append(html).show();
					}else 
						$.dialog_show('e',res.message);
					$('.add_loading').hide();
				}
			});
		}
	});

	$(document).on('click','.add_li',function() {
		var _this 			= $(this);
		$('input[name="address"]').val(_this.html());
		$('input[name="longitude"]').val(_this.attr('lng'));
		$('input[name="latitude"]').val(_this.attr('lat'));
		$('.add_message').find('ol').hide();
		$('.add_message').hide();
	});

	$(document).on('click',function(eve) {
		if(eve.target.nodeName != 'li') {
			$('.add_message').find('ol').hide();
			$('.add_message').hide();
		}
	});

	$('.upimg_multi').find('input[type="file"]').on('change',function() {
		var _this  			= $(this);
		var img             = this.files[0];
		var _type            = img.type;
	    if(_type.substr(0,5) != 'image'){
	        $.dialog_show('e','非图片类型，无法上传！');
	        return false;
	    }
	    var fd              = new FormData();
	    fd.append('param',img);
	    $.ajax({
	        type        : 'post',
	        url         : Think.MODULE + '/File/upload',
	        data        : fd,
	        processData : false, 
	        contentType : false,  
	        dataType    : 'json',
	        beforeSend 	: function() {
	        	$('input[type="submit"]').attr({'disabled' : 'disabled'}).val('等待图片上传完毕...');
	        	_this.prev().html('...');
	        },
	        success     : function(res) {
	            if(typeof res.file_id != undefined) {
	            	_this.prev().html('+');
	                _this.parent().find('.img-thumbnail').attr({'src' : res.thumb_img_dir}).fadeIn();
	                _this.parent().next().show();
	                _this.parent().attr({'file_id' : res.file_id});
	            }else {
	                $.dialog_show('e',res);
	            }
	            $('input[type="submit"]').removeAttr('disabled').val('提交');
	        }
	    });
	});

	$('.img-thumbnail').on('mouseover',function() {
		$(this).parent().find('.del_multi').show();
	});

	$('.del_multi').on('click',function() {
		$(this).parent().attr({'file_id' : ''});
		$(this).parent().find('img').attr({'src' : ''}).hide();
		$(this).hide();
	});

	$('form[name="mend"]').on('submit',function() {
		var name 			= $('#name'),
			address 		= $('#address'),
			tel 			= $('#tel'),
			ment_scope 		= $('#ment_scope'),
			province_id 	= $('select[name="province_id"]'),
			city_id 		= $('select[name="city_id"]'),
			introduction 	= $('#introduction');

		if(name.val().length <= 0 || name.val().length > 64) {
			$.dialog_show('e','商家名称1-64位之间');
			name.focus();
			return false;
		}

		if(address.val().length <= 0 || address.val().length > 64) {
			$.dialog_show('e','商家地址1-64位之间');
			address.focus();
			return false;
		}

		if(tel.val().length <= 0 || tel.val().length > 64) {
			$.dialog_show('e','商家电话1-64位之间');
			tel.focus();
			return false;
		}

		if(province_id.val() == 0) {
			$.dialog_show('e','省份必选');
			province_id.focus();
			return false;
		}

		if(city_id.val() == 0) {
			$.dialog_show('e','市区必选');
			city_id.focus();
			return false;
		}

		if(ment_scope.val().length <= 0 || ment_scope.val().length > 64) {
			$.dialog_show('e','商家经验范围1-64位之间');
			ment_scope.focus();
			return false;
		}

		if(introduction.val().length > 256) {
			$.dialog_show('e','简介最大256个字符');
			introduction.focus();
			return false;
		}

		var img_ids 		= '';
		$.each($('.upimg_multi'),function(i,n) {
			var file_id 	= $(n).attr('file_id');
			if(typeof file_id != 'undefined')
				img_ids 	+= file_id + ',';
		});
		$('input[name="img_ids"]').val(img_ids);


	});


	$('.del_mend').on('click',function() {
		var _this 	= $(this);
		var id 		= _this.attr('id');
		$.ajax({
			url 		: Think.URL + '/del/id/' + id,
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
					$.dialog_show('s','处理成功');
					// $.screen_unlock();
				}		
			}
		});
	});

	$('.get_commen').on('click',function() {
		var _this		= $(this);
		var id 			= _this.attr('id'),
			next 		= _this.next();
		if(next.find('blockquote').length == 0){
			$.ajax({
				url 		: Think.URL + '/getCommen/id/' + id,
				type 		: 'get',
				dataType 	: 'json',
				beforeSend 	: function() {
					if(next.find('.get_commen_loading').length == 0)
						next.append('<p class="get_commen_loading"><img src="'+Think.IMG+'/loading.gif" />请求数据中...</p>');
				},
				success 	: function(res) {
					if(typeof res != 'object'){
						$.dialog_show('e',res);
						next.hide();
					}else {
						next.append(res.html);
						next.find('.get_commen_loading').hide();
					}
				}
			});
		}
	});

	$(document).on('click','.com_more',function() {
		var _this 			= $(this);
		var page_num 		= _this.attr('page_num'),
			id 				= _this.attr('id');
			page_num++;
		$.ajax({
			url 			: Think.URL + '/getCommen/id/' + id + '/page_num/' + page_num,
			type 			: 'get',
			dataType 		: 'JSON',
			beforeSend		: function() {
				_this.html('请稍后...');
			},
			success 		: function(res) {
				if(typeof res != 'object')
					$.dialog_show('e',res);
				else {
					_this.before(res.html);
					_this.remove();
				}
			}
		});
	});

	$('select[name="province_id"]').on('change',function() {
		var id 				= $(this).find(':selected').val();
		var city 			= $('select[name="city_id"]');
		$.ajax({
			url 			: Think.URL + '/getSArea/id/' + id,
			type 			: 'get',
			dataType 		: 'json',
			beforeSend 		: function() {
				city.html('');
			},
			success 		: function(res) {
				var html 	= '';
				$.each(res,function(i,n) {
					html 	+= '<option value="'+n.id+'">'+n.name+'</option>';
				});
				city.html(html);
			}
		});
	});

});










