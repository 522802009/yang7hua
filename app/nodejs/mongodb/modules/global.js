var path = require('path');
var config = require('./config');

//model目录
global.modelsdir = path.join(__dirname, '..', config.models_dir); 

//配置
global.config = config;

//数据库
global.db = require('./db');

//是否ajax请求
global.isAjax = function(req, ifyes, ifnot){
	if (req.param('format') == 'json')
		ifyes()	
	else if (ifnot)
		ifnot()
}

global.loadModel = function(modelName){
	return require(path.join(global.modelsdir, modelName))
}
