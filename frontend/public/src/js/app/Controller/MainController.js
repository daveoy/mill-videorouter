Namespace('App.Controller', (function(){
 
	return{

		MainController:App.Controller.Abstract.extend({

			initialize:function(){

				this.selectorControllerFrom = new App.Controller.SelectorController({id:"from"});
				this.selectorControllerFrom.on('on:update', this.connectionCheck, this);
 
				this.selectorControllerTo = new App.Controller.SelectorController({id:"to"});
				this.selectorControllerTo.on('on:update', this.connectionCheck, this);

				this.connectionIndicatorController = new App.Controller.ConnectionIndicatorController();
				//this.connectionIndicatorController.on('on:connect', this.onConnect, this);

				this.mainMessageController = new App.Controller.MainMessageController();
 	
				this.connectedListController = new App.Controller.ConnectedListController();


				this.on('connected_btn:click', _.bind(this.connectedListController.trigger, this.connectedListController, 'connected_btn:click'));
				this.on('connected_btn:click', _.bind(this.trigger, this, 'on:connected_btn:click'));

			},

			start:function(){
				
				this.trigger('get:from_collection', _.bind(this.selectorControllerFrom.trigger, this.selectorControllerFrom, 'on:get:from_collection'));
			this.trigger('get:to_collection', _.bind(this.selectorControllerTo.trigger, this.selectorControllerTo, 'on:get:to_collection'));
    			
    			this.connectedListController.trigger('start');
			},

			// onclick connect (to be disscussed)
			/*onConnect:function(){
				if(this.selectorControllerFrom.targetData != null && this.selectorControllerTo.targetData != null){

					this.trigger('request:connection', {
						from: this.selectorControllerFrom.targetData,
						to: this.selectorControllerTo.targetData,
						success: _.bind(this.onConnectSuccess, this)
					});
 
				}
				
			},*/

			onConnectSuccess:function(){

				this.connectionIndicatorController.trigger('bounce');
				this.mainMessageController.trigger('show:connected', {
					from: this.selectorControllerFrom.targetData,
					to: this.selectorControllerTo.targetData
				});
 				//this.selectorControllerTo.trigger('go:home');
			},

			connectionCheck:function(){
			 	 
				if(this.selectorControllerFrom.targetData != null && this.selectorControllerTo.targetData != null){

					this.connectionIndicatorController.trigger('on:connection', {
						from: this.selectorControllerFrom.targetData,
						to: this.selectorControllerTo.targetData
					});

					this.trigger('request:connection', {
						from: this.selectorControllerFrom.targetData,
						to: this.selectorControllerTo.targetData,
						success: _.bind(this.onConnectSuccess, this)
					});
					
					
					this.selectorControllerFrom.trigger('remove:connected:sign');
					this.selectorControllerTo.trigger('remove:connected:sign');


				}else{
					this.connectionIndicatorController.trigger('off:connection');
				}
 			}
			 

 


		})
	}
})());



