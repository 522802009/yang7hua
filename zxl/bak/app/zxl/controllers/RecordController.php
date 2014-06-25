<?php

class RecordController extends Controller{

	public function listAction()
	{
		$params = $this->getParams();

		$Record = new Record();
		if(!isset($params['cardnumber']))
			$this->ajaxReturn('参数错误', false);
		$where['card_type'] = 1;
		$where['card_number'] = $params['cardnumber'];

		if($Record->validation($where))
			exit('error');

		$condition = "card_type = {$data['card_type']} and card_number = '{$data['card_number']}'";
			
		$list = $Record->select(array(
						$condition,
						'limit'	=>	limit($params['p'], $params['limit'])	
					));
		$count = $Record->count();
		$page = $this->page($count, $params['limit']);
		$this->ajaxReturn(array(
					'list'	=>	$list,
					'page'	=>	$page
				));
	}

}
