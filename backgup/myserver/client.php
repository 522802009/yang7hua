<?php
try{
	$client = new SoapClient(null, array('location'=>'http://127.0.0.1/test/myserver/server.php', 'uri'=>'server.php'));
	//$client->__soapCall('getName', array('Yang Hua'));
	$client->getName('Yang Hua');
}catch(SoapFault $e){
	echo $e->getMessage();
}catch(Exception $e){
	echo $e->getMessage();
}
