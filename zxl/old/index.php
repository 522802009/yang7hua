<?php

define('ROOT_PATH', dirname(__FILE__).'/');
define('THINKPATH', './ThinkPHP/');
define('APP_PATH', './App/');

define('APP_DEBUG', true);

define('TMPL_PATH', ROOT_PATH.'Public/tpl/');

hook_path_info();

session_start();
#session_destroy();

require THINKPATH.'ThinkPHP.php';

function hook_path_info()
{
    if (empty($_SERVER['PATH_INFO']) && !empty($_SERVER['REQUEST_URI'])) {
        $pos = strpos($_SERVER['REQUEST_URI'], '?');
        if ($pos === false)
            $_SERVER['PATH_INFO'] = $_SERVER['REQUEST_URI'];
        else
            $_SERVER['PATH_INFO'] = substr($_SERVER['REQUEST_URI'], 0, $pos);
    }
}

