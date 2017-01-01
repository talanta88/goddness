<?php
namespace Home\Controller;

use Admin\Controller\AttentionController;
use Admin\Controller\BannerController;
use Admin\Controller\ShareController;
use Home\Model\CopyrightModel;
use Home\Model\DiscountModel;
use Home\Model\DiscountNoModel;
use Home\Model\OrdersModel;
use Home\Model\PostDiscountModel;
use Home\Model\PostModel;
use Home\Model\ButtomModel;
use Home\Model\SalesPromotionModel;

class IndexController extends BaseController
{
    public function  arr_sort($array,$key,$order="asc"){//asc是升序 desc是降序
        $arr_nums=$arr=array();
        foreach($array as $k=>$v){
            $arr_nums[$k]=$v[$key];
        }
        if($order=='asc'){
            asort($arr_nums);
        }else{
            arsort($arr_nums);
        }
        foreach($arr_nums as $k=>$v){
            $arr[$k]=$array[$k];
        }
        return $arr;
    }
    public function index()
    {
        //讀取logo
        $logo_model = D('Logo');
        $logo_info = $logo_model->find();
        $logo_model = null;
        if (!$logo_info)
            $logo_dir = C('HTTP_URL') . '/Home/Image/logo.png';
        else
            $logo_dir = C('HTTP_URL') . $logo_info['logo'];

        //讀取時間信息
        $time_model = M('Time');
        $time_info = $time_model->find();
        $time_model = null;

        $time = array('y' => '0', 'm' => 0, 'd' => 0, 'h' => 0, 'i' => 0, 's' => 0);
        if ($time_info['start'] <= time()) {
            $time['y'] = date('Y', $time_info['end']);
            $time['m'] = date('m', $time_info['end']);
            $time['d'] = date('d', $time_info['end']);
            $time['h'] = date('H', $time_info['end']);
            $time['i'] = date('i', $time_info['end']);
            $time['s'] = date('s', $time_info['end']);
        }
        $time_info = null;

        //讀取商品信息
        $banner_model = M('banner');
        $banner_info = $banner_model->select();
        $banner_model = null;
        if ($banner_info === null)
            $this->error('請添加商品');

        $info = $this->_doBanner($banner_info);
        $info = array_values($info);

        $discount_model = M('discount');
        $goods_model = M('goods');
        $size_model = M('size');
        $color_model = M('color');
        $colorimg_model = M('colorimg');
        foreach ($info as $k => &$v) {
            //折扣信息
            $discount_info = $discount_model->field('count,discount,missmoney')->where(array('bid' => $v['id']))->order('count asc')->select();
            if ($discount_info) {
                $info[$k]['discount'] = '全系列任選';
                foreach ($discount_info as $kk => $vv) {
                    $vv['discount'] = trim($vv['discount'], '0');
                    if ($kk == count($discount_info) - 1)
                        $info[$k]['discount'] .= '滿' . $vv['count'] . '件以上' . $vv['discount'] . '折';
                    else
                        $info[$k]['discount'] .= '滿' . $vv['count'] . '件' . $vv['discount'] . '折，';
                }
            }
            $discount_info = null;
            $v['son'] = $this->arr_sort($v['son'],'id');
            //商品信息
            if (count($v['son'])) {
                foreach ($v['son'] as $kk => $vv) {
                    $goods_info = $goods_model->where(array('bid' => $vv['id']))->order('self_product_id desc')->find();
                    if ($goods_info) {
                        $tmp_size = explode(',', $goods_info['size']);
                        foreach ($tmp_size as $s_v) {
                            $tmp_size_info = $size_model->field('name')->where(array('id' => $s_v))->find();
                            $info[$k]['son'][$kk]['size'][] = $tmp_size_info['name'];
                        }
                        $info[$k]['son'][$kk]['size'] = $this->sort_size($info[$k]['son'][$kk]['size']);
                        $tmp_size = $tmp_size_info = null;
                        $tmp_color = explode(',', $goods_info['color']);
                        foreach ($tmp_color as $s_v) {
                            $tmp_size_info = $color_model->field('name')->where(array('id' => $s_v))->find();
                            $info[$k]['son'][$kk]['color'][] = $tmp_size_info['name'];
                        }
                        $info[$k]['son'][$kk]['color_str'] = implode('|',$info[$k]['son'][$kk]['color']);
                        $tmp_color = $tmp_size_info = null;
                        $info[$k]['son'][$kk]['goods_id'] = $goods_info['id'];
                        $info[$k]['son'][$kk]['info'] = stripslashes($goods_info['info']);
                        $info[$k]['son'][$kk]['price'] = $goods_info['price'];
                        $info[$k]['son'][$kk]['post_type'] = $goods_info['post_type'];
                        $info[$k]['son'][$kk]['big_img'] = $goods_info['big_img'];
                        $info[$k]['son'][$kk]['self_product_id'] = $goods_info['self_product_id'];
                    }
                    $goods_img = $colorimg_model->field('color_imgs')->where(array('bid' => $vv['id']))->order('id asc')->select();
                    if ($goods_img) {
                        $tmp_img_src = '';
                        foreach ($goods_img as $g_v) {
                            if ($g_v['color_imgs']) {
                                $tmp_img_src .= $g_v['color_imgs'] . ',';
                            }
                        }

                        $goods_img = null;
                        $tmp_img_arr = explode(',', $tmp_img_src);

                        foreach ($tmp_img_arr as $i_v) {
                            if ($i_v)
                                $info[$k]['son'][$kk]['img_src'][] = $i_v;
                        }
                    }
                    $goods_img = $tmp_img_src = $tmp_img_arr = null;
                }
            }
        }
        //读取购物车
        /*        $buycar_model   = M('buycar');
                $user_id        = session_id();
                $list           = $buycar_model->where(array('user' => $user_id))->select();
                $_html           = '';
                foreach($list as $v) {
                    $t_v        = (array)json_decode($v['info']);
                    $_html       .= '<tr><td>'.$t_v['name'].' '.$t_v['size'].' '.$t_v['color'].'</td><td>'.$t_v['num'].'</td><td>'.$t_v['price'].'</td><td><span class="btn btn-md  btn-remove delbuy" val="'.$v['id'].'">移除</td></tr>';
                }*/

        //重新計算價格信息
        /*$price_post_info = $this->autoSum($list);*/
        //var_dump($info);
        $activeTime = TimeController::getTimeInfo();
        $canActiveTime = 0;
        if ($activeTime) {
            $canActiveTime = $activeTime;
        }
        //地區
        $citys = AddressController::getAllAddressConfig();

        //頂部分享
        $topShare = ShareController::getShareInfo();
        //注意事項
        $attention = AttentionController::getAttentionInfo();
        //折扣规则
        $discountRule = DiscountModel::getDiscountAllInfo();
        $ruleData = array();
        if ($discountRule) {
            foreach ($discountRule as $key => $val) {
                $temp['count'] = $val['count'];
                $temp['discount'] = $val['discount'];
                $ruleData[$val['bid']][] = $temp;
                $ruleData[$val['bid']] =  self::arraySortByKey($ruleData[$val['bid']],'count');
            }
        }
        //邮寄費用減免規則
        $postDiscount = PostDiscountModel::getPostDiscountAllInfo();

        //底部標題
        $buttom_title = ButtomModel::getAllButtomTitle();
        foreach ($buttom_title as $key => $val) {
            $title[$val['type']]['title'] = $val['title'];
        }
        //底部內容
        $buttom_content = ButtomModel::getAllButtomInfo();
        $content = array();
        foreach ($buttom_content as $key => $val) {
            $content[$val['type']][] = $val;
        }
        //條幅
        $salesPromotion = SalesPromotionModel::getOneInfo();
        //版權信息
        $copyright = CopyrightModel::getCopyrightAllInfo();
        $attention['content'] = str_replace('\"','',stripslashes($attention['content']));
        $this->assign('copyright', $copyright);
        $this->assign('buttom_title', $title);
        $this->assign('buttom_content', $content);
        $this->assign('info', array(
            'share_info' => $topShare,
            'attention_info' => $attention,
            'logo' => $logo_dir,
            'time' => $time,
            'active_time' => $canActiveTime,
            'goods_list' => $info,
            'citys' => $citys,
            'rule_data' => json_encode($ruleData),
            'post_rule' => json_encode($postDiscount),
            'sales_promotion'=>$salesPromotion
            /* 'buycar'        => $_html,*/
            /*'pp'            => $price_post_info,*/
        ));
        $this->display();
    }

