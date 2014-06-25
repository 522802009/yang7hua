<?php

use \Common\Func as Func;

class RecordController extends Controller{

	public function listAction()
	{
		$params = $this->getParams();

		$Record = new Record();
		if(!isset($params['cardnumber']))
			$this->ajaxReturn('参数错误', false);
		$where['card_type'] = 1;
		$where['card_number'] = $params['cardnumber'];

		$condition = "card_type = {$where['card_type']} and card_number = '{$where['card_number']}'";
			
		$list = $Record->select(array(
						$condition,
						'limit'	=>	Func\limit($params['p'], $params['limit'])	
					));
		$count = $Record->count();
		$page = $this->page($count, $params['limit']);
		$this->view->setVar('record', array(
					'list'	=>	$list,
					'page'	=>	$page
				));
		$this->display();
	}

}
