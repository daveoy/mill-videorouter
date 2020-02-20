Namespace('App.Model', {
	ConnectRequest: Backbone.Model.extend({

 		requestUrl: App.Model.API.connect,
 
		initialize:function(_attr, _opt){
			
			this.mainController = _opt.mainController;
			this.mainController.on('request:connection', this.connect, this);
	 
			this.options = {
 				url: this.requestUrl,
 				type: 'POST',
 				dataType: 'json',
 				//contentType: "application/json",
 				wait: false,
 				reset: true,
 				error: _.bind(this.onError, this)
 			};
  		},
 
 		
 		connect:function(_param){

  			var fromModel = _param.from;
 			var toModel = _param.to;

 			var req = {
 				input_port: fromModel.get('port_uid'),
 				output_port: toModel.get('id')
 			}

 			console.log(req);
 			this.options.data = req;
  			//this.options.data = JSON.stringify(req);
 			//this.options.data = _param;
 			//this.options.contentType = "application/x-www-form-urlencoded; charset=UTF-8";
 			//this.options.contentType = "application/json";
  			this.options.success = _.bind(this.onConnect, this, _param.success);
 			this.fetchXhr = Backbone.ajax(this.options);

 		},

 		onConnect:function(_callback, _res){
 			console.log(_res);
 			if(_res[0] == 'OK: Setting route completed'){
 				_callback.call(this);
 			}else{

 			}
 		},

 		onError:function(_r){

 		}
	})
});



 