Namespace('App.Controller', (function(){
 
	return{

		MainMessageController:App.Controller.Abstract.extend({
 
			initialize:function(_opt){
 			 	
 			 	this.on('show:connected', this.onShowConnected, this);
			},


			onShowConnected:function(_param){

				_.delay(_.bind(function(){

					console.log(_param);

					// var from = _param.from.attributes;
					// var from_str = from.friendly_label;


					var str = '';
					_.each(_param.from.attributes.level, _.bind(function(_str, _i){
						str += _str;
						if(_i != _param.from.attributes.level.length - 1){
							str += ' ';
						}
 					}, this));

					from_str = str + ' - ' + _param.from.attributes.label;



					var str = '';
					_.each(_param.to.attributes.level, _.bind(function(_str, _i){
						if(_i != 0){
							str += _str;
							if(_i != _param.to.attributes.level.length - 1){
								str += ' ';
							}
						}else{
							str += _param.to.attributes.friendly_level[0] + ' ';
						}
						
 					}, this));

					to_str = str + ' - ' + _param.to.attributes.label;






					// var to = _param.to.attributes;
 				// 	var to_lable = (to.friendly_label != null) ? to.friendly_label : to.label;

 				// 	var to_str = to.title + ' '  + to.short_name_en + ', ' + to_lable;

					//console.log(_param.to.attributes);
	 
					this.trigger('on:show:connected', {
						from: from_str,
						to: to_str
					});
 
				}, this), 300);
				
			}
		})
	}
})());


















