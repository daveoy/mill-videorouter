Namespace('App.View', {
	Abstract: Backbone.View.extend({
		

		
		switchView:function(_id, _param){
 			var renderedCount = 0;
			_.each(this.viewSwitcher, _.bind(function(view){
				if(view.rendered){
					renderedCount += 1;
					view.once('on:end', _.bind(this.switchView, this, _id, _param));
					view.trigger('end');
					//this.trigger('view:switching');
				}
			}, this));

			if(renderedCount == 0){
 				_.each(this.viewSwitcher, _.bind(function(view){
					if(view.id == _id){
					//	console.log(_param);
						//view.start(_param);
						view.trigger('start', _param);
						this.trigger('view:switched', _param);
					}
				}, this));
			}
		},

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



 