$(function() {
	$('input[name="apk"]').on('change',function() {
		if(confirm('上传文件后，版本号不可修改，'+"\n"+'确认继续么？') === false){
			$(this).val('');
			return false;
		}

		var _this 		= $(this)[0],
			_this_info 	= _this.files[0],
			progress 	= $('.progress'),
			name 		= $('#name');

		if(name.val().length < 1) {
			$.dialog_show('e','版本号必需最先填写');
			$(this).val('');
			return false;			
		}

		name.attr({'readonly' : 'readonly'});

		if(_this_info == 'undefined'){
			$.dialog_show('e','浏览器版本过低，请使用ie10以上或者谷歌，火狐等浏览器');
			$(this).val('');
			return false;
		}

		if(_this_info['name'].substr(-3) != 'apk'){
			$.dialog_show('e','文件名称必须以apk结尾');
			$(this).val('');
			return false;
		}

		var fd              = new FormData();//实例化表单，提交数据使用
    	fd.append('param',_this_info);//将img追加进去
    	fd.append('name',name.val());
    	progress.show();

    	$.ajax({
            type        : 'post',
            url         : Think.MODULE + '/File/apkUpload',
            data        : fd,
            processData : false,  // tell jQuery not to process the data  ，这个是必须的，否则会报错
            contentType : false,   // tell jQuery not to set contentType  
            dataType    : 'json',
            xhr         : function() {
                var xhr     = jQuery.ajaxSettings.xhr();
                xhr.upload.onprogress     = function(ev) {
                    //这边开始计算百分比
                    var parcent = 0;
                    if(ev.lengthComputable) {
                        percent = 100 * ev.loaded / ev.total;
                        percent = parseFloat(percent).toFixed(2);
                        progress.find('.progress-bar').attr({'style':'width: '+ percent + '%','aria-valuenow':percent});
                    }
                };
                return xhr;
            },
            beforeSend 	: function() {
            	$('input[type="submit"]').attr({'disabled' : 'disabled'});
            },
            success     : function(res) {
            	console.log(res.file_id);
                if(typeof res.file_id != 'undefined') {
                    $('input[name="url"]').val(res.url);
                }else {
                    $.dialog_show('e',res);
                    $(this).val('');
                    $('input[type="submit"]').val('文件上传失败：' + res);
                }
                $('input[type="submit"]').removeAttr('disabled');
            }
        });
	});

	$('form[name="version"]').on('submit',function() {

		var name 			= $('#name'),
			url 			= $('input[name="url"]'),
			info 			= $('#info'),
			force_need 		= $('#force_need'),
			apk 			= $('input[name="apk"]');

		if(name.val().length < 1 || name.val().length > 20) {
			$.dialog_show('e','版本号不能为空或者大于20位');
			name.focus();
			return false;
		}

		if(url.val().length < 1) {
			$.dialog_show('e','请上传版本apk');
			apk.focus();
			return false;
		}

		if(force_need.val().length > 1) {
			if(force_need.val().length > 20) {
				$.dialog_show('e','请填写合法版本号');
				force_need.focus();
				return false;
			}
		}

		return true;
	});

	$('.del').on('click',function() {
		return confirm('确认删除该版本么？');
	});
	
})