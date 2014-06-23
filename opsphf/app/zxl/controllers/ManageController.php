<?php

class ManageController extends Controller
{

	public function indexAction()
	{
		$params = $this->getParams();
		$Member = new Member();
		$members = $Member->select(array(
					'limit' => limit($params['p'], $params['limit']),
					//'fields'	=>	array('username', true) 
					'fields'	=>	array('username', 'password') 
					//'fields'	=>	'username,password'
					//'fields'	=>	array(array('username','password'), true)
				));
		$count = $Member->count();
		$page = $this->page($count, $params['limit']);
		$this->ajaxReturn(array(
					'members' => array(
						'list'	=>	$members,
						'page'	=>	$page
					)
				));
	}

}
