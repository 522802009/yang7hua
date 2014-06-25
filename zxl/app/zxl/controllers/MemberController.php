<?php

use \Common\Func as Func;
/**
  会员账号相关
*/
class MemberController extends Controller
{
	protected $_allowAction = array('login', 'apply');

	public function initialize()
	{
		parent::initialize();
	}

	public function indexAction()
	{
		$member_id = $this->getMemberId();
		$Member = new Member();
		$info = $Member->first(array(
					"memberid=$member_id"
				));
		$this->view->setVar('info', $info);
		$this->display();
	}

	public function loginAction()
	{
		if($this->isAjax()){
			$params = $this->getParams();
			$username = $params['username'];
			$password = $params['password'];
			if(empty($username) || empty($password))
				$this->ajaxReturn('参数错误', false);

			$password = Func\password($password);

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
		$this->session->remove('member_id');
		$this->session->remove('username');
		$this->ajaxReturn('登出成功');
	}

	public function applyAction()
	{
		if($this->isAjax()){
			$params = $this->getParams();
			if(empty($params['company']) || empty($params['address'])
					|| empty($params['contact1']) 
					|| empty($params['contact1_phone']) || empty($params['email'])
					//|| empty($params['captcha'])
			)
				$this->ajaxReturn('参数错误', false);

			$Member = new Member();
			$info = $Member->findFirst(array(
							"companyname='{$params['company']}'"
						));
			if($info)
				$this->ajaxReturn('该公司名已存在', false);

			$params['companyname'] = $params['company'];
			$params['tablename'] = 'member';
			$Member->setQueryOptions($params)->doInsert();
		
			return;
		}
		$this->display();
	}

	public function editAction()
	{
		$Member = new Member();
		$info = $Member->first(array(
					'memberid=' . $this->getMemberId()
					)
				);
		$this->view->setVar('info', $info);
		$this->display();
	}

	public function saveAction()
	{
		$params = $this->getParams();
		$Member = new Member();
		$memberid = intval($this->getMemberId());
		$info = $Member->findFirst($memberid);
		if(!$info)
			$this->ajaxReturn('无数据', false);

		$params['tablename'] = 'member';
		$params['where'] = "memberid=$memberid";
		$params['companyname'] = $params['company'];

		$Member->setQueryOptions($params)->doUpdate();
	}
	
}
