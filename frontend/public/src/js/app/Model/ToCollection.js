Namespace('App.Model', {
	ToCollection: App.Model.AbstractCollection.extend({

 		url: App.Model.API.to,
  
        model: App.Model.ToModel,


		initialize:function(_attr, _opt){

            App.Model.AbstractCollection.prototype.initialize.call(this);


            this.loading = false;
            this.loaded = false;

            this.mainController = _opt.mainController;
            this.mainController.on('get:to_collection', this.getData, this);

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
                var match = _.find(_connected_models, function(_c_m){return _c_m.get('uid') == _m.get('id')});
                 if(match != null){
                    _m.set('source', match.get('source'));
                    _m.set('created', match.get('created'));

                }
            });

            this.mainController.connectedListController.trigger('on:get:to_collection:processed_connected', this.models)

         },

        createItemObj:function(_ob){
 
            return _ob;
        },
        

        parse:function(_res){

            var res = [];

 
            _.each(_res.output, _.bind(function(_data_0, _key_0){

                var floorShort = App.Factory.String.removeQuotation(_key_0);
                var floorFriendly = App.Factory.String.convertToFriendlyFloorName(_key_0);

                if(!_.isEmpty(_data_0)){

                      _.each(_data_0, _.bind(function(_data_1, _key_1){

                        if(_.size(_data_1.data) > 1){

                            _.each(_data_1.data, _.bind(function(_data_2){
                                
                                var label = (_data_2.data != null) ? _data_2.data.label : _key_1;                        
                                

                                // console.group(_data_2.port_uid);
                                // console.log(_key_0);
                                // console.log(_key_1);
                                // console.log(_data_1);
                                // console.log(_data_2);
                                // console.groupEnd();


                                res.push(this.createItemObj({
                                    id: _data_2.port_uid,
                                    port_uid: _data_2.port_uid,
                                    
                                    level: [_key_0, _key_1],
                                     friendly_level: [floorFriendly, _key_1],

                                    label: _data_2.sublevel_label,
                                    friendly_label: _data_1.friendly_label
                                }));

                            }, this));

                        }else{
                            //console.log(_data_1);
                            _data_1.data = _.map(_data_1.data, function(_d){return _d;});

                            //  console.group(_data_1.data[0].port_uid);
                            //  console.log(floorShort);
                            // console.log(floorFriendly);
                            //     console.log(_key_0);
                            //     console.log(_key_1);
                            //     console.log(_data_1);
                            //      console.groupEnd();

                             res.push(this.createItemObj({
                                id: _data_1.data[0].port_uid,
                                port_uid: _data_1.data[0].port_uid,
                                friendly_label: _data_1.friendly_label,
                                level: [_key_0],
                                friendly_level: [floorFriendly],
                                label: _key_1
                             }));

                        }

                    }, this));

                }else{
                  
                }

            }, this));

             return res;

 
        },

       
 		getData:function(_callback, _res){

            this.loading = true;
            this.loaded = false;

   			this.fetch({
   				success: _.bind(this.onGetData, this, _callback),
 				error: _.bind(this.onError, this)
 			});
 		},
        
 		onGetData:function(_callback){
             this.loading = false;
             this.loaded = true;

             this.runQueue();
             _callback.call(this, this.models);
        },

 		onError:function(_r){
 			console.error(_r);
 		}
	})
});



 