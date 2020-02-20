;(function(window) {
	
	var app = (function(){
 
		var init = function(){
	 
			var mainController = new App.Controller.MainController();	
 
			var mainModel = new App.Model.MainModel({
				mainController: mainController
			});

			var mainView = new App.View.MainView({
				$ctr: $(document.body),
				mainController: mainController
			});

			mainController.start();
 
		}

		return {
			init: init
		};
	})();
	
 	$(window).on('ready', app.init);

})(window);