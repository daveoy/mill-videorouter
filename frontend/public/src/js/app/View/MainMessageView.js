Namespace('App.View', {

	MainMessageView:App.View.Abstract.extend({

        html: document.getElementById('T_MainMessageView').text,
         
		initialize:function(_opt){
            this.mainMessageController = _opt.mainMessageController;
 			this.$ctr = _opt.$ctr;

            this.mainMessageController.on('on:show:connected', this.showOnConnection, this)
 
            this.render();
 
  		},

        render:function(){
            this.$el = $(_.template(this.html, {}));
            this.$ctr.append(this.$el);

            this.$message_type_1 = $('._message_type_1', this.$el);
            TweenLite.set(this.$message_type_1, {autoAlpha:0});

            this.$from_text = $('._from_text', this.$el);
            this.$to_text = $('._to_text', this.$el);


        },

        showOnConnection:function(_param){
 
            if(this.$message_type_1[0]._gsTransform != null && this.$message_type_1[0]._gsTransform.y != -10){
                TweenLite.killTweensOf(this.$message_type_1);
                TweenLite.to(this.$message_type_1, 0.5, {autoAlpha:0, y:-10, onComplete: _.bind(this.showOnConnection, this, _param)});
            }else{

                var from = _param.from;
                var to = _param.to;

                this.$from_text.html(from);
                this.$to_text.html(to);

                TweenLite.killTweensOf(this.$message_type_1);
                TweenLite.set(this.$message_type_1, {y:10});
                TweenLite.to(this.$message_type_1, 0.5, {autoAlpha:1, y:0});
              //  TweenLite.to(this.$message_type_1, 0.5, {delay:5, autoAlpha:0, y:-10});
            }
            


        }
		

		
	})

});