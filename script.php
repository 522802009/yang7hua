<?php

$config	=	(object) array(
		'log'	=>	(object) array(
			'filename'	=>	'script.log'		
		),
		'url'	=>	(object) array(
			'getLoanList' => 'http://test3.yiqihao.com/loan/list?format=json',
			'update' => 'http://zxl.local.com/api/update',
		),
		'curl_options' => array(
			CURLOPT_HEADER	=>	false,
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_POST	=>	1,
			CURLOPT_SSL_VERIFYHOST	=>	0,
			CURLOPT_SSL_VERIFYPEER	=>	false
		)
);

function getCurlOpts(array $postData = array())
{
	global $config;
	$config->curl_options[CURLOPT_POSTFIELDS]	=	$postData;	
	return $config->curl_options;
}

function execCurl($url, $options)
{
	$ch = curl_init($url);
	curl_setopt_array($ch, $options);
	$result = curl_exec($ch);
	if(!$result) {
		writelog(curl_error($ch));
	}
	curl_close($ch);
	return $result;
}

function writelog($msg)
{
	global $config;
	if(!file_exists($config->log->filename)) {
		$fo = fopen($config->log->filename, 'w');
	} else if(!is_writable($config->log->filename)) {
		printf("%s is not writable ! \r\n", $config->log->filename);
	} else {
		$fo = fopen($config->log->filename, 'a');
	}
	fwrite($fo, date('Y-m-d H:i : ', time()) . $msg . "\r\n");
	fclose($fo);
}

$options = getCurlOpts(array('p'=>1));
$data = execCurl($config->url->getLoanList, $options);

$data = json_decode($data);
if ($data->return > 0) {
	writelog('获取数据成功');
	$options = getCurlOpts(array(
				'companyid'	=>	1,
				'data'	=>	serialize($data->data->list))
			);
	$result  = execCurl($config->url->update, $options);
	writelog($result);
	echo $result;
} else {
	writelog('获取数据失败: ' . $data->errmsg);
	echo $data->errmsg;
}

//var_dump(json_decode($result));
