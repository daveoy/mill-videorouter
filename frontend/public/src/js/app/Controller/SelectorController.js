Namespace('App.Controller', (function(){
 
	return{

		SelectorController:App.Controller.Abstract.extend({

	 
 
			initialize:function(_opt){

				this.id = _opt.id;

				this.path = [];
				this.data = [];
				this.targetData = null;

 				this.on('on:get:from_collection', this.onGetFromCollection, this);
 				this.on('on:get:to_collection', this.onGetToCollection, this);

 				this.on('on:btn:click', this.onBtnClick, this);
 				this.on('on:path:btn:click', this.onPathBtnClick, this);

 				this.on('go:home', this.goHome, this);


                this.on('remove:connected:sign', _.bind(this.trigger, this, 'on:remove:connected:sign'));
                 
			},

			onGetFromCollection:function(_models){
				var data = this.sortData(_models);
				//console.log(data);
   				this.data = data;
   				this.trigger('render:view', {data: data}, 'in');
   				this.trigger('update:breadcrumbs:view', this.path);
   				this.trigger('on:update');
			},

			onGetToCollection:function(_models){
				//var data = this.sortToData(_models);
				var data = this.sortData(_models);
  				this.data = data;
				this.trigger('render:view', {data: data}, 'in');
				this.trigger('update:breadcrumbs:view', this.path);
				this.trigger('on:update');
			},

			sortData:function(_models){

                var id = this.id;
				 //_.each(_models, function(_m){console.log(_m.attributes);});
  				var array = _.groupBy(_models, function(_m){return _m.get('level')[0];});
                

 				array = _.map(array, function(_arr, _key_0){

                    // _.each(_arr, function(_item){
                    //     console.log(_item.attributes);
                    // });

                    var groupShort = App.Factory.String.removeQuotation(_key_0);
                    var groupFriendly;
                     if(id == 'to'){
                        groupFriendly = App.Factory.String.convertToFriendlyFloorName(_key_0);
                    }else{
                        groupFriendly = _key_0;
                    }
                    




                    if(id == 'to'){

                        var arr = _.groupBy(_arr, function(_m){return _m.get('level')[1] || NaN;});
                        var no_children = _.map(_.values(arr['NaN']), function(_d){return [_d];});
                        delete arr['NaN'];
                        arr = _.map(arr, function(_a){return _a;});
                        arr = _.union(arr, no_children);
                    }else{

                        var arr = _.groupBy(_arr, function(_m){return _m.get('friendly_label') ;});

                    //var no_children = _.map(_.values(arr['NaN']), function(_d){return [_d];});

                    //console.log(arr);

            //      delete arr['NaN'];
                    arr = _.map(arr, function(_a){return _a;});
            //      arr = _.union(arr, no_children);
            



                    }

                    

 
 			 
   					
               


 					arr = _.map(arr, function(_ar){
  						if(_.size(_ar) > 1){

  							return {
 								id: App.Factory.String.convertToSlug(_ar[0].get('level')[1]),
 								short_name: _ar[0].get('level')[1],
 								path_name: _ar[0].get('level')[1],
                                friendly_path_name: _ar[0].get('level')[1],
 								class_name: 'group_' + App.Factory.String.convertToSlug(_ar[0].get('level')[0]),
 								data: _ar
 							};


 						}else{

 							return _ar[0];

 						}
  						

 					});

  					return {
 						id: App.Factory.String.convertToSlug(_key_0.replace(/'/g, '')),
 						short_name: groupShort,
                        path_name: groupShort,
                        friendly_path_name: groupFriendly,
 						class_name: 'group_root',
 						data: arr
 					};
 				});
            

 				
  				return array;
 
 			},

 			

 			onBtnClick:function(_id){
   				var matched_data;
				console.log('this is a test');

 				if(this.path.length == 0){
 					matched_data = _.find(this.data, function(_d){return _d.id == _id;});

                    console.log(matched_data);
 					if(matched_data){
 						this.path = [];
 						this.path.push({key:_id, data:matched_data});

 					}
 				}else{
   					matched_data = _.find(this.path[this.path.length - 1].data.data, function(_d){return _d.id == _id;});
  					if(matched_data){
 						this.path.push({key:_id, data:matched_data});
 					}
 				}


  				if(matched_data){

   					if (matched_data instanceof Backbone.Model){

  						this.updateTarget(matched_data);
 						this.trigger('on:update');
 						this.trigger('update:view:last', matched_data, 'in');

 					}else{

 						this.trigger('on:update');
 						this.trigger('update:view', matched_data, 'in');
 					}

					this.trigger('update:breadcrumbs:view', this.path);

 				}

 			},

 			onPathBtnClick:function(_index){

  
 				this.path.splice(_index, this.path.length);

 				var data = (this.path.length == 0) ? {data: this.data} : this.path[this.path.length - 1].data;
 		 		
 				this.updateTarget();

				this.trigger('update:view', data, 'out');

 				this.trigger('update:breadcrumbs:view', this.path);
 		 
 				this.trigger('on:update');
  			},

  			goHome:function(){
  			//	console.log('goHome');
  				this.onPathBtnClick(0);
  			},

  			updateTarget:function(_data){
  				if(_data){
  					this.targetData = _data;
  				}else{
  					this.targetData = null;
  				}
  				
  			} 

            /*sortToData:function(_models){
                var array = _.groupBy(_models, function(_m){return _m.get('floor').id;});

                array = _.map(array, function(_arr, _key){

                    var arr = _.groupBy(_arr, function(_m){return _m.get('name').id;});

                    arr = _.map(arr, function(_ar, _key){

                        return {
                            id: _ar[0].get('name').id,
                            short_name: _ar[0].get('name').short_name,
                            path_name: _ar[0].get('name').path_name,
                            data: _ar[0]
                        };

                    });

                    return {
                        id: _arr[0].get('floor').id,
                        short_name: _arr[0].get('floor').short_name,
                        path_name: _arr[0].get('floor').path_name,
                        data: arr
                    };
                });
 
                return array;
            },*/



		})
	}
})());


















