<?php

if($_SERVER['HTTP_HOST'] == 'zxl.local.com')
	return array(
		'database'	=>	array(
			'host'		=>	'localhost',	
			'username'	=>	'root',
			'password'	=>	'0908',
			'dbname'	=>	'zhxla_dev',
			'charset'	=>	'utf8'
			)
		);
else
	return array();
