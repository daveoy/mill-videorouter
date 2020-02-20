Namespace('App.Model', {
	MainModel:  App.Model.AbstractModel.extend({

 
		initialize:function(_opt){

			App.Model.AbstractModel.prototype.initialize.call(this);

			var mainController = _opt.mainController;
 	
			this.fromCollection = new App.Model.FromCollection({}, {
				mainController: mainController
			});

			this.toCollection = new App.Model.ToCollection({}, {
				mainController: mainController
			});

			this.connectedCollection = new App.Model.ConnectedCollection({}, {
				mainController: mainController
			});

			this.connectRequest = new App.Model.ConnectRequest({}, {
				mainController: mainController
			});

 		}

 		 


	})
});



 