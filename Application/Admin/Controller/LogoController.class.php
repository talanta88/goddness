<?php
    /**
     * Filename: LogoController.class.php
     * Comment: logo 控制器
     * User: lxd
     * Time: 16/1/25 13:14
     */
    namespace Admin\Controller;

    class LogoController extends CommonController {

        /**
         * 展示目前logo图片
         */
        public function index() {
            $logo_model             = M('logo');
            $logo_info              = $logo_model->field('logo')->find();
            $logo_model             = null;
            if($logo_info === false)
                $this->error('服務器查詢異常');
            else if($logo_info === null){
                $mes_str            = '沒有logo信息，請上傳';
                $btn_str            = '提交';
            }else {
                $mes_str            = '';
                $btn_str            = '更新';
            }


            $this->assign('str',array('mes_str' => $mes_str,'btn_str' => $btn_str));
            $this->assign('logo',C('HTTP_URL') . $logo_info['logo']);
            $this->display();
        }


        /**
         * 更新logo图片信息
         */
        public function add() {
            $logo           = I('post.logo');
            if(empty($logo))
                $this->error('非法操作!');

            $logo_model             = M('logo');
            //清除历史
            $del_res                = $logo_model->where('1')->delete();
            if($del_res === false)
                $this->error('服務器清理失敗!');

            $insert_res             = $logo_model->add(array('logo' => $logo));
            $logo_model             = null;
            if(!$insert_res)
                $this->error('服務器新增異常');

            $this->redirect('index');
        }

    }