    public function sort_size($size){
        $sort_arr = array('xxs'=>1,'xs'=>2,'s'=>3,'m'=>4,'l'=>5,'xl'=>6,'xxl'=>7);
        $return_arr = array();
        foreach($size as $k=>$v){
            $key = $sort_arr[strtolower($v)];
            if($key){
                $return_arr[$key] = $v;
            }else{
                $other_arr[] = $v;
            }
        }
        ksort($return_arr);
         if(!empty($other_arr)){
            $return_arr = array_merge($return_arr,$other_arr);
        }
        return array_values($return_arr);
    }
    /**
     * 重新拍
     */
    private function _doBanner($info, $pid = 0)
    {
        static $return_arr = array();
        //数组排序，pid为0放在最前面
        $tmp = array();
        $tmp1 = array();
        foreach($info as $key=>$val){
            if($val['pid'] == 0){
                $tmp[] = $val;
            }else{
                $tmp1[] = $val;
            }
        }
        $info = array_merge($tmp,$tmp1);
        foreach ($info as $k => $v) {
            if ($v['pid'] == $pid) {
                $v['son'] = array();
                $return_arr[$v['id']] = $v;
            } else {
                $return_arr[$v['pid']]['son'][] = $v;
            }
        }

        return $return_arr;

    }

