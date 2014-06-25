<?php

class IndexController extends Controller{

	public function initialize()
	{
		parent::initialize();
	}

	public function indexAction()
	{
		$this->view->pick('search/index');
	}

}
