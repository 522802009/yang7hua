var Info = global.loadModel('info');

exports.index = function(req, res, next){
	Info.getValue('blogname', function(err, value){
		res.render('adm/index/index.html', {blogname:value});
	});
}
