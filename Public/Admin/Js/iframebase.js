$(function() {
	//刷新按钮
	$('.reload').on('click',function() {
		self.location.reload(true);
	});

	//提示框
	$('.tooltip_class').tooltip();

	//初始化dialog高度
	$(document).on('ready',function() {
		var dialog 		= $('.dialog');
		var height 		= ($(window).height() - dialog.height()) / 2;
		var width 		= ($(window).width() - dialog.width()) / 2;
		dialog.css({'top':height,'left':width});
	});

	//重置
	$('.reset_form').on('click',function() {
		$('input[type="text"]').val('');
		$('input[type="radio"]').removeAttr('checked');
	});

	//panel-heading绑定事件
	$('.panel-heading').on('click',function() {
		var next 			= $(this).next();
		if(next.attr('style') == 'display: none;')
			next.show();
		else
			next.slideUp();
	});


});
jQuery.extend({
	dialog_show : function(type,str) {
		var dialog 		= $('.dialog');
		dialog.hide();
		if(type == 's') {
			dialog.addClass('dialog_succ').html(str).show();
			$('input[type="submit"]').attr({disabled:'disabled'});
			setTimeout(function() {
				$('input[type="submit"]').removeAttr('disabled');
				dialog.hide().removeClass('dialog_succ').html('');
			},1000);
		}else if(type == 'e'){
			dialog.addClass('dialog_error').html(str).show();
			$('input[type="submit"]').attr({disabled:'disabled'});
			setTimeout(function() {
				$('input[type="submit"]').removeAttr('disabled');
				dialog.hide().removeClass('dialog_error').html('');
			},1000);
		}else if(type == 'l') {
			dialog.html(str).show();
		}
	},
	dialog_hide : function() {
		$('.dialog').html('').hide();
	},
	screen_lock : function() {

		$('.screen_shade').height($(document).height()).show();
		// $('.dialog').show();
	},
	screen_unlock:function() {
		$('.screen_shade').hide();
	},
	img_upload 	: function(__this,_name) {
		if(typeof __this.files == 'undefined'){
            return;
        }

        var _fun 			= {
        	'getObjectURL' 		: 	function(file) {
        		var url = null ; 
		        if (window.createObjectURL!=undefined) { // basic
		            url = window.createObjectURL(file) ;
		        } else if (window.URL!=undefined) { // mozilla(firefox)
		            url = window.URL.createObjectURL(file) ;
		        } else if (window.webkitURL!=undefined) { // webkit or chrome
		            url = window.webkitURL.createObjectURL(file) ;
		        }
		        return url ;
        	}
        };

        var _this           = $(__this);
        var img             = __this.files[0];//获取图片信息
        var type            = img.type;//获取图片类型，判断使用
        if(type.substr(0,5) != 'image'){//无效的类型过滤
            $.dialog_show('e','非图片类型，无法上传！');
            return false;
        }
        var url             = _fun.getObjectURL(__this.files[0]);//使用自定义函数，获取图片本地url
        var fd              = new FormData();//实例化表单，提交数据使用
        fd.append('param',img);//将img追加进去
        if(url)
            _this.parent().find('.pic_show').attr('src',url).show();//展示图片
        //展示图片，进度条
        _this.next().show();
        $.ajax({
            type        : 'post',
            url         : Think.MODULE + '/File/upload',
            data        : fd,
            processData : false,  // tell jQuery not to process the data  ，这个是必须的，否则会报错
            contentType : false,   // tell jQuery not to set contentType  
            dataType    : 'json',
            xhr         : function() {//这个是重点，获取到原始的xhr对象，进而绑定upload.onprogress
                var xhr     = jQuery.ajaxSettings.xhr();
                xhr.upload.onprogress     = function(ev) {
                    //这边开始计算百分比
                    var parcent = 0;
                    if(ev.lengthComputable) {
                        percent = 100 * ev.loaded / ev.total;
                        percent = parseFloat(percent).toFixed(2);
                        _this.parent().find('.progress-bar').attr({'style':'width: '+ percent + '%','aria-valuenow':percent});
                    }
                };
                return xhr;
            },
            beforeSend 	: function() {
            	$('input[type="submit"]').attr({'disabled' : 'disabled'});
            },
            success     : function(res) {
                console.log(typeof res.img_dir);
                if(typeof res.img_dir != 'undefined') {
                    _this.after('<input type="hidden" name="'+_name+'" value="'+res.img_dir+'" />');
                }else {
                    $.dialog_show('e',res);
                }
                $('input[type="submit"]').removeAttr('disabled');
            }
        });
	}
});