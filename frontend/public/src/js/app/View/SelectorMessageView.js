Namespace('App.View', {

	SelectorMessageView:App.View.Abstract.extend({

        html: document.getElementById('T_SelectorMessageView').text,
        rendered: false,
 
		initialize:function(_opt){
            
            this.selectorController = _opt.selectorController;
            this.listenTo(this.selectorController, 'update:breadcrumbs:view', _.bind(this.draw, this));

 			this.$ctr = _opt.$ctr;
 

            this.render();
 		},

        render:function(){

            if(!this.rendered){

                this.$el = $(_.template(this.html, {}));
                this.$ctr.append(this.$el);

            }

        },

        draw:function(_data){

           

            if(this.selectorController.id == "from"){

                if(_data.length == 0){
                    this.$el.html('choose your <span class="orange">machine type</span>');
                    return;
                } else if (_data.length == 1){
                    this.$el.html('choose your <span class="orange">machine No.</span>');
                    return;
                }

            }

            if(this.selectorController.id == "to"){
                if(_data.length == 0){
                    this.$el.html('choose destination <span class="orange">floor</span>');
                    return;
                } else if (_data.length == 1){
                    this.$el.html('choose <span class="orange">destination</span>');
                    return;
                }
            }

            if(_data.length > 0){
                this.$el.html('');
                return;
            }
         
        }
		
	})

});