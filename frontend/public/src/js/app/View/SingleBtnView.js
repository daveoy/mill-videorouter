Namespace('App.View', {

	SingleBtnView: App.View.Abstract.extend({

        html: document.getElementById('T_SingleBtnView').text,
        rendered: false,
 
		initialize:function(_opt){
            this.selectorController = _opt.selectorController;
            this.key = _opt.key;
            // this.parentData = _opt.parentData;
            this.data = _opt.data;
            this.groupClass = _opt.groupClass;
            this.btnClass = _opt.btnClass;
  
   		},
        isNumber: function (n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        },

        render:function(){
            var data;
            var data_modified = {};
           // console.log(this.data);
            

            if(this.data instanceof Backbone.Model){
              //  console.log(this.data);
                var text = this.data.get('short_label') || this.data.get('label');
                var text_type = this.isNumber(text);

                var text_arr = text.split(",");
                text_html = '';
                _.each(text_arr, function(_str){
                    text_html += _str + '<br/>';
                });

                data_modified.text = text_html;
                
                 data = {
                    id: this.data.get('id'),
                    short_name: text
                }
            }else{
                
                var text = this.data.short_name;
                var text_type = this.isNumber(text);

                var text_arr = text.split(",");
                text_html = '';
                _.each(text_arr, function(_str){
                    text_html += _str + '<br/>';
                });

                data_modified.text = text_html;

                data = {
                    id: this.data.id,
                    short_name: text
                }
            }

            this.$el = $(_.template(this.html, {data: data, data_modified: data_modified}));
              //console.log(this.parentData);
            // $el.addClass(this.parentData.id);
            

            this.$el.addClass(this.data.id);
            this.$el.addClass(this.groupClass);
            this.$el.addClass(this.btnClass);

            if(text=='B' || text=='G'){
                this.$el.addClass('number');
            } else if(text_type){
                this.$el.addClass('number');
            }else{
                this.$el.addClass('string');
            }
            


 
            if(this.data instanceof Backbone.Model){
                var locked = this.data.get('locked');
                if(!_.isEmpty(locked)){
                    this.$el.addClass('locked');
                }else{
                    Hammer(this.$el[0]).on('tap', _.bind(this.selectorController.trigger, this.selectorController, 'on:btn:click', this.data.id));
                }
            }else{

                var locked = this.checkChildrenlocked(this.data);

                if(locked){
                    this.$el.addClass('locked');
                } else {

                Hammer(this.$el[0]).on('tap', _.bind(this.selectorController.trigger, this.selectorController, 'on:btn:click', this.data.id));
                } 
            }

            

            
            return this.$el;
        },


        checkChildrenlocked:function(_data){

            var unlockedItems = [];

            if(_.isArray(_data.data)){
                _.each(_data.data, _.bind(function(_c_0){
                    if(!(_c_0 instanceof Backbone.Model)){
                        if(_.isArray(_c_0.data)){
                            _.each(_c_0.data, _.bind(function(_c_1){
                                if(!(_c_1 instanceof Backbone.Model)){

                                }else{

                                    var locked = _c_1.get('locked');
                                    if(_.isEmpty(locked)){
                                        //console.log(_c_1);
                                        unlockedItems.push(_c_1);
                                    }
                                 }

                            }, this));
                        }
                    }else{
                        var locked = _c_0.get('locked');
                        if(_.isEmpty(locked)){
                            //console.log(_c_1);
                            unlockedItems.push(_c_0);
                        }

                     }
                }, this));
            }

            if(unlockedItems.length > 0){
                return false;
            }else{
                return true;
            }

        },



        destroy:function(){
            Hammer(this.$el[0]).off('tap');
            this.remove();
        }
 
		

		
	})

});