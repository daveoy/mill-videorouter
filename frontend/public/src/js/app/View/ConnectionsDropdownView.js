Namespace('App.View', {

	ConnectionsDropdownView:App.View.Abstract.extend({

        html: document.getElementById('T_ConnectionsDropdownView').text,
         
		initialize:function(_opt){
 			this.$ctr = _opt.$ctr;
            this.connectedListController = _opt.connectedListController;

            this.connectedListController.on('update:view', this.update, this);
            
            this.groupViews = [];


            this.render();
 
  		},

        render:function(){
 
            this.$el = $(_.template(this.html, {}));
            this.$ctr.append(this.$el);

            this.$list_ctr = $('._list_ctr', this.$el);

        },


        update:function(_connected_models){
            
            this.$list_ctr.empty();

            var groups = _.groupBy(_connected_models, function(_m){return _m.get('level')[0];});

            _.each(groups, _.bind(function(_group, _key_floor){
                var key_floor = _key_floor.replace(/'/g, '').toLowerCase();
                var floor_title;
                switch(key_floor){
                    case 'b':
                    floor_title = 'Basement';
                    break;
                    case 'g':
                    floor_title = 'Ground Floor';
                    break;
                    default:
                    floor_title = 'Floor ' + wordMath.toString(key_floor);
                    break;
                }

                var connectedGroupView = new App.View.ConnectedGroupView({
                    $ctr: this.$list_ctr,
                    connectedListController: this.connectedListController,
                    label: floor_title,
                    models: _group
                });

                this.groupViews.push(connectedGroupView);
                 
            }, this));

        }	
	})
});