    public function setCar()
    {
        if (IS_AJAX) {
            $g_id = I('post.g_id', 0, 'intval');
            $size = I('post.size', '', 'trim');
            $color = I('post.color', '', 'trim');
            $num = I('post.num', 0, 'intval');
            $g_n = I('post.g_n', '', 'trim');
            $price = I('post.price', '', 'trim');
            $goods_id = I('post.goods_id', '', 'trim');
            $pid = I('post.p_id', 0, 'intval');
            $post_type = I('post.post_type', 0, 'intval');
            $price = number_format($price, 2, '.', '');

            if ($g_id <= 0 || empty($size) || empty($color) || $num <= 0)
                $this->ajaxReturn(array('t' => 0, 'i' => '非法操作'));

            //入庫
            $user = session_id();
            $info = array(
                'goods_id' => $goods_id,
                'name' => $g_n,
                'num' => $num,
                'size' => $size,
                'color' => $color,
                'id' => $g_id,
                'price' => $price,
                'post_type' => $post_type
            );

            $buycar_model = M('buycar');
            $insert_res = $buycar_model->add(array('user' => $user, 'info' => json_encode($info), 'pid' => $pid));
            if (!$insert_res)
                $this->ajaxReturn(array('t' => 0, 'i' => '添加購物車失敗'));

            //查詢購物車
            $list = $buycar_model->where(array('user' => $user))->select();
            $html = '';
            foreach ($list as $v) {
                $t_v = (array)json_decode($v['info']);
                $html .= '<tr><td>' . $t_v['name'] . '</td><td>' . $t_v['num'] . '</td><td>' . $t_v['price'] . '</td><td><span class="btn btn-md btn-default btn-remove delbuy" val="' . $v['id'] . '">移除</td></tr>';
            }


            $pp = $this->autoSum($list);

            $this->ajaxReturn(array('t' => 1, 'i' => '添加購物車成功', 'd' => $html, 'pp' => $pp));
        }
    }

