Namespace('App.Model', {
	AbstractCollection: Backbone.Collection.extend({
		
		
		initialize:function(){
			this.queue = [];
		},
		
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



 