/* 倒计时 */
function timer(intDiff){
    window.setInterval(function(){
        var day=0,
            hour=0,
            minute=0,
            second=0;//时间默认值
        if(intDiff > 0){
            day = Math.floor(intDiff / (60 * 60 * 24));
            hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
            minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
            second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
        }
        if (day <= 9) day = '0' + day;
        if (hour <= 9) hour = '0' + hour;
        if (minute <= 9) minute = '0' + minute;
        if (second <= 9) second = '0' + second;
        $('#day_show').html('<s></s>'+day);
        $('#hour_show').html('<s id="h"></s>'+hour);
        $('#minute_show').html('<s></s>'+minute);
        $('#second_show').html('<s></s>'+second);
        intDiff--;
    }, 1000);
}
function create_pic_view(batch,nowKey){
    picView(batch+'_'+nowKey);
}
function picView(key){
   // key = '';
    // 解决 ie6 select框 问题
    var decorateIframe = "decorateIframe"+key;
    $.fn.decorateIframe = function(options) {
        if (/msie/.test(navigator.userAgent.toLowerCase()) && navigator.userAgent.version < 7) {
            var opts = $.extend({}, $.fn.decorateIframe.defaults, options);
            $(this).each(function() {
                var $myThis = $(this);
                //创建一个IFRAME
                var divIframe = $("<iframe />");
                divIframe.attr("id", opts.iframeId);
                divIframe.css("position", "absolute");
                divIframe.css("display", "none");
                divIframe.css("display", "block");
                divIframe.css("z-index", opts.iframeZIndex);
                divIframe.css("border");
                divIframe.css("top", "0");
                divIframe.css("left", "0");
                if (opts.width == 0) {
                    divIframe.css("width", $myThis.width() + parseInt($myThis.css("padding")) * 2 + "px");
                }
                if (opts.height == 0) {
                    divIframe.css("height", $myThis.height() + parseInt($myThis.css("padding")) * 2 + "px");
                }
                divIframe.css("filter", "mask(color=#fff)");
                $myThis.append(divIframe);
            });
        }
    }

    $.fn.decorateIframe.defaults = {
        iframeId: decorateIframe,
        iframeZIndex: -1,
        width: 0,
        height: 0,
    }
    var bigView = "#bigView"+key;
    var midimg = "#midimg"+key;
    var winSelector = "#winSelector"+key;
    var midimg_winSelector = midimg+","+winSelector;
    var bigViewImg = "#bigView"+key+" img";
    var imageMenuLi = "#imageMenu"+key+" li";
    var imageMenuLiFirst = "#imageMenu"+key+" li:first";
    var imageMenuUl = "#imageMenu"+key+" ul";
    var smallImgUp = "#smallImgUp"+key;
    var smallImgDown = "#smallImgDown"+key;
    var curProInfo = '#curProInfo'+key;
    //var imageMenu = "#imageMenu"+ key;

    var midChangeHandler = null;
    var $divWidth = $(winSelector).width(); //选择器宽度
    var $divHeight = $(winSelector).height(); //选择器高度
/*
    var $imgWidth = $(midimg).width(); //中图宽度
    var $imgHeight = $(midimg).height(); //中图高度
*/
    var $viewImgWidth = $viewImgHeight = $height = null; //IE加载后才能得到 大图宽度 大图高度 大图视窗高度
    function changeViewImg() {
        if($(midimg).attr("src")!=undefined){
            //替换为原图
            var imgSrc = $(midimg).attr("src").replace("mid", "big");
            var rowImgSrcArr = imgSrc.split('.');

            var rowImgSrc = '';
                rowImgSrc += rowImgSrcArr[0]+'.'
                           /* +rowImgSrcArr[1]+'.'*/
                            +rowImgSrcArr[1].replace(/thumb_/,'')+'.'
                            +rowImgSrcArr[2];
               /* rowImgSrc = imgSrc.replace(/thumb_/,'big_');*/
           // $(bigViewImg).attr("src", $(midimg).attr("src").replace("mid", "big"));
            $(bigViewImg).attr("src",rowImgSrc);
        }
    }
    function pictureSwitch(){
/*        var count = $(imageMenuLi).length - 5;
         var interval = $(imageMenuLiFirst).width()+50;
         var upCurIndex = 0;
         var downCurIndex = 0;*/
        /* $(imageMenuUl).parent().prev().click(function () {
         var _this = $(this);
         var marginleft = 0;
         if (_this.hasClass('disabled')) return false;
         if (_this.hasClass('smallImgUp')){--upCurIndex; ++downCurIndex; ++count;}
         if (upCurIndex == 0){
         _this.addClass('disabled');
         if( upCurIndex * interval > 40){
         marginleft = upCurIndex * interval - 40;
         }
         }
         if (downCurIndex > 0) $(imageMenuUl).parent().next().removeClass('disabled');
         $(imageMenuUl).stop(false, true).animate({"marginLeft": -marginleft + "px"}, 600);
         });
         $(imageMenuUl).parent().next().click(function () {
         var _this = $(this);
         downCurIndex = count;
         if (_this.hasClass('disabled')) return false;
         if (_this.hasClass('smallImgDown')){++upCurIndex; --downCurIndex; --count;}
         if (downCurIndex <= 5) _this.addClass('disabled');
         if (upCurIndex > 0) $(imageMenuUl).parent().prev().removeClass('disabled');
         $(imageMenuUl).stop(false, true).animate({"marginLeft": -(upCurIndex * interval - 40) + "px"}, 600);
         });*/
        $(smallImgUp).click(function(){
            var p_color = $(curProInfo).attr('p_color');
            var p_bid = $(curProInfo).val();
            var p_page = $(curProInfo).attr('p_index');
            var p_flag = 'up';
            $.ajax({
                url: get_img_url ,
                type: 'post',
                dataType: 'json',
                cache: false,
                data: {p_bid: p_bid, p_color: p_color,p_page:p_page,p_flag:p_flag},
                success: function (res) {
                    if (res.t) {
                        var ulHtml = '';
                        var imageData = res.data.img_list;
                        var curPage = res.data.img_page_cur;
                        var midimgUrl = '';
                        $(imageData).each(function (index, val) {
                            var imgUrl = val;
                            if (index == 0) {
                                midimgUrl = imgUrl;
                                ulHtml += '<li class="onlickImg"><img src="' + imgUrl + '" width="65" height="65" alt="' + name + '"/></li>';
                            } else {
                                ulHtml += '<li><img src="' + imgUrl + '" width="65" height="65" alt="' + name + '"/></li>';
                            }
                        });
                        if (ulHtml != '') {
                            $(imageMenuUl).html(ulHtml);
                        }
                        $(curProInfo).attr('p_index',curPage);
                    }
                }
            })
        })

        $(smallImgDown).click(function(){
            var p_color = $(curProInfo).attr('p_color');
            var p_bid = $(curProInfo).val();
            var p_page = $(curProInfo).attr('p_index');
            var p_flag = 'down';

            $.ajax({
                url: get_img_url ,
                type: 'post',
                dataType: 'json',
                cache: false,
                data: {p_bid: p_bid, p_color: p_color,p_page:p_page,p_flag:p_flag},
                success: function (res) {
                    if (res.t) {
                        var ulHtml = '';
                        var imageData = res.data.img_list;
                        var curPage = res.data.img_page_cur;
                        var midimgUrl = '';
                        $(imageData).each(function (index, val) {
                            var imgUrl = val;
                            if (index == 0) {
                                midimgUrl = imgUrl;
                                ulHtml += '<li class="onlickImg"><img src="' + imgUrl + '" width="65" height="65" alt="' + name + '"/></li>';
                            } else {
                                ulHtml += '<li><img src="' + imgUrl + '" width="65" height="65" alt="' + name + '"/></li>';
                            }
                        });

                        if (ulHtml != '') {
                            $(imageMenuUl).html(ulHtml);
                        }
                        $(curProInfo).attr('p_index',curPage);
                    }
                }
            })
        })
    }
    var preview =".preview";
    function fixedPosition(e) {
        if (e == null) {
            return;
        }
        var $imgWidth = $(midimg).width(); //中图宽度
        var $imgHeight = $(midimg).height(); //中图高度
        var $imgLeft = $(midimg).offset().left; //中图左边距
        var $imgTop = $(midimg).offset().top; //中图上边距
        X = e.pageX - $imgLeft - $divWidth / 2; //selector顶点坐标 X
        Y = e.pageY - $imgTop - $divHeight / 2; //selector顶点坐标 Y
        X = X < 0 ? 0 : X;
        Y = Y < 0 ? 0 : Y;
        X = X + $divWidth > $imgWidth ? $imgWidth - $divWidth : X;
        Y = Y + $divHeight > $imgHeight ? $imgHeight - $divHeight : Y;

        /*if ($viewImgWidth == null) {
           $viewImgWidth = $(bigViewImg).outerWidth();
            $viewImgHeight = $(bigViewImg).height();
            if ($viewImgWidth < 200 || $viewImgHeight < 200) {
                $viewImgWidth = $viewImgHeight = 760;
            }
            $height = Number($divHeight * $viewImgHeight) - Number($imgHeight);
            $(bigView).width(Number($divWidth * $viewImgWidth) - Number($imgWidth));
            $(bigView).height($height)
        }*/
        if ($viewImgWidth == null) {
            $viewImgWidth = $(bigViewImg).outerWidth();
            $viewImgHeight = $(bigViewImg).height();
            if ($viewImgWidth < 500 || $viewImgHeight < 500) {
                $viewImgWidth = $viewImgHeight = 800;
            }
            $height = $divHeight * $viewImgHeight / $imgHeight;
            $(bigView).width($divWidth * $viewImgWidth / $imgWidth);
            $(bigView).height($height);
        }
        var scrollX = X * $viewImgWidth / $imgWidth;
        var scrollY = Y * $viewImgHeight / $imgHeight;
        $(bigViewImg).css({ "left": scrollX * -1, "top": scrollY * -1 });
        var previewLeft = $(preview).offset().left;
        var previewWidth = $(preview).width();
        var bigViewLeft = 0;
        if(previewLeft > 0){
            bigViewLeft = (previewLeft/3) + previewWidth-10
        }else{
            bigViewLeft = previewLeft + previewWidth + 10;
        }
        $(bigView).css({ "top": 0, "left": bigViewLeft });

        return { left: X, top: Y };
    }

    var imageMenuLiImg = "#imageMenu"+key+" li img";
    var onlickImg = "onlickImg"+key;
    //点击到中图
    function imageMenu(){
        $(imageMenuLiImg).live("click", function(){
            if ($(this).attr("id") != onlickImg) {
                midChange($(this).attr("src").replace("small", "mid"));
                $(imageMenuLi).removeAttr("id");
                $(this).parent().attr("id", onlickImg);
            }
        }).live("mouseover", function(){
            if ($(this).attr("id") != onlickImg) {
                window.clearTimeout(midChangeHandler);
                midChange($(this).attr("src").replace("small", "mid"));
                //$(this).css({ "border": "1px solid #959595" });
            }
        });
    }
    function midChange(src) {
        $(midimg).attr("src", src).load(function() {
            changeViewImg();
        });
    }

    var winSelector = "#winSelector"+key;
    var winSelector_bigView = "#winSelector"+key+",#bigView"+key;
    //大视窗看图
    function mouseover(e) {
        if ($(winSelector).css("display") == "none") {
            $(winSelector_bigView).show();
        }
        $(winSelector).css(fixedPosition(e));
        e.stopPropagation();
    }
    function mouseOut(e) {
        if ($(winSelector).css("display") != "none") {
            $(winSelector_bigView).hide();
        }
        e.stopPropagation();
    }

    //放大镜视窗
    $(bigView).decorateIframe();
    $(bigView).scrollLeft(0).scrollTop(0);
    $(midimg).mouseover(mouseover); //中图事件
    $(midimg_winSelector).mousemove(mouseover).mouseout(mouseOut); //选择器事件

    imageMenu();
    changeViewImg();
    /*商品图片切换 start*/
    pictureSwitch();
    /*商品图片切换 end*/
}

function isBrowser(){
/*    if (/msie/.test(navigator.userAgent.toLowerCase()) && navigator.userAgent.version < 9){
        alert('請更新IE版本到10.0以上，或改用Chrome、Firefox瀏覽器!');
        return false;
    }
    return true;*/
    var ua = navigator.userAgent.toLowerCase();
    var s = ua.match(/msie ([\d.]+)/);
    if(/msie/.test(navigator.userAgent.toLowerCase())){//Js判断为IE浏览器
        var ie_version = s[1];
        if(ie_version < '9.0'){//Js判断为IE 9
            alert('請更新IE版本到10.0以上，或改用Chrome、Firefox瀏覽器!');
            return false;
        }
    }
    return true;
}