    public function delbuycar()
    {
        if (IS_AJAX) {
            $id = I('get.id', 0, 'intval');
            if ($id <= 0)
                $this->ajaxReturn(array('t' => 0, 'i' => '非法操作'));
            $buycar_model = M('buycar');
            $del_res = $buycar_model->where(array('id' => $id))->delete();
            if (!$del_res)
                $this->ajaxReturn(array('t' => 0, 'i' => '移除失敗'));

            $pp = $this->autoSum();

            $this->ajaxReturn(array('t' => 1, 'i' => '移除成功', 'pp' => $pp));
        }
    }


    //重新计算價格信息
    public function autoSum($list = null, $discount = null, $post_money = 0, $ajax_type = 0)
    {
        if (empty($list)) {
            $buycar_model = M('buycar');
            //查詢購物車
            $list = $buycar_model->where(array('user' => session_id()))->select();
        }

        if (empty($list)) {
            $return_arr['xiaoji'] = 0;
            $return_arr['nums'] = 0;
            $return_arr['zhekou'] = 0;
            $return_arr['zongji'] = 0;
            $return_arr['discount'] = 0;
            $return_arr['yunfei'] = 0;
            $return_arr['total'] = 0;
            $return_arr['sendorder_val'] = 0;
            if ($ajax_type) {
                $this->ajaxReturn(array('d' => $return_arr, 't' => 1));
            } else
                return $return_arr;
        }

        $g_html = '';
        $sendorder_val = '';
        foreach ($list as $k => $v) {
            $t_v = (array)json_decode($v['info']);
            $g_html .= '<tr><td>' . $t_v['name'] . '</td><td>' . $t_v['num'] . '</td><td>' . $t_v['price'] . '</td><td><span class="btn btn-md btn-default btn-remove delbuy" val="' . $v['id'] . '">移除</td></tr>';

            $tmp_k = $k + 1;
            if (strlen($tmp_k) == 1) {
                $tmp_k = '0' . $tmp_k;
            }
            $sendorder_val .= $tmp_k . $t_v['name'] . $t_v['size'] . $t_v['num'] . '件' . "\n";
        }

        $ajax_type = I('get.ajax_type', 0, 'intval');
        $post_modey = I('get.post_money', 0, 'trim');
        $discount = I('get.discount', '', 'trim');


        $pid_arr = array();
        $i = 1;
        $post_type_2 = false; //如果是大物品
        foreach ($list as $v) {
            $t_v = (array)json_decode($v['info']);
            $pid_arr[$v['pid']]['xiaoji'] += $t_v['price'];
            $pid_arr[$v['pid']]['nums'] += $t_v['num'];
            $pid_arr[$v['pid']]['post_type'] = $t_v['post_type'];
            if ($t_v['post_type'] == 2)
                $post_type_2 = true;
        }

        $discount_model = M('discount');
        //查询折扣
        foreach ($pid_arr as $k => $v) {
            $disinfo = $discount_model->field('discount,missmoney')->where(array('bid' => $k, 'count' => array('ELT', $v['nums'])))->order('count desc')->find();
            if ($disinfo) {
                $pid_arr[$k]['zongji'] = $v['xiaoji'] * ($disinfo['discount'] / 100);
                $pid_arr[$k]['zhekou'] = $v['xiaoji'] - $pid_arr[$k]['zongji'];
                if ($disinfo['missmoney'] && $disinfo['missmoney'] != '0.00') {
                    if ($v['xiaoji'] >= $disinfo['missmoney'])
                        $pid_arr[$k]['yunfei'] = 0;
                    $pid_arr[$k]['yunfie_diff'] = 0;
                } else {
                    $pid_arr[$k]['yunfei'] = $post_modey;
                    //$pid_arr[$k]['yunfie_diff']     = $disinfo['missmoney'] - $v['xiaoji'];
                }
                $pid_arr[$k]['total'] = $pid_arr[$k]['zongji'] + $pid_arr[$k]['yunfei'];
            } else {
                $pid_arr[$k]['zongji'] = $v['xiaoji'];
                $pid_arr[$k]['zhekou'] = 0;
                $pid_arr[$k]['yunfei'] = $post_modey;
                $pid_arr[$k]['total'] = $v['xiaoji'];
                //$pid_arr[$k]['yunfie_diff']     = $disinfo['missmoney'] - $v['xiaoji'];
            }

        }

        //zong
        $return_arr = array();
        $min_yunfei_diff = array();
        $yunfei_flag = false;
        foreach ($pid_arr as $v) {
            $return_arr['xiaoji'] += $v['xiaoji'];
            $return_arr['nums'] += $v['nums'];
            $return_arr['zhekou'] += $v['zhekou'];
            $return_arr['zongji'] += $v['zongji'];
            $return_arr['discount'] = 0;
            if ($v['yunfei'] == 0)
                $yunfei_flag = true;
            if (isset($v['yunfie_diff']))
                $min_yunfei_diff[] = $v['yunfie_diff'];
            $return_arr['yunfei'] = $v['yunfei'];
            $return_arr['total'] += $v['total'];
        }

        if ($yunfei_flag)
            $return_arr['yunfei'] = 0;

        if (count($min_yunfei_diff) > 0) {
            ksort($min_yunfei_diff);
            $return_arr['yunfei_diff'] = $min_yunfei_diff[0];
        }

        $discountno_model = M('discountno');
        $disinfo = $discountno_model->field('price')->where(array('used_user' => session_id()))->find();
        if ($disinfo) {
            if ($discount)
                $this->ajaxReturn(array('i' => '您已經使用過優惠券了', 't' => 0));

            $return_arr['discount'] = $return_arr['zongji'] * ((100 - $disinfo['price']) / 100);
            $return_arr['total'] = $return_arr['zongji'] - $return_arr['discount'];
        }

        //折上折
        if ($discount) {
            $disinfo = $discountno_model->field('id,start,end,is_used,price')->where(array('no' => $discount))->find();
            if ($disinfo) {
                if ($disinfo['start'] > time())
                    $this->ajaxReturn(array('i' => '此優惠券使用時間未到', 't' => 0));
                else if ($disinfo['end'] < time())
                    $this->ajaxReturn(array('i' => '此優惠券已經過期', 't' => 0));
                else if ($disinfo['is_used'] == 1)
                    $this->ajaxReturn(array('i' => '此優惠券已經使用過', 't' => 0));
                else {
                    $return_arr['discount'] = $return_arr['zongji'] * ((100 - $disinfo['price']) / 100);
                    $return_arr['total'] = $return_arr['zongji'] - $return_arr['discount'];

                    //更新
                    $up_res = $discountno_model->where(array('id' => $disinfo['id']))->save(array('is_used' => 1, 'use_time' => time(), 'used_user' => session_id()));
                    if (!$up_res)
                        $this->ajaxReturn(array('i' => '服務器處理失敗', 't' => 0));
                }
            } else {
                $this->ajaxReturn(array('i' => '無此優惠券信息', 't' => 0));
            }
        }


        $return_arr['total'] = $return_arr['total'] + $return_arr['yunfei'];

        foreach ($return_arr as $k => $v) {
            if ($k == 'nums')
                continue;

            $return_arr[$k] = number_format($v, 2, '.', '');
        }

        if (empty($return_arr['yunfei']) || $return_arr['yunfei'] == '0.00')
            $return_arr['yunfie'] = '0(免運)';
        else {
            if (isset($return_arr['yunfei_diff']))
                $return_arr['yunfei'] = $return_arr['yunfei'] . '(差' . $return_arr['yunfei_diff'] . '免運)';
            else
                $return_arr['yunfei'] = $return_arr['yunfei'];
        }
        if ($post_type_2)
            $_pid = 2;
        else
            $_pid = 1;

        $post_model = M('post');
        $post_list = $post_model->field('name,price')->where(array('pid' => $_pid))->select();

        $post_html = '<option value="">--請選擇--</option>';
        foreach ($post_list as $v) {
            $post_html .= '<option value="' . $v['name'] . '|' . $v['price'] . '">' . $v['name'] . '(' . $v['price'] . ')</option>';
        }
        $return_arr['post'] = $post_html;

        $return_arr['sendorder_val'] = $sendorder_val;

        if ($ajax_type) {
            $return_arr['g_html'] = $g_html;
            $this->ajaxReturn(array('d' => $return_arr, 't' => 1));
        } else
            return $return_arr;
    }


