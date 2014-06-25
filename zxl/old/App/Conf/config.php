<?php

if ($_SERVER['SERVER_ADDR'] == '127.0.0.1') {
    if ($_SERVER['HTTP_HOST'] == 'beta.zhengxinla.com')
    $db_dsn = 'mysqli://root:mysql@127.0.0.1:3306/zhxla_dev';
    else
    $db_dsn = 'mysql://root:0908@localhost:3306/zhxla_dev';
} else {
    $db_dsn = 'mysqli://zhxla_dev:zhxla.17dev.mysql@192.168.1.69:3306/zhxla_dev';
}

return array(
	//'配置项'=>'配置值'
	'DB_PREFIX'	=>	'',
    'DB_DSN'	=>	$db_dsn,
	'DB_FIELDTYPE_CHECK' => true,

	'URL_CASE_INSENSITIVE'	=>	true,

	'TMPL_PARSE_STRING'	=>	array(
		'__static'	=>	'/Public'
	),

    'URL_MODEL' => 2,
    'DEFAULT_PARAMS' => array(
        'limit' => 10,
        'p' => 1
    )
);


