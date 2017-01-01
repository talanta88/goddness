<?php
	/*
		权限管理
	*/
	namespace Admin\Controller;

	class PermissionController extends CommonController {
		private $_permission_model 		= null;

		public function __construct() {
			parent::__construct();
			$this->_permission_model 	= D('Permission');
		}

		/*
			获取权限列表
		*/
		public function getPermissionList() {
			$permission_info 			= $this->_getPerList();

			$this->assign('info',$permission_info);
			$this->display('list');														
		}

		/*
			新增权限
		*/
		public function addPermission() {
			if(IS_POST) {
				$data 					= $this->_permission_model->create($_POST);
				if(!$data)
					$this->error($this->_permission_model->getError());

				$insert_res 			= $this->_permission_model->add($data);
				if(!$insert_res)
					$this->error('服務器異常');
				else
					$this->redirect('Permission/getPermissionList');
			}

			//获取主权限信息
			$main_info 					= $this->_permission_model->field('id,name')->where(array('parent_id' => 0))->select();
			if($main_info === false)
				$this->error('服務器查詢異常');

			$this->assign('info',$main_info);
			$this->display('add');
		}


		/*
			ajax删除权限
			@param 		id
			@return  	str
		*/
		public function ajaxDel() {
			if(IS_AJAX) {
				$id 			= I('get.id',0,'intval');
				//验证id
				$is_id 			= $this->_permission_model->field('parent_id')->where(array('id' => $id))->find();
				if($is_id === false)
					die('error：服務器查詢異常');
				else if($is_id === null)
					die('error：未找到該權限信息');

				//从角色表中获取该权限是否在使用
				$role_model 	= M('role');
				$map['permission_ids'] 			= array('like','%' . $id . ',');
				$is_use 		= $role_model->field('name,permission_ids')->where($map)->select();
				if($is_use === false)
					die('error：服務器查詢異常');
				else if($is_use) {
					//如果找到类似的，那么严格判断
					foreach ($is_use as $key => $value) {
						if(in_array($id, explode(',', $value['permission_ids']))) {
							die('error：' . $value['name'] . ' 該角色佔用該權限，請先接觸');
						}
					}
				}

				if($is_id['parent_id'] == 0) {
					//同时删除子类
					$del_res 	= $this->_permission_model->where(array('parent_id' => $id))->delete();
					if($del_res === false)
						die('error：服務器刪除失敗');
				}

				//删除数据
				$del_res 		= $this->_permission_model->where(array('id' => $id))->delete();
				if(!$del_res)
					die('error：服務器刪除失敗');

				echo 1;
			}
		}

		/*
			ajax更新是否前台展示
			@param 				id  数据id，value  数据修改值
			@return 			str
		*/
		public function ajaxShow() {
			if(IS_AJAX) {
				$id 			= I('get.id',0);
				$value 			= I('get.value',0);

				//验证id
				$is_id 			= $this->_permission_model->field('id')->where(array('id' => $id))->find();
				if($is_id === false)
					die('-1');
				else if($is_id === null)
					die('0');

				//更改数据
				$update_res 	= $this->_permission_model->where(array('id' => $id))->save(array('is_show' => $value));
				if($update_res === false)
					die('-1');

				echo 1;
			}
		}


		/*
			新增角色	
		*/
		public function addRole() {
			if(IS_POST) {
				$name 					= I('post.name','','trim');
				$permission_arr 		= I('post.per','');

				if(mb_strlen($name) >= 30 || mb_strlen($name) <= 0)
					$this->error('角色名称不能为空或者大于30位');
				if(empty($permission_arr))
					$this->error('角色权限不能为空');

				//数据入库
				$role_model 			= M('role');

				//验证角色名称是否重复
				$is_only 				= $role_model->field('id')->where(array('name' => $name))->find();
				if($is_only === false)
					$this->error('服务器查询错误');
				else if($is_only)
					$this->error('新增角色失败，角色名称重复');

				$insert_res 			= $role_model->add(array('name' => $name,'permission_ids' => implode(',', $permission_arr) . ','));
				if(!$insert_res)
					$this->error('新增角色失败，服务器错误');

				$this->redirect('Permission/getRoleList');
			}

			$permission_info 			= $this->_getPerList();
			$this->assign('per_info',$permission_info);
			$this->display('add_role');
		}

		/*
			角色列表
		*/
		public function getRoleList() {
			$role_model 			= M('role');
			$role_info 				= $role_model->field('id,name')->select();
			$this->assign('info',$role_info);
			$this->display('role_list');
		}

		/*
			ajax删除角色
		*/
		public function ajaxDelRole() {
			if(IS_AJAX) {
				$role_model 			= M('role');
				$id 					= I('get.value',0);
				//验证id合法性
				$is_id 					= $role_model->field('id')->where(array('id' => $id))->find();
				if($is_id === false)
					die('-1');
				else if($is_id === null)
					die('0');

				//验证管理员是否有正在使用该角色的
				$admin_model 			= M('admin');
				$map['role_ids'] 		= array('like','%' . $id . ',');
				$is_use 				= $admin_model->field('name,role_ids')->where($map)->select();
				if($is_use === false)
					die('-1');
				else if($is_use) {
					//如果找到类似的，那么严格判断
					foreach ($is_use as $key => $value) {
						if(in_array($id, explode(',', $value['role_ids']))) {
							die($value['name']);
						}
					}
				}

				//删除
				$del_res 				= $role_model->where(array('id' => $id))->delete();
				if(!$del_res)
					die('-1');

				echo '1';
			}
		}

		/*
			修改角色信息
			@param 			id role_id
		*/
		public function mofRoleInfo() {
			if(IS_POST) {
				$id 			= I('post.id',0);

				//验证id
				$role_model 	= M('role');
				$is_id 			= $role_model->field('id')->where(array('id' => $id))->find();
				if($is_id === false)
					$this->error('服务器查询错误');
				else if($is_id === null)
					$this->error('非法传值');

				//接收其他参数
				$name 			= I('post.name','','trim');
				$permission_arr	= I('post.per','');

				if(mb_strlen($name) >= 30 || mb_strlen($name) <= 0)
					$this->error('角色名称不能为空或者大于30位');
				if(empty($permission_arr))
					$this->error('角色权限不能为空');

				//验证角色名称
				$map['name'] 	= $name;
				$map['id'] 		= array('neq',$id);
				$is_only 		= $role_model->field('id')->where($map)->find();
				if($is_only === false)
					$this->error('服务器查询错误');
				else if($is_only)
					$this->error('该角色名称已经被占用');

				//修改数据
				$update_res 	= $role_model->where(array('id' => $id))->save(array('name' => $name,'permission_ids' => implode(',', $permission_arr) . ','));
				if($update_res === false)
					$this->error('服务器修改数据错误');

				$this->success('修改角色信息成功',U('Permission/getRoleList'));
				die;
			}

			if(IS_GET) {
				$id 			= I('get.id',0);
				$role_model 	= M('role');
				$is_id 			= $role_model->field('id,name,permission_ids')->where(array('id' => $id))->find();
				if($is_id === false)
					$this->error('服务器查询错误');
				else if($is_id === null)
					$this->error('非法传值');

				$permission_info 			= $this->_getPerList();

				$this->assign('per_info',$permission_info);
				$this->assign('mof_info',$is_id);
				$this->display('add_role');
			}
		}


		/*
			管理员新增
		*/
		public function adminAdd() {
			if(IS_POST) {
				//接收参数
				$name 				= I('post.name','','trim');
				if(mb_strlen($name) <=0 || mb_strlen($name) > 30)
					$this->error('管理员账号必须在30位之内');

				$pass 				= I('post.pass','','trim');
				if(mb_strlen($pass) < 6)
					$this->error('密码至少6位');

				$role_arr 			= I('post.rol','');
				if(!$role_arr)
					$this->error('您必须给该管理分配角色');

				//验证名称是否重复
				$admin_model 		= M('admin');
				$is_only 			= $admin_model->field('id')->where(array('name' => $name))->find();
				if($is_only === false)
					$this->error('服务器查询错误');
				else if($is_only)
					$this->error('该名称已经被占用');

				//入库
				$pass 				= md5(C('PASS_RAND') . $pass);
				$insert_res 		= $admin_model->add(array('name' =>$name ,'pass' => $pass,'role_ids' => implode(',', $role_arr) . ','));
				if(!$insert_res)
					$this->error('服务器新增错误');

				$this->redirect('Permission/adminList');
			}
			//获取所有角色
			$role_model 			= M('role');
			$role_info 				= $role_model->field('id,name')->select();
			if($role_info === false)
				$this->error('服务器查询错误');

			$this->assign('role_info',$role_info);
			$this->display('add_admin');
		}



		/*
			管理员列表
		*/
		public function adminList() {
			$admin_model 		= M('admin');
			$admin_id 			= session(C('ADMIN_ID'));

			if(session('?' . C('ADMIN_SUPER')))
				$admin_info 		= $admin_model->field('id,name,IF(is_super=0,"<span class=\"glyphicon glyphicon-remove\"></span>","<span class=\"glyphicon glyphicon-ok btn_color_share\"></span>") is_super_str,is_super,last_ip,last_time')->order('is_super asc')->select();
			else 
				$admin_info 		= $admin_model->field('id,name,IF(is_super=0,"<span class=\"glyphicon glyphicon-remove\"></span>","<span class=\"glyphicon glyphicon-ok btn_color_share\"></span>") is_super_str,is_super,last_ip,last_time')->where(array('is_super' => 0))->order('is_super asc')->select();
			if($admin_info === false)
				$this->error('服務器查詢失敗');

			$this->assign('info',$admin_info);
			$this->display('admin_list');
		}

		/*
			删除管理员
		*/
		public function adminDel() {
			$id 				= I('get.value',0);

			//验证id
			$admin_model 		= M('admin');
			$is_id 				= $admin_model->field('id,is_super')->where(array('id' => $id))->find();
			if($is_id === false)
				die('-1');
			else if($is_id === null)
				die('0');

			//超级管理不可以删除
			if($is_id['is_super'] == 1)
				die('0');

			//执行删除
			$del_res 			= $admin_model->where(array('id' => $id))->delete();
			if(!$del_res)
				die('-1');

			echo '1';
		}

		/*
			管理员信息修改
		*/
		public function adminMof() {
			if(IS_POST) {
				$id 				= I('post.id',0);
				$admin_model 		= M('admin');
				$is_id 				= $admin_model->field('id,is_super,role_ids')->where(array('id' => $id))->find();
				if($is_id === false)
					$this->error('服务器查询错误');
				else if($is_id === null)
					$this->error('非法传值');

				//严格验证，只有超级管理可以修改自身
				if($is_id['is_super'] == 1) {
					//获取当前后台用户等级
					$admin_id 		= session(C('ADMIN_ID'));
					$info 			= $admin_model->field('is_super')->where(array('id' => $admin_id))->find();
					if($info === false)
						$this->error('服务器查询错误');
					else if($info === null)
						$this->error('服务器错误');

					if($info['is_super'] != 1)
						$this->error('操作失败，无权限');
				}


				//接收参数
				$name 		= I('post.name','','trim');
				if(mb_strlen($name) <=0 || mb_strlen($name) > 30)
					$this->error('管理员账号必须在30位之内');

				$pass 				= I('post.pass','');
				if($pass) {
					if(mb_strlen($pass) < 6)
						$this->error('密码至少6位');
				}
				$is_super 			= I('post.is_super',0);
				$role_arr 			= I('post.rol','');


				//验证管理员名称是否重复
				$map['id'] 			= array('neq',$id);
				$map['name'] 		= $name;

				$is_only 			= $admin_model->field('id')->where($map)->find();
				if($is_only === false)
					$this->error('服务器查询错误');
				else if($is_only)
					$this->error('管理员账号重复');

				//修改数据
				if($pass)
					$update_res 	= $admin_model->where(array('id' => $id))->save(array('name' => $name,'pass' => md5(C('PASS_RAND') . $pass),'role_ids' => implode(',', $role_arr) . ','));
				else 
					$update_res 	= $admin_model->where(array('id' => $id))->save(array('name' => $name,'role_ids' => implode(',', $role_arr) . ','));

				if($update_res === false)
					$this->error('服务器修改数据错误');

				$this->success('数据修改成功',U('Permission/adminList'));die;
			}

			if(IS_GET) {
				$id 			= I('get.id',0);
				$admin_model 	= M('admin');
				$is_id 			= $admin_model->field('id,name,is_super,role_ids')->where(array('id' => $id))->find();
				if($is_id === false)
					$this->error('服务器查询错误');
				else if($is_id === null)
					$this->error('非法传值');

				//获取角色信息
				$role_model 	= M('role');
				$role_info 		= $role_model->select();

				$this->assign('role_info',$role_info);
				$this->assign('mof_info',$is_id);
				$this->display('add_admin');
			}
		}

		/*
			获取格式化后的权限列表
		*/
		private function _getPerList() {
			$permission_info 			= $this->_permission_model
																	->field('id,name,contro_name,action_name,parent_id,IF(is_show=0,"否","是") is_show')
																	->order('id asc')
																	->select();
			if($permission_info === false)
				$this->error('服务器查询错误');
			else if($permission_info === null)
				$permission_info 		= array();

			$permission_info 			= $this->_listDate($permission_info);

			return $permission_info;
		}






	}