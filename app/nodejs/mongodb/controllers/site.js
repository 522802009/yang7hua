//var Info = require(global.modelsdir + '/info');
var Info = global.loadModel('info');

exports.index = function(req, res, next){
	Info.getValue('blogname', function(err, value){
		if (err)
			console.log(err);
		res.render('index/index.html', {blogname:value});
	});
};

exports._404 = function(req, res, next){
	res.render('404');
}
