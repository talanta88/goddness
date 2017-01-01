<?php
namespace Admin\Controller;

class IndexController extends CommonController {

	/*
		展示主页面
	*/
    public function index(){
    	$rule_info 			= session(C('ADMIN_RULE'));
    	$this->assign('rule_info',$rule_info);
        $this->display('Base/base');
    }


    /*
		框架主页面
    */
    public function showIndex() {
    	$info = array(
				            '操作系統'				=>PHP_OS,
				            '運行環境'				=>$_SERVER["SERVER_SOFTWARE"],
				            'PHP運行方式'				=>php_sapi_name(),
				            '上傳附件限制'			   =>ini_get('upload_max_filesize'),
				            '執行時間限制'			   =>ini_get('max_execution_time').'秒',
				            '服務器時間'				=>date("Y年n月j日 H:i:s"),
				            '服務器域名/IP'			=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
				            '剩餘空間'				=>round((disk_free_space(".")/(1024*1024)),2).'M',
				            'register_globals'		=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
				            'magic_quotes_gpc'		=>(1===get_magic_quotes_gpc())?'YES':'NO',
				            'magic_quotes_runtime'	=>(1===get_magic_quotes_runtime())?'YES':'NO',
		            );
        $this->assign('info',$info);
        $this->display('index');
    }

}