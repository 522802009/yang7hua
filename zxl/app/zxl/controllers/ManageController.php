<?php

use \Common\Func as Func;

class ManageController extends Controller
{
	protected $_allowAction = array('login');

	public function indexAction()
	{
		$params = $this->getParams();
		$Member = new Member();
		$members = $Member->select(array(
					'limit' => Func\limit($params['p'], $params['limit']),
					'fields'	=>	array('password', true) 
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

		$params['tablename'] = 'member';
		$params['where'] = "memberid=$memberid";
		$params['fields'] = array('username', 'password', 'companyname', 'status');
		$Member->setQueryOptions($params)->doUpdate();
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
				$this->ajaxReturn('登陆成功', false);
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
