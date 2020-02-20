Namespace('App.Model', {
	ConnectedCollection: Backbone.Collection.extend({

 		url: App.Model.API.connected,
  
        model: App.Model.ConnectedModel,

		initialize:function(_attr, _opt){
            this.mainController = _opt.mainController;
            //this.mainController.on('get:connected_collection', this.getData, this);
            this.mainController.connectedListController.on('get:connected_collection', this.getData, this);

   		},

      
        parse:function(_res){
            var res = [];
            _.each(_res.output, function(_obj, _key_obj){

                _.each(_obj, function(_item, _key_item){

                    var item = {
                        uid: _item.Id,
                        source: _item.Source, 
                        label: _item.Label,
                        floor: _item.Floor,
                        hardware: _item.Hardware,
                        created: _item.Created
                    }

                    res.push(item);
                });

            });

            return res;
        },
 
 		getData:function(_callback){

			this.fetch({
   				success: _.bind(this.onGetData, this, _callback),
 				error: _.bind(this.onError, this)
 			});
 		},
        
 		onGetData:function(_callback){
            // _.each(this.models, function(_m){
            //     console.log(_m.attributes);
            // });
            if(_callback) _callback.call(this, this.models);
 		},

 		onError:function(_r){
 			console.error(_r);
 		}
	})
});



 