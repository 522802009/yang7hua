var config = require('./config');
var path = require('path');
var controllers_dir = path.join(__dirname, '..', config.controllers_dir);

var site = require(controllers_dir + '/site');
var adm = {
	index : require(controllers_dir + '/adm/index'),
	blog : require(controllers_dir + '/adm/blog'),
};

module.exports = function(app) {
	app.get('*', function(req, res, next){
		next();
	})
	app.get('/', site.index)
	//后台管理
	app.get('/adm', adm.index.index)
	app.all('/adm/[a-z]+/[a-z]+(/[\/a-z0-9]+)?', function(req, res, next){
		var params = req.path.slice(5).split('/')
		var controller = params.shift();
		var action = params.shift();
		req._params = params;
		adm[controller][action](req, res, next)
	})
	app.all('/[a-z]+/[a-z]+(/[\/a-z0-9]+)?', function(req, res, next){
		var params = req.path.slice(1).split('/');	
		var controller = params.shift();
		var action = params.shift();
		req._params = params;
		controller = require(controllers_dir + '/' + controller);
		controller[action](req, res, next);
	});

	app.get('*', site._404)
};
