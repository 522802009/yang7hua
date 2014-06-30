<?php

use \Common\Func as Func;

class ManageController extends Controller
{
	protected $_allowAction = array('login');
	private $_canOpeAdmin = array('addadmin', 'admin', 'deladmin');

	const SUPER_MID = 1;

	protected function initialize()
	{
		$this->view->setVar('mname', $this->session->get('mname'));
		$this->view->setVar('mid', $this->session->get('mid'));
		$this->view->setVar('issuper', $this->session->get('mid') == self::SUPER_MID);
		$this->checkOpeAdmin();
		parent::initialize();
	}

	public function indexAction()
	{
		$params = $this->getParams();
		$Member = new Member();
		$members = $Member->select(array(
					'limit' => Func\limit($params['p'], $params['limit']),
					'fields'	=>	array(array('password'), true),
					'order'	=>	'addtime desc'
				));
		$count = $Member->count();
		$page = $this->page($count, $params['limit']);
		$this->view->setVar('members', array(
						'list'	=>	$members,
						'page'	=>	$page
						)
					);
		$this->display();
	}

	public function saveMemberAction()
	{
		$params = $this->getParams();
		if(empty($params['memberid']))
			$this->ajaxReturn('参数错误', false);

		$memberid = $params['memberid'];
		$Member = new Member();

		/*if($Member->findFirst(array(
						"companyname='{$params['companyname']}'"		
					)))
			$this->ajaxReturn('该公司名已存在');*/

		$params['status'] = intval($params['status']);
		$params['tablename'] = 'member';
		$params['where'] = "memberid=$memberid";
		$params['fields'] = array('password', 'companyname', 'status');
		$Member->setQueryOptions($params)->doUpdate();
	}

	public function addMemberAction()
	{
		if($this->isAjax()){
			$params = $this->getParams();
			if(empty($params['username']) || empty($params['password']) 
					|| empty($params['repassword']) || empty($params['company'])
			  )
				$this->ajaxReturn('参数错误', false);
			if($params['password'] != $params['repassword'])
				$this->ajaxReturn('两次密码不一致', false);

			$Member = new Member();
			if($Member->findFirst(array(
							"username='{$params['username']}'"	
							))
			  ){
				$this->ajaxReturn('该用户名已存在', false);
			}
			if($Member->findFirst(array(
							"companyname='{$params['company']}'"	
							))
			  ){
				$this->ajaxReturn('该公司名已存在', false);
			}

			$params['tablename'] = 'member';
			$params['companyname'] = $params['company'];
			$params['addtime'] = time();
			$params['status'] = 1;
			$params['fields'] = array('username', 'password', 'companyname', 'addtime', 'status');
			$Member->setQueryOptions($params)->doInsert();
		}
		$this->display();
	}

	private function checkOpeAdmin()
	{
		if(in_array($this->getActionName(), $this->_canOpeAdmin) && $this->session->get('mid') != self::SUPER_MID){
			if($this->isAjax())
				$this->ajaxReturn('无权限操作', false);
			else
				exit('无权限操作');
		}
	}

	public function adminAction()
	{
		if($this->isAjax()){
			$Admin = new Admin();
			$params = $this->getParams();
			$admin['list'] = $Admin->select(array(
							'limit' => Func\limit($params['p'], $params['limit']),
							'fields'	=>	array('mid', 'username')
						));
			$count = $Admin->count();
			$admin['page'] = $this->page($count, $params['limit']);
			$this->ajaxReturn($admin);
		}
		$this->display();
	}

	public function addAdminAction()
	{
		if($this->isAjax()){
			$params = $this->getParams();

			if(empty($params['username']) || empty($params['password']) || empty($params['repassword']))
				$this->ajaxReturn('参数错误', false);
			if($params['password'] != $params['repassword'])
				$this->ajaxReturn('两次密码不一致', false);

			$Admin = new Admin();
			if($Admin->findFirst(array(
							"username='{$params['username']}'"	
							)))
				$this->ajaxReturn('该账号已存在', false);
			$password = Func\password($params['password']);
			$params['tablename'] = 'admin';
			$params['password'] = $password; 
			$params['fields'] = array('username', 'password');
			$Admin->setQueryOptions($params)->doInsert();
		}
		$this->display();
	}

	public function saveAdminAction()
	{
		$params = $this->getParams();

		if(empty($params['password']) || empty($params['mid']))
			$this->ajaxReturn('参数错误', false);
		$Admin = new Admin();
		$mid = intval($params['mid']);
		$info = $Admin->findFirst(array(
						"mid=$mid"	
					));
		if(!$info)
			$this->ajaxReturn('参数错误', false);

		$params['tablename'] = 'admin';
		$params['fileds'] = array('password');
		$params['password'] = Func\password($params['password']);
		$params['where'] = "mid=$mid";
		$Admin->setQueryOptions($params)->doUpdate();
	}

	public function delAdminAction()
	{
		$params = $this->getParams();
		if(empty($params['mid']) || $params['mid'] == 1)
			$this->ajaxReturn('参数错误', false);

		$Admin = new Admin();
		$mid = intval($params['mid']);

		if($this->session->get('mid') !== self::SUPER_MID)
			$this->ajaxReturn('无权限', false);

		$info = $Admin->findFirst(array(
						"mid=$mid"	
					));
		if(!$info)
			$this->ajaxReturn('参数错误', false);
		if($info->delete())
			$this->ajaxReturn('操作成功');
		else
			$this->ajaxReturn('操作失败', false);
	}

	public function loginAction()
	{
		if($this->isLogin())
			$this->redirect('manage/index');
		if($this->isAjax()){
			$params = $this->getParams();
			if(empty($params['username']) || empty($params['password']))
				$this->ajaxReturn('参数错误', false);
			$Admin = new Admin();
			$password = Func\password($params['password']);
			$info = $Admin->findFirst(array(
						"username='{$params['username']}' and password='{$password}'"
					));
			if($info){
				$this->session->set('mid', $info->mid);
				$this->session->set('mname', $info->username);
				$this->ajaxReturn('登陆成功');
			}else
				$this->ajaxReturn('用户名或密码不正确', false);
			return;
		}
		$this->display();
	}

	public function logoutAction()
	{
		$this->session->remove('mid');
		if($this->isAjax())
			$this->ajaxReturn('登出成功');
		$this->redirect('manage/login');
	}

}
