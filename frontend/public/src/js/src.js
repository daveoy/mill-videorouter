module.exports = {


	srcDir: '../public/',

	loader:[
		'src/js/lib/basket/0.4.0/dist/basket.full.min.js',
		'src/js/loader.js'
	], 

	lib:[

		'src/js/lib/modernizr/2.7.1/modernizr.custom.79300.js',
		'src/js/lib/jquery/2.1.0/jquery-2.1.0.js',
		'src/js/lib/underscore/1.6.0/underscore.js',
		'src/js/lib/backbone/1.1.2/backbone.js',
		//'src/js/lib/backbone/plugins/domStorage/1.0.0/backbone.domStorage.js',
		//'src/js/lib/cookies/0.3.1/cookies.js',
		//'src/js/lib/crypto/3.1.2/rollups/md5.js',
 		'src/js/lib/hammer/1.0.6/hammer.js',
		'src/js/lib/namespace/Namespace.js',
		'src/js/lib/greensock/1.11.8/src/minified/TweenLite.min.js',
		'src/js/lib/greensock/1.11.8/src/minified/plugins/CSSPlugin.min.js',
		'src/js/lib/greensock/1.11.8/src/uncompressed/TimelineLite.js',
		'src/js/lib/greensock/1.11.8/src/uncompressed/easing/EasePack.js',
		//'src/js/lib/greensock/1.11.8/src/uncompressed/utils/Draggable.js',
		//'src/js/lib/kinetic/5.1.0/kinetic-v5.1.0.min-2.js'

		'src/js/lib/moment/2.8.1/moment-with-locales.js',

		'src/js/lib/wordmath/wordMath.vanilla.js'
 	],

	app:[


		// Controllers /////////////////////////////////
 		'src/js/app/Controller/Abstract.js',
		'src/js/app/Controller/MainController.js',
		'src/js/app/Controller/SelectorController.js',
		'src/js/app/Controller/ConnectionIndicatorController.js',
		'src/js/app/Controller/MainMessageController.js',
		'src/js/app/Controller/ConnectedListController.js',

		// Facade /////////////////////////////////////
		'src/js/app/Facade/ArcLoadingGraphic.js',
 		
 		// Factory /////////////////////////////////////
		'src/js/app/Factory/String.js',
 
		// Models /////////////////////////////////////
		'src/js/app/Model/API.js',
		'src/js/app/Model/AbstractModel.js',
		'src/js/app/Model/AbstractCollection.js',
		'src/js/app/Model/MainModel.js',


		'src/js/app/Model/FromModel.js',
		'src/js/app/Model/FromCollection.js',

		'src/js/app/Model/ToModel.js',
		'src/js/app/Model/ToCollection.js',

		'src/js/app/Model/ConnectRequest.js',

		'src/js/app/Model/ConnectedModel.js',
		'src/js/app/Model/ConnectedCollection.js',
		

		// Views /////////////////////////////////////.
		'src/js/app/View/Abstract.js',
		'src/js/app/View/MainView.js',
		'src/js/app/View/SelectorView.js',
		'src/js/app/View/MainMessageView.js',
		'src/js/app/View/ConnectionsDropdownView.js',
		'src/js/app/View/ConnectedGroupView.js',
		'src/js/app/View/SingleConnectedView.js',
		'src/js/app/View/ConnectionIndicatorView.js',
 		'src/js/app/View/SelectorMessageView.js',
		'src/js/app/View/SingleBtnView.js',
		'src/js/app/View/SingleItemView.js',
		'src/js/app/View/BreadcrumbsView.js',
		'src/js/app/app.js',
 
	]
};

