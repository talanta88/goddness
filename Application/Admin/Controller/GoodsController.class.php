<?php
/**
 * Filename: GoodsController.class.php
 * Comment:
 * User: lxd
 * Time: 16/1/25 16:02
 */
namespace Admin\Controller;

use Home\Model\OrdersModel;
use Think\Page;
class GoodsController extends CommonController
{
    private static $charConfig = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    private $_model = null;

    public function __construct()
    {
        parent::__construct();
        $this->_model = M('goods');
    }
    public static function getGoodsInfoById($goods_id){
        if(!$goods_id){
            return false;
        }
        $ret =  M('goods')->where(array('id'=>$goods_id))->find();
        return $ret;
    }
    /**
     * 獲取列表
     */
    public function getList()
    {
        $count = $this->_model->count();
        $this->_pageIndex($count);
        $page_limit = $this->_page($count);
        $list = $this->_model->order('add_time desc')->page($page_limit)->select();
        if ($list === false)
            $this->error('服務器異常');

        $this->assign('list', $list);
        $this->display('list');
    }

    /**
     * 新增
     */
    public function add()
    {
        $banner_model = M('banner');

        if (IS_POST) {
            $pid = I('post.parent_id', 0, 'intval');
            if ($pid <= 0)
                $this->error('請選擇第一層品項');

            $name = I('post.name', '', 'trim');
            if (empty($name))
                $this->error('請填寫名稱');

            $info = I('post.info', '', 'trim');
            if (empty($info))
                $this->error('請填寫簡介');

            $size = $_POST['size'];
            if (count($size) <= 0)
                $this->error('請選擇尺寸');

            $big_img = $_POST['big_img'];
            if (empty($big_img))
                $this->error('請上傳商品主圖');

            $color = $_POST['color'];
            if (count($color) <= 0)
                $this->error('請選擇顏色');

            $price = $_POST['price'];
            if (empty($price) || $price == '0.00')
                $this->error('價格不能為空');

            $sale_price = $_POST['sale_price'];
            if (empty($sale_price) || $sale_price == '0.00')
                $this->error('產品售價不能為空');

            $material = $_POST['material'];
            if (empty($material))
                $this->error('產品材質不能為空');

            $producing_area = $_POST['producing_area'];
            if (empty($producing_area))
                $this->error('產品產地不能為空');

            $post_type = I('post.post_type');
            if ($post_type <= 0)
                $this->error('請選擇運送方式');


            $color_arr = array();
            $color_img = array();

            foreach ($color as $k => $v) {
                if (is_numeric($k)) {
                    $color_arr[] = $v;
                    $color_img[$v] = array();
                    unset($color[$k]);
                } else {
                    $tmp_key = str_replace('_', '', $k);
                    foreach ($v as $vv) {
                        if (!empty($vv)) {
                            $color_img[$tmp_key][] = $vv;
                        }
                    }
                }
            }

            if (count($color_img) <= 0)
                $this->error('請至少上傳一張圖片');

            foreach ($color_img as $k => $v) {
                if (!in_array($k, $color_arr))
                    unset($color_img[$k]);
            }


            //入庫
            $b_insert_res = $banner_model->add(array(
                'pid' => $pid,
                'name' => $name,
                'add_time' => time(),
            ));
            if (!$b_insert_res)
                $this->error('服務器新增品項異常');

            $colorimg_model = M('colorimg');
            foreach ($color_img as $k => $v) {
                $c_insert_res = $colorimg_model->add(array(
                    'bid' => $b_insert_res,
                    'color_int' => $k,
                    'color_imgs' => implode(',', $v)
                ));
                if (!$c_insert_res)
                    $this->error('服務器新增圖片異常');
            }
            $self_product_id = I('post.self_product_id');
            $insert_res = $this->_model->add(array(
                'name' => $name,
                'bid' => $b_insert_res,
                'size' => implode(',', $size),
                'info' => self::strFalter($info),
                'color' => implode(',', $color_arr),
                'sale_price' => $sale_price,
                'price' => $price,
                'material' => $material,
                'producing_area' => $producing_area,
                'add_time' => time(),
                'post_type' => $post_type,
                'big_img' => $big_img,
                'self_product_id'=>$self_product_id
            ));
            if (!$insert_res)
                $this->error('服務器新增商品異常');

            $this->redirect('getList');
        }
        //讀取信息
        //一級品項
        $banner_list = $banner_model->where(array('pid' => 0))->select();

        $color_model = M('color');
        $color_list = $color_model->select();

        $size_model = M('size');
        $size_list = $size_model->select();

        $post_tmodel = M('posttype');
        $post_tlist = $post_tmodel->select();

        $this->assign('need', array('b' => $banner_list, 'c' => $color_list, 's' => $size_list, 'p' => $post_tlist));
        $this->display();
    }

