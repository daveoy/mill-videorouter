Namespace('App.Factory', {
	String: (function(){
		return {
			convertToSlug:function(_str){
				if(_str != null && _str != ''){
					return _str.toLowerCase().replace(/ /g,'_').replace(/[^\w-]+/g,'');
				}else{
					return '';
				}
			},

			removeQuotation:function(_str){
				return _str.replace(/'/g, '');
			},

			convertToFriendlyFloorName:function(_str){
				var str = _str.replace(/'/g, '').toLowerCase();
				var res;

				switch(str){
					case 'b':
					res = 'Basement';
					break;
					case 'g':
					res = 'Ground Floor';
					break;
					default:
					res = 'Floor ' + this.toTitleCase(wordMath.toString(str));
					break;

				}

				return res;
			},


			toTitleCase: function(str){
				return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
			}

		};
	})()
});