    /**
     * 生成訂單
     */
    public function getOrder()
    {
        if (IS_AJAX) {
            $consignee = I('post.username', '', 'trim');//收件人
            $fbName = I('post.fbname', '', 'trim');//FB姓名
            $telPhone = I('post.tel', '', 'trim');//收件人手機
            $email = I('post.email', '', 'trim');//收件人Email
            $uInfo = I('post.uinfo', '', 'trim');//收件人備註
            $eagree = I('post.eagree', '', 'trim');//電子訂閱
            $postType = I('post.post_type');//郵寄方式
            $houseNum = I('post.house_num', '', 'trim');//服務編號5碼
            $postNum = I('post.post_num', '', 'trim');//郵寄地址編號
            $addressDetail = I('post.post_name', '', 'trim');//详细地址
            $discountNo = I('post.youhuika', '', 'trim');//優惠卡號
            $cartInfo = I('post.cartInfo', '', 'trim');//訂單明細信息
            $xiaoji = I('post.xiaoji', '', 'trim');//商品的總金額
            $zk = I('post.zk', '', 'trim');//满减金額
            $discount = I('post.discount', '', 'trim');//折扣券金額
            $totalMoney = I('post.totalMoney', '', 'trim');//应付款金額
            $user = session_id();
            if (empty($postType))
                $this->ajaxReturn(array('i' => L('lang_post_type'), 't' => 0));

            if (empty($consignee))
                $this->ajaxReturn(array('i' => L('lang_write_addressee'), 't' => 0));

            if (empty($fbName))
                $this->ajaxReturn(array('i' => L('lang_write_fb'), 't' => 0));

            if (empty($telPhone))
                $this->ajaxReturn(array('i' => L('lang_write_phone'), 't' => 0));

            if (empty($email))
                $this->ajaxReturn(array('i' => L('lang_write_email'), 't' => 0));

            if($discountNo){
                $discountRet = DiscountNoModel::getDiscountInfoByNo($discountNo);
                if(!$discountRet)
                    $this->ajaxReturn(array('i' => L('lang_discount_no_error'), 't' => 0));
            }

            $shippingData = explode('|', $postType);
            //訂單基本信息
            $baseInfo = array(
                'userId' => $user,
                'consignee' => $consignee,
                'fb_consignee' => $fbName,
                'city' => $postNum,
                'address' => $addressDetail,
                'house_no' => $houseNum,
                'mobile' => $telPhone,
                'email' => $email,
                'postscript' => $uInfo,
                'shipping_name' => $shippingData[0],//配送名称
                'shipping_fee' => $shippingData[1],
                'egree' => $eagree,
                'discount_no' => $discountNo,
                'goods_amount' => ($xiaoji + $shippingData[1]),
                'discount_money' => $discount,
                'fullcut_money' => $zk,
                'order_amount' => $totalMoney,
            );
            //$addOrderRet = OrderController::addOrdersInfo($baseInfo,$buyCarInfo);
            $orderItem = json_decode(str_replace('\\','',$cartInfo));
            $addOrderRet = OrderController::addOrdersInfo($baseInfo, $orderItem);
            if (!$addOrderRet)
                $this->ajaxReturn(array('i' => L('lang_add_order_error'), 't' => 0));

            //修改優惠券使用次數
            $discountType = $discountRet['type'];
            if($discountType == DiscountNoModel::ONE_TIME_TYPE){
                $discountId = $discountRet['id'];
                $updRet = DiscountNoModel::updDiscountUsedById($discountId);
                if(!$updRet)
                    error_log('set discount no used error data='.json_encode($discountRet));
            }
            session_regenerate_id(true);
            $this->ajaxReturn(array('i' => L('lang_add_order_success'), 't' => 1, 'd' => $addOrderRet));
        }
    }


