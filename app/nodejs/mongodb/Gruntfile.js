module.exports = function(grunt){
	grunt.initConfig({
		less : {
			options : {
				paths : ['public/less'],
				compress : true,
				yuicompress : true
			},
			//编译	
			css : {
				options : {
					paths : ['public/less']
				},
				files : [
					{dest : 'public/css/adm.css', src: ['public/less/adm.less']},
					{dest : 'public/css/home.css', src: ['public/less/home.less']}
				]
			}
		},
		watch : {
			css : {
				files : ['public/less/*.less'],
				tasks : ['less']
			}
		}
	});
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.registerTask('default', ['less', 'watch']);
}
