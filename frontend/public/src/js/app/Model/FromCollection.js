Namespace('App.Model', {
	FromCollection: App.Model.AbstractCollection.extend({

 		url: App.Model.API.from,
  
        model: App.Model.FromModel,

		initialize:function(_attr, _opt){

            App.Model.AbstractCollection.prototype.initialize.call(this);
            
            this.loading = false;
            this.loaded = false;

            this.mainController = _opt.mainController;
            this.mainController.on('get:from_collection', this.getData, this);

            this.mainController.connectedListController.on('on:get:connected_collection', this.onGetConnected, this)

   		},

        onGetConnected:function(_connected_models){
            if(!this.loading && this.loaded){
                this.postProcessConnected(_connected_models);
            }else{
                this.addQueue(_.bind(this.postProcessConnected, this, _connected_models));
            }
        },


        postProcessConnected:function(_connected_models){
             _.each(this.models, function(_m){
                var match = _.find(_connected_models, function(_c_m){return _c_m.get('source') == _m.get('id')});
                 if(match != null){
                    _m.set('connected_to', match.get('uid'));
                    _m.set('created', match.get('created'));

                }
              }); 

          this.mainController.connectedListController.trigger('on:get:from_collection:processed_connected', this.models)
  
        },

        createItemObj:function(_ob){
            return _ob;
        },
        

        parse:function(_res){

            var res = [];

            console.log(_res.input);
            _.each(_res.input, _.bind(function(_data_0, _key_0){

                if(!_.isEmpty(_data_0)){

                    _.each(_data_0, _.bind(function(_data_1, _key_1){

                        //console.log(_key_1);

                         if(_.size(_data_1.data) > 1){

                            _.each(_data_1.data, _.bind(function(_data_2){
                                
                                // console.group(_data_2.port_uid);
                                // console.log(_key_0);
                                // console.log(_key_1);
                                // console.log(_data_1);
                                //  console.log(_data_2);
                                // console.groupEnd();
                                  //  console.log(_data_2);

                                //  console.log(_data_2.locked);

                                res.push(this.createItemObj({
                                    id: _key_1 + _data_2.port_uid,
                                    port_uid:_data_2.port_uid,
                                    friendly_label: _key_1,
                                    level: [_key_0, _data_1.label],
                                    label: _data_2.sublevel_label,
                                    locked: _data_2.locked
                                }));

                            }, this));

                        }else{

                            _data_1.data = _.map(_data_1.data, function(_d){return _d;});

                            //console.log(_data_1.data);
                             res.push(this.createItemObj({
                                id: _key_1 + _data_1.data[0].port_uid,
                                port_uid:_data_1.data[0].port_uid,
                                friendly_label: _key_1,
                                short_label: _data_1.label,
                                level: [_key_0],
                                label: _key_1,
                                locked:_data_1.data[0].locked
                             }));

                        }

                    }, this));

                }else{
               



                }

            }, this));  
        
   
            return res;

 
        },
 
 		getData:function(_callback){

            this.loading = true;
            this.loaded = false;



   			this.fetch({
   				success: _.bind(this.onGetData, this, _callback),
 				error: _.bind(this.onError, this)
 			});
 		},
        
 		onGetData:function(_callback, _res){
             this.loading = false;
            this.loaded = true;
            // _.each(this.models, function(_m){
            //     console.log(_m.attributes);
            // });
            this.runQueue();    

             _callback.call(this, this.models);

            
 		},

 		onError:function(_r){
 			console.error(_r);
 		}
	})
});



 