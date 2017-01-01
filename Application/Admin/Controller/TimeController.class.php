<?php
    /**
     * Filename: TimeController.class.php
     * Comment:
     * User: lxd
     * Time: 16/1/25 14:51
     */
    namespace Admin\Controller;

    class TimeController extends CommonController {

        /**
         * 展示
         */
        public function index() {
            $model             = M('time');
            $info              = $model->field('start,end')->find();
            $model             = null;
            if($info === false)
                $this->error('服務器查詢異常');
            else if($info === null){
                $mes_str            = '沒有時間信息，請選擇信息';
                $btn_str            = '提交';
                $info['start']      = '';
                $info['end']        = '';
                $err_str            = '';
            }else {
                $mes_str            = '';
                $btn_str            = '更新';

                if($info['end'] > time())
                    $err_str        = '未過期';
                else
                    $err_str        = '已過期';

                $info['start']      = date('Y-m-d H:i:s',$info['start']);
                $info['end']        = date('Y-m-d H:i:s',$info['end']);
            }



            $this->assign('str',array('mes_str' => $mes_str,'btn_str' => $btn_str,'err_str' => $err_str));
            $this->assign('info',$info);
            $this->display();
        }


        /**
         * 更新
         */
        public function add() {
            $start           = I('post.start');
            $end             = I('post.end');

            if(empty($start) || empty($end))
                $this->error('非法傳參');

            $model             = M('time');
            //清除历史
            $del_res           = $model->where('1')->delete();
            if($del_res === false)
                $this->error('服務器清理失敗!');

            $start_int              = strtotime($start);
            $end_int                = strtotime($end);
            $insert_res             = $model->add(array('start' => $start_int,'end' => $end_int));
            $logo_model             = null;
            if(!$insert_res)
                $this->error('服務器新增異常');

            $this->redirect('index');
        }

    }
