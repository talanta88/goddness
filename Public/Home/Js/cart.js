$(function(){
    var cart = [];
    var addCart = $('.add_buy_car');
    var HtmlCart = $('#buyCar');
    var delCart = $('.delbuy');

    //添加到購物車
    addCart.click(function(){
        if(isBrowser()===false){
            return;
        }
        var _this           = $(this);
        var _parent         = _this.parent().parent().parent();
        var firstImg        = _this.prev().val();
        var size_val        = _parent.find('select[name="size"]').val();
        var color_val       = _parent.find('select[name="color"]').val();
        var num_val         = _parent.find('select[name="num"]').val();
        var gn_val          = _parent.find('.p-name').html();
        var pro_val         = _parent.find('.pro-name').val();
        var sale_price      = _this.attr('price');
        var goods_id        = _this.attr('goods_id');
        var post_type       = _this.attr('post_type');
        var pid             = _this.attr('pid');
        var product_id             = _this.attr('product_id');
        if(size_val.length <= 0){
            diaLog({content:lang.check_size})
            return false;
        }

        if(color_val.length <= 0){
            diaLog({content:lang.check_color})
            return false;
        }

        if(num_val.length <= 0){
            diaLog({content:lang.check_number})
            return false;
        }

        //加入購物車
        var cartData = [];
        cartData['goods_id'] = goods_id;
        cartData['first_img'] = firstImg;
        cartData['gname'] = gn_val;
        cartData['pro_name'] = pro_val;
        cartData['num'] = num_val;
        cartData['sale_price'] = sale_price;
        cartData['price'] = 0;
        cartData['size'] = size_val;
        cartData['color'] = color_val;
        cartData['post_type'] = post_type;
        cartData['pid'] = pid;//品類
        cartData['product_id'] = product_id;//商品编号

        if(cart.length == 0){
            cart.push(cartData);
        }else{
            for(var index in cart){
                if( Number(cart[index]['goods_id']) ==  Number(goods_id)
                    && cart[index]['size'] == size_val
                    && cart[index]['color'] == color_val){
                    cart[index]['num'] = Number(cart[index]['num']) + Number(num_val);
                    num_val = 0;
                }
                if(Number(num_val) &&　index == cart.length-1){
                    cart.push(cartData);
                }
            }
        }
        cart = listSortBy(cart, 'product_id', 'asc');
        cartObj = cart;
        cartMoney(cart,rule_data)
        addCart.Html(cart);
        cartLog({content:lang.add_cart_success})
    })
    //從購物車移除
    delCart.live('click',function(){
        var thisObj = $(this);
        var curId = thisObj.attr('val');
        cart.splice(curId,1);
        addCart.Html(cart);
        if(cart.length == 0){
            var nullCart = '<tr><td colspan="4">'+lang.no_commodity+'</td></tr>';
            HtmlCart.html(nullCart);
            getPostInfo(post_url);
        }
        cart = listSortBy(cart, 'product_id', 'asc');
        cartObj = cart;
        $('.discount').html('0')
        cartMoney(cart,rule_data)
        diaLog({content:lang.remove_success})
    })
    //购物车模板
    addCart.Html = function(cartData){
        var HtmlStr = '';
        var djProduct = false;
        var product_id_str ='';
        for(var index in cartData){
            if(cartData[index]['post_type'] == 2){
                djProduct = true;
            }
            if(cartData[index]['product_id']!=''){
                product_id_str = cartData[index]['product_id'] +'.';
            }
            HtmlStr += '<tr><td><img src="'+cartData[index]['first_img']+'" width="65px" height="65px"></td>' +
                            '<td>'+cartData[index]['gname']+'</td>' +
                            '<td>'+cartData[index]['num']+'</td>' +
                            '<td>'+cartData[index]['sale_price']*cartData[index]['num']+'</td>' +
                            '<td><span class="btn btn-md  btn-remove delbuy"  val='+index+'>'+lang.remove_active+
                        '</td></tr>';
        }
        if(djProduct){
            getPostInfo(post_url,2);
        }else{
            getPostInfo(post_url,1);
        }
        HtmlCart.html(HtmlStr);
    }
    //选择邮购方式
    $('select[name=post-type]').change(function(){
        var _this       = $(this),
            t           = _this.val();
        //清理邮购信息
        $('input[name=house_num]').val('');
        $('input[name=post-name]').val('');
        $('select[name=post-num]').find("option:selected").attr('selected',false)
        if(t.substring(0,6) != lang.home_post ) {
            $('.fwm').hide();
            $('.yj').show();
        }else{
            $('.fwm').show();
            $('.yj').hide();
        }
        if(cart.length){
            yfMoney(post_rule);
        }
    });
    //选取尺寸
    $("select[name=size]").change(function(){
        var selectSize = $(this).val();
        var pname = $(this).next().val();
        var name = $(this).next().attr('data');
        var product_id = $(this).next().attr('product_id');
        var pnameStr = '';
        var product_id_str = '';
        var _curObj = $(this).parent().parent();
        var color_str =  _curObj.next().find('select[name=color]').html();
        var num_str = _curObj.next().next().find('select[name=num]').html();
        if(selectSize!=''){
            pnameStr = name +' '+ selectSize;
        }else{
            pnameStr = name;
        }

        if(product_id!=''){
            product_id_str = product_id+'.';
        }
        $("."+pname).html(product_id_str+pnameStr);
        _curObj.next().find('select[name=color]').next().attr('data',product_id_str+pnameStr);
        _curObj.next().find('select[name=color]').html(color_str);
        _curObj.next().next().find("select[name=num]").html(num_str);
    })
    //选取颜色
    $("select[name=color]").change(function(){
        var selectColor = $(this).val();
        var inputObj = $(this).next();
        var pname = inputObj.val();
        var name = inputObj.attr('data');
        var pkey = inputObj.attr('data-key');
        var imageMenu = "#imageMenu"+ pkey;
        var midimg = "#midimg"+ pkey;
        var curProInfo = '#curProInfo'+pkey;
        var pnameStr = '';

        if(selectColor!=''){
            pnameStr = name +' '+ selectColor;
        }else{
            pnameStr = name;
        }
        $("."+pname).html(pnameStr);
        //ajax請求顏色對應的圖片
        var p_bid = inputObj.attr('data-bid');
        var p_color = selectColor;
        $(curProInfo).attr('p_color',selectColor);
        getColor(p_bid,p_color,imageMenu,get_img_url, curProInfo);
    });
    function listSortBy(arr, field, order){
        var refer = [], result=[], order = order=='asc'?'asc':'desc', index;
            for(i=0; i<arr.length; i++){
            refer[i] = arr[i][field]+':'+i;
        }
        refer.sort(sortNumber);
        if(order=='desc') refer.reverse();
        for(i=0;i<refer.length;i++){
            index = refer[i].split(':')[1];
            result[i] = arr[index];
        }
        return result;
    }
    function sortNumber(a,b)
    {
        return a.split(':')[0] - b.split(':')[0];
    }
})