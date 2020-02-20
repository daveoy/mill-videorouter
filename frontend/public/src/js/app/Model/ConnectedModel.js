Namespace('App.Model', {
	ConnectedModel: Backbone.Model.extend({

 		urlRoot: App.Model.API.connected,
 
		initialize:function(_opt){

  		},

 		getData:function(){

 		},

 		onGetData:function(_r){

 		},

 		onError:function(_r){
 			console.error(_r);
 		}
	})
});



 