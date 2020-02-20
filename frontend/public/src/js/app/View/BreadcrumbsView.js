Namespace('App.View', {

	BreadcrumbsView: App.View.Abstract.extend({

        html: document.getElementById('T_BreadcrumbsView').text,
  
		initialize:function(_opt){
            this.selectorController = _opt.selectorController;
             this.listenTo(this.selectorController, 'update:breadcrumbs:view', _.bind(this.draw, this));

            this.$ctr = _opt.$ctr;
            this.render();
   		},

        render:function(){

            this.$el = $(_.template(this.html, {}));
            this.$ctr.append(this.$el);

            this.$path_ctr = $('._path_ctr', this.$el);

            this.$home_btn = $('._home_btn', this.$el);
            Hammer(this.$home_btn[0]).on('tap', _.bind(this.selectorController.trigger, this.selectorController, 'on:path:btn:click', 0));

        },

        draw:function(_path){

            this.$path_ctr.empty();

            if(_.size(_path) == 0){
                this.$home_btn.addClass('active');
            }else{
                this.$home_btn.removeClass('active');
            }
            
            _.each(_path, _.bind(function(_p, _index){
                //console.log(_p);

                var $crumb;
                if (_p.data instanceof Backbone.Model != true){
 
                    $crumb = $('<div>', {'class':'crumb', 'html':_p.data.friendly_path_name.replace(/,/g, '')});
                }else{

                    $crumb = $('<div>', {'class':'crumb', 'html':_p.data.get('label').replace(/,/g, '')});
                }

                
                this.$path_ctr.append($crumb);

                if(_index != _path.length - 1){
                    Hammer($crumb[0]).on('tap', _.bind(this.selectorController.trigger, this.selectorController, 'on:path:btn:click', _index + 1));
                }else{
                    $crumb.addClass('active');
                }

               
            }, this));

        }
 
		

		
	})

});