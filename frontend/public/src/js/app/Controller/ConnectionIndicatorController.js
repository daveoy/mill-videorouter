Namespace('App.Controller', (function(){
 
	return{

		ConnectionIndicatorController:App.Controller.Abstract.extend({
 
			initialize:function(_opt){
 				 this.on('on:connection', this.onConnection, this);
 				 this.on('off:connection', this.offConnection, this);

 				 this.on('on:connect:click', this.onConnectClick, this);

 				 this.on('bounce', _.bind(this.trigger, this, 'do:bounce'))
			},


			onConnection:function(_data){
 				this.trigger('update:view:connect');
			},

			offConnection:function(){
				this.trigger('update:view:disconnect');
			},

			onConnectClick:function(){
				console.log('onConnectClick');
				this.trigger('on:connect');
			}

		})
	}
})());


















