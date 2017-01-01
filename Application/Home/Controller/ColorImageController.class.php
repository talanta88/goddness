<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/28
 * Time: 11:30
 */
namespace Home\Controller;
use Home\Model\ColorImageModel;
use Home\Model\ColorModel;

class ColorImageController extends BaseController{
    const PAGE_SIZE = 5;
    public static function getColorImagesInfo($p_bid,$p_color,$p_page){
        if(!($p_bid && $p_color)){
            return false;
        }
        if(!$p_page){
            $p_page = 1;
        }
        //获取颜色id

        $colorIdRet = ColorModel::getColorIdByName($p_color);

        if(!$colorIdRet){
            return false;
        }
        foreach($colorIdRet as $key=>$val){
            $colorIds[] = $val['id'];
        }
        $condition = array(
            'bid'=>$p_bid,
            'color_int'=>array('in',$colorIds),
        );
        $colorImageRet = ColorImageModel::getColorImagesInfo($condition);
        $imageData = array();
        foreach($colorImageRet as $key=>$val){
            $imageArr = explode(',',$val['color_imgs']);
        /*    foreach($imageArr as $k=>$v){
                $images[]  = C('HTTP_URL').$v;
            }*/
            $imageData = array_merge($imageData,$imageArr);
        }
        //分页
        $page_count = ceil(count($imageData)/self::PAGE_SIZE);
        if($p_page > $page_count){
            $p_page = $page_count;
        }
        $imageData =  array_slice($imageData, ($p_page-1) * self::PAGE_SIZE,self::PAGE_SIZE);

        $return['img_list'] = $imageData;
        $return['img_page_cur'] = $p_page;
        $return['img_page_count'] = $page_count;
        return $return;
    }
}