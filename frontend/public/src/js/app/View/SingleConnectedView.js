Namespace('App.View', {

	SingleConnectedView:App.View.Abstract.extend({

        html: document.getElementById('T_SingleConnectedView').text,
         
		initialize:function(_opt){
 			this.$ctr = _opt.$ctr;
            this.model = _opt.model;
            this.connectedListController = _opt.connectedListController;

            this.render();
  
  		},

        render:function(){


            var data_modified = {};

            if(this.model.attributes.friendly_level.length > 1){
                var str = '';
                _.each(this.model.attributes.friendly_level, _.bind(function(_str, _i){
                    if(_i != 0){
                        str += _str;
                        if(_i != this.model.attributes.friendly_level.length - 1){
                            str += ' ';
                        }

                    }
                }, this));

                data_modified.to_text = str + ' - ' + this.model.attributes.label;
            }else{
                data_modified.to_text = this.model.attributes.label;
            }


            var str = '';
            _.each(this.model.attributes.source_data.level, _.bind(function(_str, _i){
                 str += _str;
                if(_i != this.model.attributes.source_data.level.length - 1){
                    str += ' ';
                }

            }, this));

            if(this.model.attributes.created != null){
                if(this.model.attributes.created == 0){
                    var time = '';
                }else{
                    var time = moment(this.model.attributes.created*1000).fromNow();

                }

            }
            var time_str = time;

            data_modified.from_text = str + ' - ' + this.model.attributes.source_data.label;

            data_modified.time_str = time_str;

            this.$el = $(_.template(this.html, {data: this.model.attributes, data_modified: data_modified}));
            this.$ctr.append(this.$el);

        }
	})
});