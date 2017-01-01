<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/28
 * Time: 14:43
 */
namespace Admin\Controller;
use Home\Controller\AddressController;
use Home\Model\OrderItemModel;
use Home\Model\OrdersModel;
use Home\Model\BannerModel;
use Home\Model\ColorModel;
use Think\Page;

class OrdersController extends CommonController
{
    private static $charConfig = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

    public static function getOrderInfoList($condition, $limit = 20)
    {
        $count = OrdersModel::getOrderCount($condition);
        $Page = new Page($count, $limit);
        $Page->lastSuffix = false;
        $Page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;&nbsp;每页<b>%LIST_ROW%</b>条&nbsp;&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $Page->setConfig('last', '末页');
        $Page->setConfig('first', '首页');
        $Page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        $show = $Page->show();// 分页显示输出
        $listRet = OrdersModel::getOrderListPage($condition, $Page->firstRow, $Page->listRows);
        if (!$listRet) {
            return false;
        }
        $returnRet = array('page' => $show, 'listRet' => $listRet);
        return $returnRet;
    }

    public static function updOrderStatus($order_id, $order_code, $status)
    {
        if (!($order_id && $order_code && $status)) {
            return false;
        }
        $condition = array(
            'order_id' => $order_id,
            'order_code' => $order_code,
        );

        $data = array(
            'order_status' => $status,
        );
        $updRet = OrdersModel::updateOrderStatus($condition, $data);
        if ($updRet) {
            return $updRet;
        }
        return false;
    }

    public static function updShippingStatus($order_id, $order_code, $status)
    {
        if (!($order_id && $order_code && $status)) {
            return false;
        }
        $condition = array(
            'order_id' => $order_id,
            'order_code' => $order_code,
        );

        $data = array(
            'shipping_status' => $status,
        );
        $updRet = OrdersModel::updateOrderStatus($condition, $data);
        if ($updRet) {
            return $updRet;
        }
        return false;
    }

    public static function getOrderItemInfoByOrderId($order_id)
    {
        if (!$order_id) {
            return false;
        }
        $condition = array(
            'order_id' => $order_id,
        );
        $orderItemRet = OrderItemModel::getOrderItemInfo($condition);
        //獲取品类名
        foreach ($orderItemRet as $key => $value) {
            $bannerInfo = BannerModel::getBannerInfoById($value['banner_id']);
            $orderItemRet[$key]['branner_name'] = $bannerInfo['name'];
            $statusText = OrderItemModel::getOrderItemStatusConfig($value['status']);
            $orderItemRet[$key]['status_text'] = $statusText;
            $goodsInfo  = GoodsController::getGoodsInfoById($value['goods_id']);
            $orderItemRet[$key]['self_product_id'] = $goodsInfo['self_product_id'];
        }
        return $orderItemRet;
    }

