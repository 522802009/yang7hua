<?php

class ManageController extends Controller
{

	public function indexAction()
	{
		$params = $this->getParams();
		$Member = new Member();
		$members = $Member->select(array(
					'limit' => limit($params['p'], $params['limit']),
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

}
