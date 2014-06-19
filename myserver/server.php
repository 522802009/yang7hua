<?php

class TestClass{

	public function getName($name)
	{
		echo $name;
	}

}

$soap = new SoapServer(null, array('location'=>'http://127.0.0.1/test/myserver/server.php', 'uri'=>'server.php'));

$soap->setClass('TestClass');

$soap->handle();
