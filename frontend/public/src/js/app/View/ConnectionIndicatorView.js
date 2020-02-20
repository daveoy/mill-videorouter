Namespace('App.View', {

	ConnectionIndicatorView:App.View.Abstract.extend({

        html: document.getElementById('T_ConnectionIndicatorView').text,
        rendered: false,
 
		initialize:function(_opt){
            
            this.connectionIndicatorController = _opt.connectionIndicatorController;

            this.listenTo(this.connectionIndicatorController, 'update:view:connect', _.bind(this.onConnect, this));
            this.listenTo(this.connectionIndicatorController, 'update:view:disconnect', _.bind(this.onDisconnect, this));

            this.listenTo(this.connectionIndicatorController, 'do:bounce', _.bind(this.bounce, this));

            


 			this.$ctr = _opt.$ctr;
 

            this.render();
 		},

        render:function(){

            if(!this.rendered){

                this.$el = $(_.template(this.html, {}));
                this.$ctr.append(this.$el);

                this.$connected_btn = $('._connected_btn', this.$el);
                this.$not_connected_btn = $('._not_connected_btn', this.$el);

                Hammer(this.$connected_btn[0]).on('tap', _.bind(this.connectionIndicatorController.trigger, this.connectionIndicatorController, 'on:connect:click'));


                

            }

        },

        onConnect:function(){
            this.$connected_btn.addClass('active');
            this.$not_connected_btn.removeClass('active');
        },

        onDisconnect:function(){
            this.$connected_btn.removeClass('active');
            this.$not_connected_btn.addClass('active');
        },


        bounce:function(){


            this.tl = new TimelineLite({onComplete:function(){}});
                this.tl.add(TweenLite.to(this.$connected_btn, 0.4, {scale:1.3, ease:Quad.easeOut}));
                this.tl.add(TweenLite.to(this.$connected_btn, 0.3, {scale:1, ease:Bounce.easeOut}));
            this.tl.play();
                
            


        }
 
		

		
	})

});