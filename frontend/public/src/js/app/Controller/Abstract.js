Namespace('App.Controller', {
	Abstract: Backbone.View.extend({
		
		queue:[],

		addQueue:function(_fun){
			this.queue.push(_fun);
		},
		runQueue:function(){
			_.each(this.queue, _.bind(function(que){
				que();
			}, this));
			this.queue = [];
		}


 	})
});



 