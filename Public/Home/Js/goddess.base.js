/**
 * Created by congquan on 2016/3/16.
 */
function getOrder(getOrderUrl,orderObj,locationUrl) {
    $.ajax({
        url: getOrderUrl,
        type: 'post',
        cache: false,
        data: orderObj,
        dataType: 'json',
        success: function (res) {
            if (res.t) {
                window.location.href =  locationUrl + res.d;
            } else {
                diaLog({content: res.i})
                location.reload(true);
            }
            return false;
        }
    });
    return;
}
function checkQuan(checkDiscountUrl,quanNo) {
    var quanRebate = 0;
     $.ajax({
        url: checkDiscountUrl,
        type: 'post',
        dataType: 'json',
        async:false,
        cache: false,
        data: {discountNo: quanNo},
        success: function (res) {
            if (res.t) {
                quanRebate = res.data.price;
            } else {
                diaLog({'content': res.i})
            }
        }
    });
    return quanRebate;
}
function orderLog(options) {
    var title = options.title;
    var content = options.content;
    if (!title) {
        title = lang.order_confirm;
    }
    $('.order-title').html(title);
    $('.order-content').html(content);
    $('#orderModel').modal('show');
}
function cartLog(options) {
    var title = options.title;
    var content = options.content;
    if (!title) {
        title = lang.cart_info;
    }
    $('.cart-title').html(title);
    $('.cart-content').html(content);
    $('#cartModel').modal('show');
}
function diaLog(options) {
    var title = options.title;
    var content = options.content;
    if (!title) {
        title = lang.info;
    }
    $('.modal-title').html(title);
    $('.modal-body').html(content);
    $('#myModal').modal('show');
}
function keysrt(key, desc) {
    return function (a, b) {
        return desc ? (a[key] < b[key]) : (a[key] > b[key]);
    }
}
function cartMoney(cart,ruleData) {
    //清理运费
    $('.yunfei').html(0);
    $('.yunfei_input').val(0);
    var zkRule = ruleData;//折扣規則
    var xiaoJi = 0;//小計
    var pNums = 0;//數量
    var discountMoney = [];//折扣
    var zj = 0;//總計
    var totalMoney = 0;//總金額
    //折扣計算
    var discountPoint = 0;
    var djProduct = false;//大件商品
    //品類商品數量
    var pclass = {};
    if (cart) {
        for (var index in cart) {
            var cartObj = cart[index];
            var num = Number(cartObj.num);
            var price = num * Number(cartObj.sale_price);
            var pid = Number(cartObj.pid);
            var post_type = Number(cartObj.post_type);
            //小計
            xiaoJi = Math.ceil((xiaoJi + price));
            //商品總數
            pNums = pNums + num;
            //按照品類歸類商品
            if (pclass["" + pid + ""]) {
                pclass["" + pid + ""]['num'] += num;
                pclass["" + pid + ""]['price'] += price;
            } else {
                var tmp = [];
                tmp['num'] = num;
                tmp['price'] = price;
                pclass["" + pid + ""] = tmp;
           }
            //是否大件商品
            if (post_type == 2) {
                djProduct = true;
            }
        }
        $(zkRule).each(function (index, val) {
            for (var key in pclass) {
                //存在該商品的減免規則
                var zkMoney = 0;
                var prule = val[key];
                var curNums = Number(pclass[key]['num']);
                var price = pclass[key]['price'];
                if (prule) {
                    for (var n in prule) {
                        var next_key = Number(n)+1;
                        var count = Number(prule[n].count);
                        var discount = Number(prule[n].discount);
                        if (n == (prule.length - 1)) {
                            discountPoint = discount;
                            break;
                        }
                        if (count > curNums) {
                            discountPoint = 0;
                            break;
                        }
                        var next_count = Number(prule[next_key].count);
                        if(curNums >= count &&  curNums < next_count){
                            discountPoint = discount;
                            break;
                        }else{
                            continue;
                        }

                    }
                    discountPoint =  Number(discountPoint);
                    if (discountPoint > 0) {
                        zkMoney = price *  (1-(discountPoint/100));
                        discountMoney.push(Number(zkMoney));
                    }
                }
            }
        });

        //折扣金額
        var discountMoneyTotal = 0;
        for(var i in discountMoney){
            discountMoneyTotal += discountMoney[i];
        }
        //總計計算
        //zj = Math.ceil(xiaoJi * (discountPoint/100));
        //折扣金額
        //if(discountPoint){
        if(discountMoneyTotal){
            discountMoneyTotal = discountMoneyTotal.toFixed(2);
            discountMoneyTotal = Math.round(discountMoneyTotal);
            zj = xiaoJi - discountMoneyTotal;
        }else{
            zj = xiaoJi;
        }
        //總金額計算
        totalMoney = zj;
    }
    $('.xiaoji').html(xiaoJi);
    $('.nums').html(pNums);
    if(discountMoneyTotal>0){
        $('.zhekou').html('-'+discountMoneyTotal);
    }else{
        $('.zhekou').html(discountMoneyTotal);
    }
    $('.zongji').html(zj);
    $('.total').html(zj);
    $('.discount').html(0)
    $('input[name=totalMoney]').val(zj);
}
function yfMoney(postRule) {
    var yfRule = postRule;//運費規則
    var yfVal = $('select[name=post-type]').val();//運費
    var zjMoney = Number($('.zongji').html());//总计
    var totalMoney = zjMoney - Number($('.discount').html().replace(/-/,''));//总计
    if (!yfVal) {
        return false;
    }
    var yfMoneyArr = yfVal.split('|');
    var yfMoney = Number(yfMoneyArr[1]);
    var jyf = 0;
    var cjyf = 0;
    var yjjyf = 0;
    //郵費計算
    $(yfRule).each(function (key, val) {
        var rule_money = Number(val.rule_money);
        var discount_money = Number(val.discount_money);
        if (zjMoney < rule_money) {
            jyf = 0;
            cjyf = rule_money - zjMoney;
            yjjyf = discount_money;
            return true;
        }
        if (key === yfRule.length - 1) {
            jyf = discount_money;
            return true;
        }

        if (zjMoney >= rule_money && zjMoney < yfRule[key + 1].rule_money) {
            jyf = discount_money;
            return true;
        }

    })

    if (jyf >= yfMoney) {
        yfMoney = 0;
    } else {
        yfMoney = yfMoney - jyf;
    }
    var yfMoneyStr = '0';
    if (yfMoney == 0) {
        yfMoneyStr = lang.post_free;
    } else {
        if (cjyf) {
            yjjyf = yjjyf > yfMoney ? yfMoney : yjjyf;
            yfMoneyStr = yfMoney + lang.post_difference + cjyf + lang.free + yjjyf + lang.freight;
        } else {
            yfMoneyStr = yfMoney + lang.already_relief + jyf + lang.freight;
        }
    }
    $('.yunfei').html(yfMoneyStr);
    $('.yunfei_input').val(yfMoney);
    $('.total').html(yfMoney + totalMoney);
    $('input[name=totalMoney]').val(yfMoney + totalMoney);
}
function getPostInfo(post_url,post_type) {
    $.ajax({
        url: post_url,
        type: 'post',
        dataType: 'json',
        cache: false,
        data: {post_type: post_type},
        success: function (res) {
            var postTypeStr = '<option value="">'+lang.please_select+'</option>';
            if (res.t) {
                $(res.data).each(function (index, val) {
                    postTypeStr += '<option value="' + val.name + '|' + val.price + '">' + val.name + ' ' + val.price + '</option>'
                })
            }
            $('select[name=post-type]').html(postTypeStr);
            $('.yunfei').html(0);
        }
    })
}
function orderDetailHtml(cartData){
    var HtmlStr = '<table class="table table-bordered table-hover">' +
        '<tr><td colspan="5" class="text-left">'+lang.again_confirm_order+'</td></tr>'+
        '<tr><td>'+lang.first_img+'</td><td>'+lang.product_name+'</td><td>'+lang.quantity+'</td></tr>';
    for(var index in cartData){
        HtmlStr += '<tr>' +
            '<td><img src=" '+cartData[index]['first_img']+'" height="65px" width="65px"/></td>' +
            '<td>'+cartData[index]['gname'] +
            '<td>'+cartData[index]['num']+'</td>' +
            '</tr>';
    }
    HtmlStr += '<tr><td colspan="5">'+lang.sender_info+'</td></tr></table>';
    return HtmlStr;
};
function getColor(p_bid, p_color, imageMenu, getImgUrl,curProInfo) {
    $.ajax({
        url: getImgUrl ,
        type: 'post',
        dataType: 'json',
        cache: false,
        data: {p_bid: p_bid, p_color: p_color},
        success: function (res) {
            if (res.t) {
                var ulHtml = '';
                var imageData = res.data.img_list;
                var curPage = res.data.img_page_cur;
                var midimgUrl = '';
                var firstImgObj = '#firstImg'+imageMenu.replace(/#imageMenu/,'');
                var firstImg = '';
                $(imageData).each(function (index, val) {
                    var imgUrl = val;
                    if (index == 0) {
                        midimgUrl = imgUrl;
                        firstImg = imgUrl;
                        ulHtml += '<li class="onlickImg"><img src="' + imgUrl + '" width="65" height="65" alt="' + name + '"/></li>';
                        //设置默认图
                        //var pre_parent = $(imageMenu).parent().parent();
                        var imgObj = '#midimg'+imageMenu.replace(/#imageMenu/,'');
                        var bigImgObj = '#bigView'+imageMenu.replace(/#imageMenu/,'');
                        $(imgObj).attr('src',imgUrl);
                        var rowImgSrcArr = imgUrl.split('.');
                        var rowImgSrc = '';
                            rowImgSrc += rowImgSrcArr[0]+'.'
                            +rowImgSrcArr[1]+'.'
                            +rowImgSrcArr[2].replace(/thumb_/,'')+'.'
                            +rowImgSrcArr[3];
                        $(bigImgObj).find('img').attr('src',rowImgSrc);
                    } else {
                        ulHtml += '<li><img src="' + imgUrl + '" width="65" height="65" alt="' + name + '"/></li>';
                    }
                });
                if (ulHtml != '') {
                    $(imageMenu).children('ul').html(ulHtml);
                }
                $(firstImgObj).val(firstImg);
                $(curProInfo).attr('p_index',curPage);
            }
        }
    })
}