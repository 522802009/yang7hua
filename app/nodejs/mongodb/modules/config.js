var config = {
	//app
	port : 8000,
	debug : 'development',

	//db
	db_host : 'localhost',
	db_port : 27017,
	db_name : 'myblog',
	db_connect : 'mongodb://localhost:27017/myblog',

	//controllers dir
	controllers_dir : 'controllers',
	models_dir : 'models',
	public_dir : 'public',
	upload_dir : 'public/upload',
	//template
	views : 'views',
	view_engine : 'jade',

};

module.exports = config;
