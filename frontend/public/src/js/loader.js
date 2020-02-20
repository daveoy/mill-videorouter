;(function(window){
	'use strict';

	function onSuccess(r){
		console.info('lib.js, app.js loaded from local storage');
	}

	function onError(error){
		console.error(error);
	}


	basket.clear();
	basket.require(
		{ url: 'dist/js/lib.js' },
		{ url: 'dist/js/app.js' } 
	).then(onSuccess, onError);
 
})(window);