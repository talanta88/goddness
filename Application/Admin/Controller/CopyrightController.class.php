<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/3/5
 * Time: 22:38
 */
namespace Admin\Controller;
use Home\Model\CopyrightModel;

class CopyrightController extends  CommonController{
    public function index(){
        $id = I('post.id','','trim');
        $content = I('post.content','','trim');
        $conditon['content'] = self::strFalter($content);
        if(!$id){
            CopyrightModel::addCopyrightInfo($conditon);
        }else{
             $conditon['id'] = $id;
            CopyrightModel::updCopyrightInfo($conditon);
        }
        $Ret = CopyrightModel::getCopyrightAllInfo();
        $this->assign('copyRight',$Ret);
        $this->display();
    }

}