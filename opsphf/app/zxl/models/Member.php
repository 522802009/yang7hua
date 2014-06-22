<?php

class Member extends Model{

	protected $selectFields = array('memberid', 'username', 'companyname');
	
	public function select(array $param=array())
	{
		$data = $this->find($param);
		return $this->format($data);
	}

	public function insert(array $data=array())
	{
		try{
			$this->db->insert(
						'member',
						array($data['username'], $data['password'], $data['companyname'], $data['address'], $data['addtime'], $data['email']),
						array('username', 'password', 'companyname', 'address', 'addtime', 'email')
				  );
		}catch(PDOException $e){
			//return $e->getMessage();
			return false;
		}
		return true;
	}

}
