(function(){


 	var _ = require('underscore');
	var devServer = 'jenkins@saz-new.mill.co.uk';
	var userConfig = require('../config/userConfig.js');
	var js_src = require('../public/src/js/src.js');
	var tpl_src = require('../public/src/tpl/src.js');

 	module.exports = function(grunt){

		var tasks = grunt.cli.tasks;
		var gruntConfig = {pkg: grunt.file.readJSON('package.json')};

		userConfig.livereloadPort = (userConfig.livereloadPort) ? userConfig.livereloadPort : '55555';

		//grunt.loadNpmTasks('grunt-rsync');
		grunt.loadNpmTasks('grunt-sass');
 		grunt.loadNpmTasks('grunt-contrib-watch');


 		var Include = require('./lib/include.js');
		var include = new Include(grunt);

	 	if(_.find(tasks, function(t){
	 		return  t == 'build' ||  t == 'build-stage' ||  t == 'build-live';
	 	})){


	 	}

	 	if(_.find(tasks, function(t){
	 		return t == 'build-live'
	 	})){

	 		var Htmlmin = require('./lib/htmlmin.js');
			var htmlmin = new Htmlmin(grunt);

 	 		grunt.loadNpmTasks('grunt-contrib-uglify');
	 	}



 		if(_.find(tasks, function(t){
	 		return  t == 'stage';
	 	})){
 			grunt.loadNpmTasks('grunt-contrib-concat');
 		}



	 	var DEV_TASKS = [
	 		'sass:dev'
	 	];

	 	var DEV_BUILD_TASKS = [
	 		'sass:dev',
	 		'include:dev'
	 	];


	 	var STAGE_TASKS = [
		 	'sass:live',
	 		'step:Are you sure you want to proceed?',
		 	'concat:loader',
		 	'concat:lib',
		 	'concat:app'
	 	];

	 	var STAGE_BUILD_TASKS = [
		 	'sass:live',
 	 		'concat:loader',
		 	'concat:lib',
		 	'concat:app',
		 	'include:stage'
	 	];



	 	var LIVE_BUILD_TASKS = [
		 	'sass:live',
  		 	'uglify:loader',
		 	'uglify:lib',
		 	'uglify:app',
 		 	'htmlmin:live',
		 	'include:live'
 	 	];



 	 	gruntConfig.watch = (function(){

  	 		var scripts = {
  	 			files: [
 	  	 			'../public/src/js/**',
	  	 			'!../public/src/js/lib/**',
 	 	 			'../public/php/**/*.php',
 	 	 			'../public/src/tpl/**/*.html'
  	 			],
  	 			tasks: []
  	 		//	tasks: ['rsync:dev']
  	 		}

  	 		var src = {
  	 			files: ['../public/src/tpl/src.js', '../public/src/js/src.js'],
  	 			tasks: ['include:dev']
  	 		}

  	 		var sass = {
  	 			options: {
  	 				livereload: false
  	 			},
  	 			files: [
  	 			'../public/src/sass/**/*',
  	 			'!../public/src/sass/lib/**'
  	 			],
  	 			tasks: ['sass:dev']
  	 			//tasks: ['sass:dev', 'rsync:dev']
  	 		}

  	 		var css = {
  	 			files: ['../public/dist/css/style.min.css'],
			    tasks: []
  	 		}


  	 		var options = {
	 			livereload: {port: userConfig.livereloadPort},

  	 			interval: 100,
  	 			debounceDelay: 250
  	 		}

 	 		return {
 	 			sass: sass,
 	 			css: css,
 	 			src: src,
 	 			scripts: scripts,
 	 			options: options
  	 		}


   		})();





	 	gruntConfig.concat = (function(){


	 		return {

	 			loader:{
	 				src: _.map(js_src.loader, function(dir){return js_src.srcDir + dir;}),
	 				dest: '../public/dist/js/loader.js'
	 			},

	 			lib: {
	 				src: _.map(js_src.lib, function(dir){return js_src.srcDir + dir;}),
	 				dest: '../public/dist/js/lib.js'
	 			},

	 			app: {
	 				src: _.map(js_src.app, function(dir){return js_src.srcDir + dir;}),
	 				dest: '../public/dist/js/app.js'
	 			}
	 		}

	 	})();

	 	gruntConfig.uglify = (function(){

	 		return {

	 			loader:{
	 				files: {'../public/dist/js/loader.js': _.map(js_src.loader, function(dir){return js_src.srcDir + dir;})}
 	 			},

	 			lib: {
 	 				files: {'../public/dist/js/lib.js': _.map(js_src.lib, function(dir){return js_src.srcDir + dir;})}
	 			},

	 			app: {
 	 				files: {'../public/dist/js/app.js': _.map(js_src.app, function(dir){return js_src.srcDir + dir;})}
	 			}
	 		}

	 	})();


	 	gruntConfig.sass = (function(){


   			return {

   				dev: {
   					options: {
   						outputStyle: 'expanded',
   						sourceMap: userConfig.sassSourceMap
   					},
   					files: {
   						'../public/dist/css/style.min.css': '../public/src/sass/style.scss'
   					}
   				},

   				live: {
   					options: {
   						outputStyle: 'compressed',
   						sourceMap: false
   					},
   					files: {
   						'../public/dist/css/style.min.css': '../public/src/sass/style.scss'
   					}
   				}
   			}

	 	})();



	 	gruntConfig.include = (function(){

	 		var localhost;

	 		if(userConfig.localhost != null && userConfig.localhost != ''){
	 			localhost = userConfig.localhost;
	 		}else{
	 			localhost = '127.0.0.1';

	 		}

	 		var head_html = grunt.file.read('../public/src/head.html');


 	 		var dev_js = _.union(
 	 			js_src.lib,
 	 			js_src.app
 	 			//['http://' + localhost + ':' + userConfig.livereloadPort + '/livereload.js?snipver=1']
 	 			//['http://api.ncrm.the-mill.com:8081/socket.io/socket.io.js']
 	 			);

 	 		var live_js = _.union(
 	 			['dist/js/loader.js']
 	 			);

 	 		var head_dest = '../public/dist/tpl/head.php';


 	 		var dev_echos = (function(){

 	 			var tpls = _.map(tpl_src.tpl, function(ob){ return {id: ob.id, dir:tpl_src.srcDir + ob.dir};});

 	 			echos = [
 	 			'<?php ',
 	 			'require DIR_BASE."/php/views/UnderscoreTemplateOutput.php";',
 	 			'$utu = new UnderscoreTemplateOutput(DIR_BASE);'
 	 			];
 	 			_.each(tpls, function(ob){
 	 				echos.push('echo $utu->getTemplates("' + ob.id + '", "' + ob.dir + '");');
 	 			});
 	 			echos.push(' ?>');

 	 			echos.push('<meta id="viewport" name="viewport" content ="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>');

 	 			echos.push('<meta name="apple-mobile-web-app-capable" content="yes" />');
 	 			//echos.push('<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />');
 	 			echos.push('<meta name="apple-mobile-web-app-status-bar-style" content="black" />');
 	 			//echos.push('<meta name="apple-mobile-web-app-status-bar-style" content="default" />');



 	 			return echos;

 	 		})();

 	 		var live_echos = (function(){

 	 			var tpls = _.map(tpl_src.tpl, function(ob){ return {id: ob.id, dir:tpl_src.distDir + ob.dir};});

 	 			echos = [
 	 			'<?php ',
 	 			'require DIR_BASE."/php/views/UnderscoreTemplateOutput.php";',
 	 			'$utu = new UnderscoreTemplateOutput(DIR_BASE);'
 	 			];
 	 			_.each(tpls, function(ob){
 	 				echos.push('echo $utu->getTemplates("' + ob.id + '", "' + ob.dir.replace(/(\.[\w\d_-]+)$/i, '.min$1') + '", true);');
 	 			});
 	 			echos.push(' ?>');


 	 			echos.push('<meta id="viewport" name="viewport" content ="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>');
 	 			echos.push('<meta name="apple-mobile-web-app-capable" content="yes" />');
 	 			echos.push('<meta name="apple-mobile-web-app-status-bar-style" content="black" />');

 	 			return echos;

 	 		})();

 	 		var dev_css_src;

 	 		if(userConfig.sassSourceMap){
 	 			dev_css_src = [
   	 			'dist/css/style.min.css',
 	 			'dist/css/style.min.css.map'
 	 			];
 	 		}else{
 	 			dev_css_src = [
 	 			'dist/css/style.min.css'
 	 			];
 	 		}

 	 		live_css_src = [
  	 			'dist/css/style.min.css'
 	 		];




		 	return {
		 		dev: {
		 			options: {
		 				include: head_dest
		 			},
		 			js: [{
		 				expand: true,
 		 				src: dev_js
		 			}],
		 			css: [{
		 				src: dev_css_src
		 				//src: ['/dist/css/style.min.css']
		 			}],

		 			html: head_html,

		 			custom: dev_echos
		 		},

		 		stage: {
		 			options: {
		 				include: head_dest
		 			},
		 			js: [{
		 				expand: true,
 		 				src: live_js
 		 			}],
		 			css: [{
		 				src: dev_css_src
		 				//src: ['/dist/css/style.min.css']
		 			}],

		 			html: head_html,

		 			custom: live_echos
		 		},

		 		live: {
		 			options: {
		 				include: head_dest
		 			},
		 			js: [{
		 				expand: true,
 		 				src: live_js
 		 			}],
		 			css: [{
		 				src: live_css_src
		 			}],

		 			html: head_html,

		 			custom: live_echos
		 		}
		 	}
	 	})();

	 	gruntConfig.htmlmin = (function(){


	 		var files = (function(){

	 			var r = [];
	 			_.each(tpl_src.tpl, function(tp){
	 				r.push({
	 					src: tpl_src.baseDir + tpl_src.srcDir + tp.dir,
	 					dist: tpl_src.baseDir + tpl_src.distDir + tp.dir.replace(/(\.[\w\d_-]+)$/i, '.min$1'),
	 				});
	 			});

	 			return r;
	 		})();


	 		return {
	 			live: {
	 				options: {
	 					removeComments: true,
	 					collapseWhitespace: true,
	 					removeOptionalTags: true
	 				},
	 				files: files
	 			}
	 		}
	 	})();




   		gruntConfig.rsync = (function(){
   			var exclude;
   			var defaultExclude = [
   			'grunt',
			'node',
			'node_modules',
			'.git',
			'.',
			'..',
			'.DS_Store' ];

			exclude = (!userConfig.exclude) ? defaultExclude : userConfig.exclude;

   			return {
   				dev: {
   					src: userConfig.source,
   					dest: userConfig.destination,
   					host: devServer,
   					recursive: userConfig.recursive,
   					syncDest: userConfig.syncDest,
   					compareMode: userConfig.compareMode,
   					args: [],
   					exclude: exclude
   				}

   			}

   		})();







		gruntConfig.step = (function(){

			var Step = require('./lib/step.js');
			var step = new Step(grunt);

			return {
				options: {
					option: false
				}
			}

   		})();






    	grunt.initConfig(gruntConfig);
    	grunt.registerTask('default', DEV_TASKS);
		grunt.registerTask('build', DEV_BUILD_TASKS);
     	grunt.registerTask('stage', STAGE_TASKS);
    	grunt.registerTask('build-stage', STAGE_BUILD_TASKS);
   		grunt.registerTask('build-live', LIVE_BUILD_TASKS);



	};


}).call(this);
