$(function() {

// pie_png({
// 	_this 	: $('.user_stat'),
// });

var user_stat 		= $('.user_stat');

$.ajax({
	url 		: Think.URL + '/getUserLogin',
	type 		: 'get',
	dataType 	: 'json',
	beforeSend 	: function() {
		user_stat.append('<p class="get_commen_loading"><img src="'+Think.IMG+'/loading.gif" />请求数据中...</p>');
	},
	success 	: function(res) {
		if(typeof res.data_info == undefined) {
			$('.get_commen_loading').html(res.explain);
		}else {
			var temp_arr 		= [];
			$.each(res.data_info.data,function(i,n) {
				switch(i) {
					case 0 : 
						temp_arr.push(['10天内登陆',n]);
						break;
					case 1 : 
						temp_arr.push(['1个月内登陆',n]);
						break;
					case 2 : 
						temp_arr.push(['半年内登陆',n]);
						break;
					case 3 : 
						temp_arr.push(['一年内登陆',n]);
						break;
					case 4 : 
						temp_arr.push(['一年以上登陆',n]);
						break;
				}
			});
			pie_png({
				_this 	: user_stat,
				title 	: res.explain,
				subtitle: res.sub_exp,
				name 	: res.data_info.name,
				data 	: temp_arr
			});
		}
	}
});


function pie_png(info) {
	info._this.highcharts({
	        chart: {
	            plotBackgroundColor: null,
	            plotBorderWidth: 1,
	            plotShadow: false
	        },
	        title: {
	            text: info.title
	        },
	        subtitle : {
	        	text: info.subtitle,
	        },
	        tooltip: {
	    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
	        },
	        plotOptions: {
	            pie: {
	                allowPointSelect: true,
	                cursor: 'pointer',
	                dataLabels: {
	                    enabled: true,
	                    color: '#000000',
	                    connectorColor: '#000000',
	                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
	                }
	            }
	        },
	        series: [{
	            type: 'pie',
	            name: info.name,
	            data: info.data
	        }]
	});
}



});

// jQuery.extend({
// 	pie_png : 
// 	},
// });




