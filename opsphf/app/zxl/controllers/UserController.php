<?php

class UserController extends Controller
{

	public function listAction()
	{
		$params = $this->getParams();

		$User = new User();
		$list = $User->select(array(
						'limit'	=>	limit($params['p'], $params['limit'])	
					));
		$count = $User->count();
		$page = $this->page($count, $params['limit']);
		$this->ajaxReturn(array(
					'list'	=>	$list,
					'page'	=>	$page
				));
	}

	public function addAction()
	{
		$params = $this->getParams();
		$cardnumber = $params['cardnumber']; 
		if(empty($cardnumber))
			$this->ajaxReturn('参数错误', false);

		$User = new User();
		$User->idcard= $cardnumber;
		$User->realname = 'yanghua';
		$User->addtime = time();
		$User->uptime = time();
		$User->save();
	}

}