    /**
     * 編輯
     */
    public function mof()
    {
        $banner_model = M('banner');
        $colorimg_model = M('colorimg');

        if (IS_POST) {
            $id = I('post.id', 0, 'intval');
            if ($id <= 0)
                $this->error('服務器異常');

            $bid = I('post.bid', 0, 'intval');
            if ($bid <= 0)
                $this->error('服務器異常');

            $pid = I('post.parent_id', 0, 'intval');
            if ($pid <= 0)
                $this->error('請選擇第一層品項');

            $name = I('post.name', '', 'trim');
            if (empty($name))
                $this->error('請填寫名稱');

            $info = I('post.info', '', 'trim');
            if (empty($info))
                $this->error('請填寫簡介');

            $size = $_POST['size'];
            if (count($size) <= 0)
                $this->error('請選擇尺寸');

            $big_img = $_POST['big_img'];
            if (empty($big_img))
                $this->error('請上傳商品主圖');

            $color = $_POST['color'];
            if (count($color) <= 0)
                $this->error('請選擇顏色');

            $price = $_POST['price'];
            if (empty($price) || $price == '0.00')
                $this->error('價格不能為空');

            $sale_price = $_POST['sale_price'];
            if (empty($sale_price) || $sale_price == '0.00')
                $this->error('產品售價不能為空');

            $material = $_POST['material'];
            if (empty($material))
                $this->error('產品材質不能為空');

            $producing_area = $_POST['producing_area'];
            if (empty($producing_area))
                $this->error('產品產地不能為空');

            $post_type = I('post.post_type');
            if ($post_type <= 0)
                $this->error('請選擇運送方式');

            $add_time = I('post.add_time');
            $color_arr = array();
            $color_img = array();

            foreach ($color as $k => $v) {
                if (is_numeric($k)) {
                    $color_arr[] = $v;
                    $color_img[$v] = array();
                    unset($color[$k]);
                } else {
                    $tmp_key = str_replace('_', '', $k);
                    foreach ($v as $vv) {
                        if(!empty($vv)) {
                        $color_img[$tmp_key][] = $vv;
                        }
                    }
                }
            }

            if (count($color_img) <= 0)
                $this->error('請至少上傳一張圖片');

            foreach ($color_img as $k => $v) {
                if (!in_array($k, $color_arr))
                    unset($color_img[$k]);
            }


            //清理之前的數據，重新存儲
            $del_res = $banner_model->where(array('id' => $bid))->delete();
            if (!$del_res)
                $this->error('服務器異常' . $banner_model->getDbError());

            $del_res = $colorimg_model->where(array('bid' => $bid))->delete();
            if (!$del_res)
                $this->error('服務器異常' . $colorimg_model->getDbError());

            /*$del_res = $this->_model->where(array('id' => $id))->delete();
            if (!$del_res)
                $this->error('服務器異常' . $this->_model->getDbError());*/


            //入庫
            $b_insert_res = $banner_model->add(array(
                'id' => $bid,
                'pid' => $pid,
                'name' => $name,
                'add_time' => time(),
            ));
            if (!$b_insert_res)
                $this->error('服務器新增品項異常');

            $colorimg_model = M('colorimg');

            foreach ($color_img as $k => $v) {
                $c_insert_res = $colorimg_model->add(array(
                    'bid' => $bid,
                    'color_int' => $k,
                    'color_imgs' => implode(',', $v)
                ));
                if (!$c_insert_res)
                    $this->error('服務器新增圖片異常');
            }
            $self_product_id = I('post.self_product_id');
            $update_res = $this->_model->save(array(
                'id'=>$id,
                'name' => $name,
                'bid' => $bid,
                'size' => implode(',', $size),
                'info' => self::strFalter($info),
                'color' => implode(',', $color_arr),
                'sale_price' => $sale_price,
                'price' => $price,
                'material' => $material,
                'producing_area' => $producing_area,
                'add_time' => $add_time,
                'post_type' => $post_type,
                'big_img' => $big_img,
                'self_product_id'=>$self_product_id
            ));
            if (!$update_res)
                $this->error('服務器编辑商品異常');

            $this->redirect('getList');

        }

        $id = I('get.id', 0, 'intval');
        if ($id <= 0)
            $this->error('服務器異常');

        $info = $this->_model->where(array('id' => $id))->find();
        if (!$info)
            $this->error('服務器查找信息失敗');

        //找到pid
        $banner_info = $banner_model->where(array('id' => $info['bid']))->find();
        if (!$banner_info)
            $this->error('服務器查找一級品項失敗');

        //找到圖片
        $colorimg_info = $colorimg_model->where(array('bid' => $info['bid']))->select();
        //if(!$colorimg_info)
        //    $this->error('服務器查找圖片失敗');

        $color_arr = $color_img = array();
        foreach ($colorimg_info as $v) {
            $color_arr[] = $v['color_int'];
            $tmp_color_imgs = explode(',', $v['color_imgs']);
            foreach ($tmp_color_imgs as $kk => $vv) {
                if (!empty($vv))
                    $color_img[$v['color_int']][$kk] = array('val' => $vv, 'url' => $vv);
            }
        }

        //讀取信息
        //一級品項
        $banner_list = $banner_model->where(array('pid' => 0))->select();

        $color_model = M('color');
        $color_list = $color_model->select();

        $size_model = M('size');
        $size_list = $size_model->select();

        $post_tmodel = M('posttype');
        $post_tlist = $post_tmodel->select();

        $this->assign('need', array('b' => $banner_list, 'c' => $color_list, 's' => $size_list, 'p' => $post_tlist));
        $info['pid'] = $banner_info['pid'];
        $info['color'] = $color_arr;
        $info['color_img'] = $color_img;
        $info['size'] = explode(',', $info['size']);
        $this->assign('info', $info);
        $this->display('add');
    }


