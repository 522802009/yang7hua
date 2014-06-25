<?php

/**
  会员账号相关
*/
class MemberController extends Controller{
	protected $_allowAction = array('login');

	public function initialize()
	{
		parent::initialize();
	}

	public function indexAction()
	{
		echo $this->isLogin() ? 1 : 0;
	}

	public function loginAction()
	{
		if($this->isAjax()){
			$params = $this->getParams();
			$username = $params['username'];
			$password = $params['password'];
			if(empty($username) || empty($password))
				$this->ajaxReturn('参数错误', false);

			$password = password($password);

			$Member = new Member();
			$info = $Member->findFirst("username = '$username' and password = '$password'");
			if(!$info)
				$this->ajaxReturn('账号或密码不正确', false);
			if($info->status != 1)
				$this->ajaxReturn('您的账号未通过审核', false);

			$this->session->set('member_id', $info->memberid);
			$this->session->set('username', $info->username);

			$this->ajaxReturn('登陆成功');
		}
		$this->display();
	}

	public function logoutAction()
	{
		$this->session->destroy();
		$this->ajaxReturn('登出成功');
	}

	public function infoAction()
	{
		$member_id = $this->getMemberId();
		$Member = new Member();
		$info = $Member->first(array(
					"memberid=$member_id",
					'fields'	=>	'username'
				));
		$this->view->setVar('memberinfo', $info);
		$this->display();
	}

	public function addAction()
	{
		$params = $this->request->get();
		if(empty($params['username']) || empty($params['password']) || empty($params['company']))
			$this->ajaxReturn('参数错误', false);

		$Member = new Member();
		$Member->username = $params['username'];
		$Member->password = $params['password'];
		$Member->companyname = $params['company'];
		$Member->address = $this->request->get('address', '', '');
		$Member->email = $this->request->get('email', 'email', '');
		$Member->addtime = time();
		$Member->save();
	}

	public function saveAction()
	{
		$params = $this->getParams();
		if(empty($params['id']))
			$this->ajaxReturn('参数错误', false);
		$Member = new Member();
		$member_id = intval($params['id']);
		$info = $Member->findFirst($member_id);
		if(isset($params['password']))	
			$info->password = password($params['password']);
		$this->save();
	}
	
}
