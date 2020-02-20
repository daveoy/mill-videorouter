Namespace('App.Controller', (function(){

	return{

		ConnectedListController:App.Controller.Abstract.extend({


            windowFocus: false,

			initialize:function(_opt){

				this.updateTiming = 5000;

 		 		//this.on('start', this.start, this);
 		 		this.on('connected_btn:click', _.bind(this.trigger, this, 'on:connected_btn:click'));

                window.onblur = _.bind(_.debounce(this.onWindowBlur, 100), this);
                window.onfocus = _.bind(_.debounce(this.onWindowFocus, 100), this);

                $('body').focus();

 			},

            onWindowBlur: function(){
                 console.log("onWindowBlur");
                this.windowFocus = false;
                this.clearTimer();
             },

            onWindowFocus: function(){
                 console.log("onWindowFocus");
                this.windowFocus = true;
                this.resumeTimer();
            },



 			resumeTimer:function(){
 				if(this.windowFocus) this.timer = setTimeout(_.bind(this.update, this), this.updateTiming);
 			},

            clearTimer:function(){
                clearTimeout(this.timer);
            },


			update:function(){

                console.log('updating list...');

				var data = {};

				var validate = _.bind(function(){
					if(data.from && data.to){

						var models_to = [];
 						_.each(data.to, function(_m){

							var m = _m.clone();
							var match = _.find(data.from, function(_m_from){return m.get('source') == _m_from.get('port_uid');});
 							if(match != null){
 								m.set('source_data', match.clone().attributes);

 								match.set('destination_data', _m.clone().attributes);
 								_m.set('source_data', match.clone().attributes);

 								if(m.get('source') != 0) models_to.push(m);
 							}

						});
  						this.trigger('update:view', models_to);

  						this.resumeTimer();
					}
				}, this);

				this.once('on:get:from_collection:processed_connected', function(_models){
					data.from = _models;
					validate();
				}, this);


				this.once('on:get:to_collection:processed_connected', function(_models){
					data.to = _models;
					validate();
				}, this);
 				this.trigger('get:connected_collection', _.bind(this.trigger, this, 'on:get:connected_collection'));
			}

		})
	}
})());


















