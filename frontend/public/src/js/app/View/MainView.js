Namespace('App.View', {

	MainView:App.View.Abstract.extend({

        html: document.getElementById('T_MainView').text,
        rendered: false,
 
		initialize:function(_opt){
            
 			this.$ctr = _opt.$ctr;
            this.mainController = _opt.mainController;
            this.mainController.on('on:connected_btn:click', this.onConnectedBtnClick, this);
 

            this.render();
 		},

        render:function(){

            if(!this.rendered){

                this.$el = $(_.template(this.html, {}));
                this.$ctr.append(this.$el);

                var $message_ctr = $('._message_ctr', this.$el);
                var $selection_from_ctr = $('._selection_from_ctr', this.$el);
                var $connection_indicator_ctr = $('._connection_indicator_ctr', this.$el);
                var $selection_to_ctr = $('._selection_to_ctr', this.$el);

                this.$connected_list_ctr = $('._connected_list_ctr', this.$el);
                var $connected_btn = $('._connected_btn', this.$el);
                Hammer($connected_btn[0]).on('tap', _.bind(this.mainController.trigger, this.mainController, 'connected_btn:click'));
                var $list_ctr = $('._list_ctr', this.$el);

                var $overlay = $('._overlay', this.$el);
                Hammer($overlay[0]).on('tap', _.bind(this.onConnectedBtnClick, this));

                var connectionsDropdown = new App.View.ConnectionsDropdownView({
                    $ctr: $list_ctr,
                    connectedListController: this.mainController.connectedListController
                });

                var mainMessage = new App.View.MainMessageView({
                    $ctr: $message_ctr,
                    mainMessageController: this.mainController.mainMessageController
                });

                var selectorViewFrom = new App.View.SelectorView({
                    id: 'from', 
                    $ctr: $selection_from_ctr,
                    selectorController: this.mainController.selectorControllerFrom
                });

                var selectorViewTo = new App.View.SelectorView({
                    id: 'to', 
                    $ctr: $selection_to_ctr,
                    selectorController: this.mainController.selectorControllerTo
                });

                var connectionIndicator = new App.View.ConnectionIndicatorView({
                    $ctr: $connection_indicator_ctr,
                    connectionIndicatorController: this.mainController.connectionIndicatorController
                });



            }

        },

        onConnectedBtnClick:function(){

            if(this.$el.hasClass('open')){
                this.$el.removeClass('open');
            }else{
                this.$el.addClass('open');
            }
        }
 
		

		
	})

});