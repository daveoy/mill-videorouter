Namespace('App.View', {

	SingleItemView: App.View.Abstract.extend({

        html: document.getElementById('T_SingleItemView').text,
  
		initialize:function(_opt){

            App.View.Abstract.prototype.initialize.call(this);

            this.selectorController = _opt.selectorController;
           // this.listenTo(this.selectorController, '', this.selectorController);
            this.id = _opt.id;
            this.key = _opt.key;
            this.model = _opt.model;


            this.rendered = false;
  
   		},

        render:function(){
            var data = this.model.attributes;
            var data_modified = {};
            data.type = this.id;


            // console.log(this.model.attributes);
            
            if(this.id == 'from'){
                var text_arr = (this.model.attributes.short_label != null) ? this.model.attributes.short_label.split(",") : this.model.attributes.level[this.model.attributes.level.length - 1].split(",");
                text_html = '';
                _.each(text_arr, function(_str){
                    text_html += _str + '<br/>';
                });

                data_modified.text = text_html;
            }
            


            if(this.id == 'to'){

                var level_0 = data.level[0].replace(/'/g, '').toLowerCase();

                if(level_0 == 'b' || level_0 == 'g'){

                    if(level_0 == 'b'){
                        data.title = '';
                        data.short_name_en = 'basement';
                    } 

                    if(level_0 == 'g'){
                        data.title = '';
                        data.short_name_en = 'ground floor';
                    } 


                }else{
                    data.title = 'floor';
                    data.short_name_en = wordMath.toString(data.level[0].replace(/'/g, ''));
                }




                var text_arr = (this.model.attributes.friendly_level[1] != null) ? this.model.attributes.friendly_level[1].split(",") : this.model.attributes.label.split(",");
                text_html = '';
                _.each(text_arr, function(_str){
                    text_html += _str + '<br/>';
                });

                data_modified.text = text_html;

            }

            

 

            this.$el = $(_.template(this.html, {data: this.model.attributes, data_modified: data_modified}));
            this.$el.addClass(this.id);

            if(this.id == 'from'){
                //this.model.on();
            }

            if(this.id == 'to'){

            }

            this.$currently_connected = $('._currently_connected', this.$el);

 
            return this.$el;
            
        },

        init:function(){
            this.runQueue();
            this.rendered = true;
        },

        removeConnectedSign:function(){
            
            if(this.rendered){
                this.$currently_connected.html('');
            }else{
                this.addQueue(_.bind(function(){
                     this.$currently_connected.html('');
                }, this));
            }
 
        }

		

		
	})

});