$(function() {
	$('.del').on('click',function() {
		var _this 			= $(this);
		var id 				= _this.attr('id'),
			type 			= _this.attr('type'),
			html 			= _this.html();
		if(html == '删除')
			var value 		= 1;
		else 
			var value 		= 0;

		$.ajax({
			url 			: Think.URL + '/delCom/id/' + id + '/type/' + type + '/value/' + value,
			type 			: 'get',
			dataType 		: 'json',
			success 		: function(res) {
				if(typeof res.haserr != 'undefined'){
					$.dialog_show('e',res.haserr);
					return false;
				}else {
					$.dialog_show('s','操作成功');
					_this.parent().parent().remove();
				}

			}
		});
	});
});