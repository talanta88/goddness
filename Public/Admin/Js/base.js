$(function() {
	//初始化main_box的高度
	$(document).on('ready',function() {
		changeHeight();
	});

	$(window).on('resize',function() {
		changeHeight();
	})


	function changeHeight() {
		var main_box 		= $('.con_box').height($(window).height() - 50);
	}

	//左侧菜单
	$('a.left_sidebar_a').on('click',function() {
		var son 			= $(this).next();
		if(son.attr('style') == 'display: none;'){
			son.slideDown();
			$(this).addClass('active');
		}else{ 
			son.slideUp();
			$(this).removeClass('active');
		}
	});

	$('.sidebar_list_a').on('click',function() {
		$('.sidebar_list_a').removeClass('active');
		$(this).addClass('active');
	});
})