    /**
     * 成功後跳轉頁面
     */
    public function succ()
    {
        $user = I('get.user', '', 'trim');
        $useraddr_model = M('useraddr');
        $info = $useraddr_model->field('price')->where(array('user' => $user))->find();
        if (!$info)
            $this->error(L('lang_service_exp'));

        $this->assign('price', intval($info['price']));
        $this->display('succ');
    }

    /**
     * 成功後跳轉頁面
     */
    public function OrderInfo()
    {
        $orderNo = I('get.orderNo', '', 'trim');
        if (!$orderNo)
            $this->error(L('lang_service_exp'));

        $condition['order_sn'] = $orderNo;
        $orderInfo = OrdersModel::getOrderInfo($condition);
        if (!$orderInfo)
            $this->error(L('lang_service_exp'));

        $this->assign('price', intval($orderInfo[0]['order_amount']));
        $this->display('succ');
    }

    /**
     * 獲取商品顏色對應的圖片信息
     */
    public function getColorImgs()
    {
        if (IS_AJAX) {
            $p_bid = I('post.p_bid', '', 'trim');
            $p_color = I('post.p_color', '', 'trim');
            $p_color = explode('|',$p_color);

            if (!($p_bid && $p_color))
                $this->ajaxReturn(array('i' => '', 't' => 0));
            $p_page =  I('post.p_page', '', 'trim');
            if(!$p_page){
                $p_page = 1;
            }
            $p_flag =  I('post.p_flag', '', 'trim');
            if($p_flag == 'up'){
               $p_page = $p_page -1>0 ? $p_page-1 : 1;
            }
            if($p_flag == 'down'){
                $p_page = $p_page + 1;
            }

            $ret = ColorImageController::getColorImagesInfo($p_bid, $p_color, $p_page);
            if (!$ret)
                $this->ajaxReturn(array('i' => L('lang_get_image_error'), 't' => 0));

            $this->ajaxReturn(array('i' => L('lang_get_image_success'), 't' => 1, 'data' => $ret));
        }
    }

