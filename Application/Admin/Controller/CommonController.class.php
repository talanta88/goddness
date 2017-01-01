<?php
	/*
		后台统一基类
	*/
	namespace Admin\Controller;
	use Think\Controller;

	class CommonController extends Controller {
		private $_accessAuthor 		= array();
		private $_controName 		= CONTROLLER_NAME;
		private $_actionName 		= ACTION_NAME;
		protected $_areaName  		= 'area_cache';		
		const SUPER_RULE 			= 1; //标志超级管理员

		//构造
		public function __construct() {
			parent::__construct();
			$this->checkPower();
		}

		//权限验证
		protected function checkPower() {
			//构建当前用户方法
			$user_action 				= strtolower($this->_controName . '/' . $this->_actionName);

			//创建用户权限过滤数组,值全为小写
			$this->_accessAuthor 		= array(
													'login/getcode',
													'login/index',
													'login/checkcode',
													'login/login'
												);
			if(in_array($user_action, $this->_accessAuthor))
				return true;

			//获取当前用户权限信息
			$admin_id 					= session(C('ADMIN_ID'));
			if(!$admin_id)
				$this->redirect('Login/index');

			//判断是否存在缓存权限信息,如果存在，直接返回
			//if(session('?' . C('ADMIN_RULE'))) {
			//	//如果是超级管理员，直接返回true
			//	if(session('?' . C('ADMIN_SUPER')))
			//		return true;
            //
			//	//如果当前访问越界，那么报错
			//	$per_info 					= session(C('ADMIN_RULE'));
			//	$access_arr 				= array();
			//	foreach ($per_info as $key => $value) {
			//		if(isset($value['son'])) {
			//			foreach ($value['son'] as $k => $v) {
			//				$access_arr[] 			= strtolower($v['contro_name'] . '/' . $v['action_name']);
			//			}
			//		}
			//	}
			//	if(!in_array($user_action,$access_arr)){
			//		if(IS_AJAX)
			//			die('error：您没有权限进行该操作');
			//		else
			//			$this->error('您没有权限进行该操作');
			//	}
			//
			//	return true;
			//}

			//验证当前管理员信息
			$admin_model 				= M('admin');
			$info 						= array();
			$info 						= $admin_model->field('id,is_super,role_ids')->where(array('id' => $admin_id))->find();
			if(!$info)
				$this->error('服務器查詢管理員信息失敗');

			//写入当前管理员权限信息
			$per_info 					= array();
			if($info['is_super'] == self::SUPER_RULE){
				//如果是超级管理员
				$per_info 				= $this->_getPerList();

				session(C('ADMIN_SUPER'),'1');
			}else {
				//非超级管理员
				$role_ids 				= '';
				$role_ids 				= trim($info['role_ids'],',');
				if(!$role_ids){
					session(null);
					$this->error('沒有權限操作任何信息');
				}
				
				//获取角色信息
				$role_model 			= M('role');
				$role_info 				= array();
				$role_info 				= $role_model->field('permission_ids')->where(array('id' => array('in',$role_ids)))->select();
				if(!$role_info){
					session(null);
					$this->error('服務器獲取角色信息失敗，驗證錯誤');
				}

				//合并数据
				$permission_ids 		= '';
				foreach ($role_info as $key => $value) {
					$permission_ids 	.= trim($value['permission_ids'],',') . ',';
				}
				$role_info 				= null;

				//去除首尾空格
				$permission_ids 		= trim($permission_ids,',');

				//获取权限信息
				$permission_model 		= M('permission');
				$per_info 				= $permission_model->field('id,name,contro_name,action_name,parent_id,is_show')->where(array('id' => array('in',$permission_ids)))->order('id asc')->select();
				if(!$per_info){
					session(null);
					$this->error('服務器獲取權限信息失敗，驗證錯誤');
				}
				$per_info 				= $this->_listDate($per_info);
			}
			session(C('ADMIN_RULE'),$per_info);
			return true;
		}


		/*
			获取全部权限，即超级管理员的权限
		*/
		private function _getPerList() {
			$permission_model 			= M('permission');
			$permission_info 			= array();
			$permission_info 			= $permission_model
															->field('id,name,contro_name,action_name,parent_id,is_show')
															->order('id asc')
															->select();
			if($permission_info === false)
				$this->error('查詢錯誤');
			else if($permission_info === null)
				$permission_info 		= array();

			$permission_info 			= $this->_listDate($permission_info);

			return $permission_info;
		}


		/*
			格式化数据,需要有parent_id。将子类重新合并到父类的son字段下面
		*/
		protected function _listDate($arr) {
			if(count($arr) <= 1)
				return $arr;

			//合成父类
			$arr_parent 				= array();
			foreach ($arr as $key => $value) {
				if($value['parent_id'] == 0){
					$arr_parent[] 		= $value;
					unset($arr[$key]);
				}
			}

			//追加子类
			foreach ($arr_parent as $key => $value) {
				foreach ($arr as $k => $v) {
					if($v['parent_id'] == $value['id']) {
						$arr_parent[$key]['son'][] 		= $v;
						unset($arr[$k]);
					}
				}
			}

			return $arr_parent;
		}

		/*
			分页编号
		*/
		protected function _pageIndex($count) {
			$page_num 				= I('get.p',1,'intval');

			$page_max_num 			= ceil($count / C('PAGE_SIZE'));
			if($page_num >= $page_max_num)
				$page_num 			= $page_max_num;

			$index 					= ($page_num - 1) * C('PAGE_SIZE') + 1;


			$page    				= new \Think\Page($count,C('PAGE_SIZE'));
			$show 					= $page->show();

			$this->assign('page',$show);
			$this->assign('index',$index);
		}

		/*
			拼接sql语句的page条件
		*/
		protected function _page($count) {
			$page_num 				= I('get.p',1,'intval');
			$page_max_num 			= ceil($count / C('PAGE_SIZE'));
			if($page_num >= $page_max_num)
				$page_num 			= $page_max_num;

			return $page_num . ',' . C('PAGE_SIZE');
		}

		/*
			获取图片，
			@param 		id 文件id
						type 1,缩略图；2原图
		*/
		protected function _getImgById($id,$type=1,$fid=0) {
			$file_model 	= M('file');
			$file_info 		= $file_model->field('file_dir,thumb_img_dir')->where(array('file_id' => $id,'family_id' => $fid))->find();
			
			if($file_info === false)
				$this->error('查詢失敗');
			else if($file_info === null)
				$this->error('獲取圖片失敗');
			
			if($type == 1)
				return 	C('HTTP_URL') . $file_info['thumb_img_dir'];
			else 
				return C('HTTP_URL') . $file_info['file_dir'];

			
		}

		/**
		 * 编辑器输入过滤\",&quot;
		 * @param $str
		 * @return mixed|string
		 */
		public function strFalter($str){
			if(!$str){
				return '';
			}
			return str_replace('&quot;','',str_replace('\\','',$str));
		}

	}	