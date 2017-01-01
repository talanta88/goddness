<?php
/**
 * share page
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/3/1
 * Time: 20:01
 */
namespace Admin\Controller;
use Home\Model\ShareModel;

class ShareController extends  CommonController{
    public function index(){
        $list = ShareModel::getAllInfo();
        $this->assign('list',$list);
        $this->display();
    }
    public function addShare(){
        $shareId = I('id');
        $shareContent = I('content');
        $time = Date('Y-m-d H:i:s',time());
        $data = array();
        if($shareContent){
            $data['content'] = self::strFalter($shareContent);
        }
        if(!$shareId){
            //添加新內容
            $data['create_time'] = $time;
            $data['status'] = ShareModel::NORMAL_STATUS;
            ShareModel::addInfo($data);
        }else{
            //編輯內容
            $data['id'] = $shareId;
            $data['modify_time'] = $time;
            ShareModel::saveInfo($data);
        }

        if($shareId){
            $data = ShareModel::getOneInfoById($shareId);
        }
        $this->assign('info',$data);
        $this->display('add');
    }
    public function closeShare(){
        if(IS_AJAX){
            $shareId = I('get.id');
            if(!$shareId){
                $this->ajaxReturn(array('t' => 0,'i' => '关闭分享失敗'));
            }

            $data = array('id'=>$shareId,'status'=>ShareModel::DELETE_STATUS);
            $Ret = ShareModel::saveInfo($data);
            if(!$Ret){
                $this->ajaxReturn(array('t' => 0,'i' => '关闭分享失敗'));
            }
            $this->ajaxReturn(array('t' => 1,'i' => '关闭分享成功'));
        }
    }

    public function openShare(){
        if(IS_AJAX){
            $shareId = I('get.id');
            if(!$shareId){
                $this->ajaxReturn(array('t' => 0,'i' => '启用分享失敗'));
            }

            $data = array('id'=>$shareId,'status'=>ShareModel::NORMAL_STATUS);
            $Ret = ShareModel::saveInfo($data);
            if(!$Ret){
                $this->ajaxReturn(array('t' => 0,'i' => '启用分享失敗'));
            }
            $this->ajaxReturn(array('t' => 1,'i' => '启用分享成功'));
        }
    }
    public static function getShareInfo(){
        return ShareModel::getOneInfo();
    }
}
