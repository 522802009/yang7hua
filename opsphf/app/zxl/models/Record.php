<?php

class Record extends Model{

	public function insert($data)
	{
		global $di;
		$sql = 'insert into record (member_id, card_type, card_number, reason, addtime)'
			 . 'values(:member_id, :card_type, :card_number, :reason, :addtime)'; 
		$sth = $di->get('db')->prepare($sql);
		$sth->bindParam(':member_id', $data['member_id'], PDO::PARAM_INT);
		$sth->bindParam(':card_type', $data['card_type'], PDO::PARAM_INT);
		$sth->bindParam(':card_number', $data['card_number'], PDO::PARAM_STR, 18);
		$sth->bindParam(':reason', $data['reason'], PDO::PARAM_INT);
		$sth->bindParam(':addtime', $data['addtime'], PDO::PARAM_INT);
		return $sth->execute();
	}

}
