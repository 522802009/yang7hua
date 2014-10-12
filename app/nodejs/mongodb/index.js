var express = require('express');
var app = express();
var bodyParser = require('body-parser');
var config = require('./modules/config');
var path = require('path');

//模板引擎
app.set('views', path.join(__dirname, config.views));
app.set('view engine', config.view_engine);
app.engine('.html', require(config.view_engine).__express);

app.locals.basedir = app.get('views');//模板include的basedir
app.use(express.static(path.join(__dirname, config.public_dir)));//静态资源根目录js,css,images
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({extended:false}));

//上传目录
app.set('upload_dir', path.join(__dirname, config.upload_dir));

//全局变量(方法)设置, 
require('./modules/global');


var router = require('./modules/router');
router(app);

app.listen(config.port);
