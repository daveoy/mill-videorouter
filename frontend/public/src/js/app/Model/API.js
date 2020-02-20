Namespace('App.Model', {

	API: (function () {

		return {

 			from: env.API_URL + 'router/?input',
 			to: env.API_URL + 'router/?output',
 			connect: env.API_URL + 'router/',
 			connected: env.API_URL + 'router/?list'

		}
	})()
})
