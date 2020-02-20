Namespace('App.Router', {

	MainRouter:Backbone.Router.extend({

		routes: {
		//	'login(/)(:_action)': 'login',
		//	'logout(/)(:_action)': 'logout',
			":_cat/:_uid(/)" : 'main',
			'*_action': 'defaultAction'
		},

		start:function(){
			
			//Backbone.history.start({pushState:true, root: site_path});
			Backbone.history.start({pushState:true});
		},



		main:function(_key, _uid){
			var param = {
				key: _key,
				uid: _uid
			};
		//	console.info('router:content', param);
			this.trigger('router:content', param);
		},

		defaultAction: function(_action) {
			if(!_action){
			//	console.info('router:default');
				this.trigger('router:default');
			}else{
			//	console.info('router:404');
				this.trigger('router:404');
			}
			
 		} 





 				// login:function(_action){
		// 	console.info('router:login');
		// 	this.trigger('router:login');
		// },

		// logout:function(_action){
		// 	console.info('router:logout');
		// 	this.trigger('router:logout');
		// },

	})

});