    /**
     * 刪除
     */
    public function del()
    {
        if (IS_AJAX) {
            $id = I('get.id', 0, 'intval');
            if ($id <= 0)
                $this->ajaxReturn(array('status' => 0, 'data' => '非法傳值'), 'json');

            $bid = I('get.bid', 0, 'intval');
            if ($bid <= 0)
                $this->ajaxReturn(array('status' => 0, 'data' => '非法傳值'), 'json');

            $banner_model = M('banner');
            $del_res = $banner_model->where(array('id' => $bid))->delete();
            if (!$del_res)
                $this->error('服務器異常' . $banner_model->getDbError());

            $colorimg_model = M('colorimg');
            $del_res = $colorimg_model->where(array('bid' => $bid))->delete();
            if (!$del_res)
                $this->error('服務器異常' . $colorimg_model->getDbError());

            $del_res = $this->_model->where(array('id' => $id))->delete();
            if (!$del_res)
                $this->error('服務器異常' . $this->_model->getDbError());


            $this->ajaxReturn(array('status' => 1, 'data' => '刪除成功'), 'json');

        }
    }
    //訂單商品數據
    public function  getGoodsOrderInfo(){
        $startTime = I('start');
        $endTime = I('end');
        $orderStatus = I('order_status');
        $shippingStatus = I('shipping_status');
        $payStatus = I('pay_status');
        $page = I('p');
        $condition = array();
        if($startTime){
            $condition['order_time'] = array('egt',$startTime);
        }
        if($endTime){
            $condition['order_time'] = array('elt',$endTime);
        }

        if($orderStatus!=''){
            $condition['order_status'] = $orderStatus;
        }
        if($shippingStatus!=''){
            $condition['shipping_status'] = $shippingStatus;
        }
        if($payStatus !=''){
            $condition['pay_status'] = $payStatus;
        }
        $limit = 20;
        if(!$page){
            $offset = 0;
        }else{
            $offset = ($page -1)*$limit;
        }
        $listRet = OrdersModel::getGoodsOrderList($condition, $offset);
        if($listRet){
            $show = $this->getShowPage($listRet['count'],$limit);
            $this->assign('page',$show);
        }
        $shippingStatusArr = OrdersModel::$shippingStatusConfig;
        $orderStatusArr = OrdersModel::$orderStatusConfig;
        $payStatusArr = OrdersModel::$payStatusConfig;
        $this->assign('shipping_status_arr',$shippingStatusArr);
        $this->assign('shipping_status',$shippingStatus);
        $this->assign('order_status_arr',$orderStatusArr);
        $this->assign('order_status',$orderStatus);
        $this->assign('pay_status_arr',$payStatusArr);
        $this->assign('pay_status',$payStatus);
        $this->assign('start_time',$startTime);
        $this->assign('end_time',$endTime);
        $this->assign('offset',$offset);
        $this->assign('goods_order_list',$listRet['list']);
        $this->display('goods_order');
    }

