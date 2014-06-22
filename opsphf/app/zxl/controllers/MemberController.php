<?php
use \Phf\Mvc\Model\Resultset;

class MemberController extends Controller{

	public function initialize()
	{
		parent::initialize();
	}

	public function indexAction()
	{
	}

	public function infoAction()
	{
		$member_id = $this->session->get('member_id');
		$Member = new Member();
		$info = $Member::findFirst(array(
					'memberid'	=>	$member_id
				));
	}

	public function addAction()
	{
		$params = $this->request->get();
		if(empty($params['username']) || empty($params['password']) || empty($params['company']))
			$this->ajaxReturn('参数错误', false);

		$data['username'] = $params['username'];
		$data['password'] = $params['password'];
		$data['companyname'] = $params['company'];
		$data['address'] = $this->request->get('address', '', '');
		$data['email'] = $this->request->get('email', 'email', '');
		$data['addtime'] = time();
		$Member = new Member();
		$result = $Member->insert($data);
		if($result === true)
			$this->ajaxReturn('添加成功');
		else
			$this->ajaxReturn('添加失败', false);
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
		if($info->save() == false)
			$this->ajaxReturn('操作失败', false);
		$this->ajaxReturn('操作成功');
	}
	
}
