Namespace('App.Model', {
	ToModel: Backbone.Model.extend({

 		urlRoot: App.Model.API.to,
 
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



 