    public function downGoodsOrderInfo(){
        $list = $this->getAllGoodsOrderList();
        // Create new PHPExcel object
        Vendor('PHPExcel', '', '.php');
        // Create new PHPExcel object
        $objPHPExcel = new  \PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("ctos")
            ->setLastModifiedBy("ctos")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        // set width
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

        // 设置行高度
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);

        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

        // 字体和样式
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        // 设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //  合并
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');

        // 表头
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '訂單商品匯總表')
            ->setCellValue('A2', '品名')
            ->setCellValue('B2', '顏色')
            ->setCellValue('C2', '尺寸')
            ->setCellValue('D2', '材質')
            ->setCellValue('E2', '產地')
            ->setCellValue('F2', '售價')
            ->setCellValue('G2', '數量');

        // 内容
        foreach($list as $key=>$val){
            $goods_names = explode('(',$val['goods_name']);
            if(!$goods_names){
                $goods_names = explode('（',$val['goods_name']);
            }
            $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($key + 3), $goods_names[0]);
            $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($key + 3), $val['goods_color']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($key + 3), $val['goods_size']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($key + 3), $val['material']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($key + 3), $val['producing_area']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($key + 3), $val['sale_price']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('G' . ($key + 3), $val['num']);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($key + 3) . ':G' . ($key + 3))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($key + 3) . ':G' . ($key + 3))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getRowDimension($key + 3)->setRowHeight(16);
        }

        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('訂單商品匯總表');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // 输出
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="訂單商品匯總表(' . date('Ymd-His') . ').xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    public function getAllGoodsOrderList(){
        $startTime = I('start');
        $endTime = I('end');
        $orderStatus = I('order_status');
        $shippingStatus = I('shipping_status');
        $payStatus = I('pay_status');
        $condition = array();
        if($startTime){
            $condition['order_time'] = array('egt',$startTime);
        }
        if($endTime){
            $condition['order_time'] = array('elt',$endTime);
        }

        if($orderStatus!=''){
            $condition['order_status'] = $orderStatus;
        }
        if($shippingStatus!=''){
            $condition['shipping_status'] = $shippingStatus;
        }
        if($payStatus !=''){
            $condition['pay_status'] = $payStatus;
        }
       return $listRet = OrdersModel::getAllGoodsOrderList($condition);
    }
    public function getShowPage($count,$limit){
        $Page = new Page($count, $limit);
        $Page->lastSuffix = false;
        $Page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;&nbsp;每页<b>%LIST_ROW%</b>条&nbsp;&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $Page->setConfig('last', '末页');
        $Page->setConfig('first', '首页');
        $Page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        $show = $Page->show();// 分页显示输出
        return $show;
    }
}