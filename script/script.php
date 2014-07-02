<?php

$config	=	(object) array(
		'log'	=>	(object) array(
			'filename'	=>	'script.log'		
		),
		//更新数据的api
		'url'	=>	(object) array(
			'user' => 'http://zxl.local.com/api/add?type=user',
			'loan' => 'http://zxl.local.com/api/add?type=loan',
			'loan_repay' => 'http://zxl.local.com/api/add?type=loanrepay',
			'updaterepay'	=>	'http://zxl.local.com/api/updaterepay'
		),
		'curl_options' => array(
			CURLOPT_HEADER	=>	false,
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_POST	=>	1,
			CURLOPT_SSL_VERIFYHOST	=>	0,
			CURLOPT_SSL_VERIFYPEER	=>	false
		),
		//爬取数据的api
		'api'	=>	array(
			'yiqihao'	=>	array(
				'id'	=>	1,
				'name'	=>	'yiqihao',
				'baseurl'	=>	'http://test3.yiqihao.com',
				'user'	=>	'/user/list?format=json',//获取用户信息
				'loan'	=>	'/loan/list?format=json',//获取贷款信息
				'loan_repay'	=>	'/loan/list?format=json'//获取还款信息
			)
		)
);

function getLog()
{
	$log = file_get_contents('log.txt');
	return unserialize($log);
}
function setLog($key, $val)
{
	$log = getLog();
	$log[$key] = $val;
	file_put_contents('log.txt', serialize($log));
}

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

function execute($url, array $post = array())
{
	$curlOpts = getCurlOpts($post);
	$data = execCurl($url, $curlOpts); //json
	return json_decode($data);
}

$begin = strtotime(date('Y-m-d', strtotime('last day'))); //前一天凌晨
$end = strtotime(date('Y-m-d', strtotime('now'))) - 1;	//当天凌晨
$log = getLog();	//爬行记录
$lastend = $log['end'] ? $log['end'] : 0;	//最后一次查询的时间点

//初次获取所有数据
function getData(array $company, $datatype = 'user')
{
	global $config;
	$logstr = "获取[{$company['name']}]";
	
	switch($datatype){
		case 'user':
			$apiUrl = $company['baseurl'] . $company['user'];
			$updateUrl = $config->url->user;
			$logstr .= '用户数据';
			break;
		case 'loan':
			$apiUrl = $company['baseurl'] . $company['loan'];
			$updateUrl = $config->url->loan;
			$logstr .= '贷款数据';
			break;
		case 'loanrepay':
			$apiUrl = $company['baseurl'] . $company['loanrepay'];
			$updateUrl = $config->url->loanrepay;
			$logstr .= '还款数据';
			break;
	}

	//获取今天凌晨之前所有的数据
	$data  = execute($apiUr, array('end'=>$end));
	
	if($data->return > 0){
		writelog($logstr . '成功');
		$options = array(
					'companyid'	=>	 $company['id'],
					'data'	=>	serialize($data->data->list)
				 );
		$result = execute($updateUrl, $options);
		writelog($result);
		setLog('end', $end); //记录最后一次查询时间点
		echo $result;
	}else{
		writelog($logstr . '失败: ' . $data->errmsg);
		echo $data->errmsg;
	}
}


//按还款时间获取每天已还款的还款数据
function getRepayByDay($company)
{
	global $config;
	$data = execute($company['loanrepay'], 
				array(
					'begin'=>$begin, 
					'end'=>$end
				)
			);	
	$data = json_decode(file_get_contents('repay.txt'));
	//if($data->return > 0){
		$options = array(
						'companyid'	=>	$company['id'],
						'data'	=>	serialize($data->data)
				);
		$result = execute($config->url->updaterepay, $options);
		print_r($result);
	//}
}

$companyname = 'yiqihao';
$company = $config->api[$companyname];

//初次获取
if(!$log['done']){
	//获取所有用户信息	
	getData($company, 'user');
	getData($company, 'loan');
	getData($company, 'loanrepay');
	setLog('done', 1);
	exit();
}else{
	//获取当天新增的数据
	getRepayByDay($company);
}
