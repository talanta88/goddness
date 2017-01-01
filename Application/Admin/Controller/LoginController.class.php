<?php
	/*
		后台登录页面
	*/

	namespace Admin\Controller;

	class LoginController extends CommonController {

		/*
			登录页面
		*/
		public function index() {
			//判断是否已经登录，如果已经登录，那么返回
			if(session('?' . C('ADMIN_ID')))
				$this->redirect('Index/index');

			$this->display();
		}

		/*
			产生验证码
		*/
		public function getCode() {
			$config 		= array(	
										'fontSize' 	  => 22,
									    'length'      => 4,     // 验证码位数
									    'useCurve' 	  => false,
									    									);
			$Verify 		= new \Think\Verify($config);
			$Verify->entry();
		}

		/*
			验证验证码
		*/
		public function checkCode() {
			if(IS_AJAX) {
				$code 			= I('get.code','','trim');
				$verify 		= new \Think\Verify(array('reset'=> false));
	    		if($verify->check($code))
	    			echo 1;
	    		else 
	    			echo 0;
	    	}
		}

		/*
			管理员登录
		*/
		public function login() {
			if(IS_AJAX) {
				//判断是否已经登录，如果已经登录，那么返回
				if(session('?' . C('ADMIN_ID')))
					die('error: 非法操作，已經登錄');

				$where['name'] 		= I('post.username','','htmlspecialchars');
				$where['pass'] 		= I('post.password','','htmlspecialchars');

				$admin_model 		= D('Admin');
				if(!$admin_model->create($where))
					die('error:' . $admin_model->getError());

				$admin_info 		= null;
				$admin_info 		= $admin_model->login($where);
				if(!$admin_info)
					die('error:用戶名或密碼錯誤');

				//登录成功，保存session，跳转
				session(C('ADMIN_ID'),$admin_info['id']);
				session(C('ADMIN_NAME'),$where['name']);
				session('temp_admin_ip',long2ip($admin_info['last_ip']));
				if($admin_info['last_ip'] == 0) 
					session('temp_admin_addr','第一次登錄');
				else {
					$ip_info_model 		= new \Org\Net\IpLocation();
					$ip_info 			= $ip_info_model->getlocation(long2ip($admin_info['last_ip']));
					session('temp_admin_addr',$ip_info['country']);
				}
				if($admin_info['last_time'] != 0)
					session('temp_admin_time',date('Y-m-d',$admin_info['last_time']));

				//更新ip跟时间
				$up_data['last_time'] 	= time();
				$up_data['last_ip'] 	= get_client_ip(1);
				if(!$admin_model->reLastInfo($admin_info['id'],$up_data)){
					session(null);
					die('error:服務器錯誤，請檢查');
				}

				echo 'ok';
			}
		}


		/*
			退出登录
		*/
		public function logout() {
			//清空session
			session(null);
			//跳转
			$this->redirect('Login/index');
		}




	}