    public static function downOrderInfo($condition)
    {
        $orderRet = OrdersModel::getOrderInfo($condition);
        $itemInfo = array();
        $orderIds = array();
        foreach ($orderRet as $key => $value) {
            $condition = array(
                'order_id' => $value['order_id'],
            );
            $orderIds[] = $value['order_id'];
            $orderItemRet = OrderItemModel::getOrderItemInfo($condition);
            $itemNumber = array();
            foreach ($orderItemRet as $k => $val) {
                $self_product_info = GoodsController::getGoodsInfoById($val['goods_id']);
                $itemStr = $self_product_info['self_product_id'] . $val['goods_name'] . $val['goods_size'] . $val['goods_color'];
                if (!array_key_exists($itemStr, $itemInfo)) {
                    $itemInfo[$itemStr] = array(
                        'goods_id' => $val['goods_id'],
                        'self_product_id' => $self_product_info['self_product_id'],
                        'goods_name' => $val['goods_name'],
                        'goods_size' => $val['goods_size'],
                        'goods_color' => $val['goods_color']
                    );
                }
                $itemNumber[$itemStr] = intval($val['goods_number']);
            }
            $orderRet[$key]['item_info'] = $itemNumber;
        }

        //获取订单中的商品信息
        $searchCondition = array(
            'goi.order_id' => $orderIds,
        );
        $orderItemInfo = OrderItemModel::getOrderItems($searchCondition);
        $size = OrderItemModel::$sizeConfig;
        $colorRet = ColorModel::getColorInfo();

        foreach($colorRet as $key=>$val){
            $color[$val['name']] = $key;
        }
        //同一中商品
        foreach ($orderItemInfo as $key => $val) {
            $val['item_str'] = $val['self_product_id'] .$val['goods_name']  . $val['goods_size'] . $val['goods_color'];
            $tmp[$val['goods_id']][] = $val;
        }
        //按照自定义序号排序
        foreach ($tmp as $key => $val) {
            foreach ($val as $k => $v) {
                $tmp1[$v['self_product_id']][] = $v;
            }
        }
        //按照尺码排序
        foreach ($tmp1 as $key => $val) {
            foreach ($val as $k1 => $v1) {
                $size_key = $size[$v1['goods_size']];
                $tmp2[$key][$size_key][] = $v1;
            }
        }

        //按照颜色排序
        foreach ($tmp2 as $key => $val) {
            foreach ($val as $k1 => $v1) {
                foreach ($v1 as $k2 => $v2) {
                    $color_key = $color[$v2['goods_color']];
                    $tmp3[$key][$k1][$k2][$color_key][] = $v2;
                    $tmp4[] = $v2;
                }
            }
        }
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
        $charArr = self::$charConfig;
        $colCount = count($tmp4) + 12;
        //set width
        for ($i = 0; $i <= $colCount; $i++) {
            $pCoordinate = \PHPExcel_Cell::stringFromColumnIndex($i);
            $objPHPExcel->getActiveSheet()->getColumnDimension($pCoordinate)->setWidth(20);
        }

        //设置行高度 
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
        $lastChar = \PHPExcel_Cell::stringFromColumnIndex($colCount);;

        //set font size bold
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A2:' . $lastChar . '2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A2:' . $lastChar . '2')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:' . $lastChar . '2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        //设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        for ($i = 0; $i <= $colCount; $i++) {
            $pCoordinate = \PHPExcel_Cell::stringFromColumnIndex($i);
            $objPHPExcel->getActiveSheet()->getStyle($pCoordinate)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        }
        //合并cell
        $objPHPExcel->getActiveSheet()->mergeCells('A1:' . $lastChar . '1');
        // set table header content
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '訂單匯總表  時間:' . date('Y-m-d H:i:s'))
            ->setCellValue('A2', '訂單時間')
            ->setCellValue('B2', '訂單編號');

        foreach($tmp4 as $key => $val){
            $newData[] = $val['item_str'];
        }
        //$itemInfo = array_keys($itemInfo);
      //
        $lastKey = 0;
        $charFlog = array();//存放每个商品Excel中位置
        foreach ($newData as $key => $val) {
            $lastKey = $key + 2;
            $pCoordinate = \PHPExcel_Cell::stringFromColumnIndex($lastKey);
            $objPHPExcel->setActiveSheetIndex()->setCellValue($pCoordinate . '2', $val);
            $charFlog[$val] = $pCoordinate;
        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 1). '2', '郵寄方式')
            ->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 2). '2', '服務編號5碼')
            ->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 3). '2', '地址區號/詳細地址')
            ->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 4). '2', '件數')
            ->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 5). '2', '總金額')
            ->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 6). '2', '收件人')
            ->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 7). '2', 'FB姓名')
            ->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 8). '2', '電話')
            ->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 9). '2', '電子郵件')
            ->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 10). '2', '我同意訂閱電子報通知')
            ->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 11). '2', '備註');

        // Miscellaneous glyphs, UTF-8
        foreach ($orderRet as $exce_key => $exce_val) {
            $curKey = $exce_key + 3;
            $totalNumber = 0;
            if($exce_val['egree']){
                $str = '同意訂閱電子報通知';
            }else{
                $str = '';
            }
            $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($curKey), $exce_val['order_time']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($curKey), $exce_val['order_sn']);
            foreach ($charFlog as $e_k => $e_v) {
                $callValue = 0;
                if ($exce_val['item_info'][$e_k]) {
                    $callValue = intval($exce_val['item_info'][$e_k]);
                }
                $totalNumber += $callValue;
                $objPHPExcel->getActiveSheet(0)->setCellValue($e_v . ($curKey), $callValue);
            }
            $objPHPExcel->getActiveSheet(0)->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 1) . ($curKey), $exce_val['shipping_name']);
            $objPHPExcel->getActiveSheet(0)->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 2) . ($curKey), ' ' . $exce_val['house_no'] );
            $objPHPExcel->getActiveSheet(0)->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 3) . ($curKey), ' ' . $exce_val['city'].AddressController::getAddressById($exce_val['city']) . $exce_val['address']);
            $objPHPExcel->getActiveSheet(0)->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 4) . ($curKey), $totalNumber);
            $objPHPExcel->getActiveSheet(0)->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 5) . ($curKey), ' ' . $exce_val['order_amount']);
            $objPHPExcel->getActiveSheet(0)->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 6) . ($curKey), ' ' . $exce_val['consignee']);
            $objPHPExcel->getActiveSheet(0)->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 7) . ($curKey), ' ' . $exce_val['fb_consignee']);
            $objPHPExcel->getActiveSheet(0)->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 8) . ($curKey), ' '.$exce_val['mobile']);
            $objPHPExcel->getActiveSheet(0)->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 9) . ($curKey), ' ' . $exce_val['email']);
            $objPHPExcel->getActiveSheet(0)->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 10) . ($curKey), $str);
            $objPHPExcel->getActiveSheet(0)->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($lastKey+ 11) . ($curKey), $exce_val['postscript']);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($curKey) . ':' . \PHPExcel_Cell::stringFromColumnIndex($lastKey+ 11) . ($curKey))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($curKey) . ':' . \PHPExcel_Cell::stringFromColumnIndex($lastKey+ 11) . ($curKey))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getRowDimension($curKey)->setRowHeight(16);
        }
        /*        */
        //  sheet命名
        $objPHPExcel->getActiveSheet()->setTitle('訂單匯總表');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // excel頭參數
        header('Pragma:public');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="訂單匯總表(' . date('Ymd-His') . ').xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }


}