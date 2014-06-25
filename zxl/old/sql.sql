create table if not exists `log`(
   `logid` int not null auto_increment,
   `mid` smallint not null default 0 comment '会员ID',
   `reason` char(12) not null default '' comment '查询原因',
   `realname` varchar(8) not null default '' comment '被查姓名',
   `idcard` char(18) not null default '' comment '被查者身份证',
   `addtime` int(10) not null default 0 comment '查询时间',
   primary key(`logid`),
   key `mid` (`mid`)
)engine=myisam default charset=utf8;