Namespace('App.Model', {
	FromModel: Backbone.Model.extend({

 		urlRoot: App.Model.API.from,
 
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



 