    public function checkDiscount()
    {
        $discountNo = I('post.discountNo', '', 'trim');
        if (!$discountNo) {
            $this->ajaxReturn(array('i' => L('lang_discount_no_error'), 't' => 0));
        }

        $ret = DiscountNoModel::getDiscountInfoByNo($discountNo);
        if (!$ret) {
            $this->ajaxReturn(array('i' => L('lang_exists_discount_no_error'), 't' => 0));
        }

        $curTime = time();
        $startTime = $ret['start'];
        $endTime = $ret['end'];
        if ($curTime > $endTime || $curTime < $startTime) {
            $this->ajaxReturn(array('i' => L('lang_discount_no_error'), 't' => 0));
        }
        $type = $ret['type'];
        $is_used = $ret['is_used'];
        if($type == DiscountNoModel::ONE_TIME_TYPE && $is_used){
            $this->ajaxReturn(array('i' => L('lang_discount_no_used'), 't' => 0));
        }
        $data['price'] = $ret['price'];
        $this->ajaxReturn(array('i' => L('lang_discount_no_success'), 't' => 1, 'data' => $data));
    }

    public function getPostInfo()
    {
        $post_type = I('post.post_type', '', 'trim');
        if (!$post_type) {
            $condition['pid'] = array('in', array(1, 2));
        } else {
            $condition['pid'] = $post_type;
        }
        $Ret = PostModel::getPostInfo($condition);
        if (!$Ret) {
            $this->ajaxReturn(array('i' => L('lang_get_post_type_error'), 't' => 0));
        }
        $this->ajaxReturn(array('i' => L('lang_get_post_type_success'), 't' => 1, 'data' => $Ret));
    }

    public function tarUrl()
    {
        $id = I('get.id', '', 'trim');
        $condition['id'] = $id;
        $Ret = ButtomModel::getButtomInfoByWhere($condition);
        $this->assign('content', $Ret[0]['content']);
        $this->display();
    }
}