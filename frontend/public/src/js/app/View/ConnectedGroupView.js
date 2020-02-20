Namespace('App.View', {

	ConnectedGroupView:App.View.Abstract.extend({

        html: document.getElementById('T_ConnectedGroupView').text,
         
		initialize:function(_opt){
			this.connectedListController = _opt.connectedListController;
			this.label = _opt.label;
			this.models = _opt.models;

 			this.$ctr = _opt.$ctr;
 			
 			this.views = [];
   			
   			this.render();
  
  		},


  		render:function(){


  			this.$el = $(_.template(this.html, {data: {label: this.label}}));
            this.$ctr.append(this.$el);


            var $group_list_ctr = $('._group_list_ctr', this.$el);
            
  			_.each(this.models, _.bind(function(_m){

  				var singleConnectedView = new App.View.SingleConnectedView({
  					connectedListController: this.connectedListController,
  					$ctr: $group_list_ctr,
  					model: _m
  				});

  			}, this));

  		}
	})
});