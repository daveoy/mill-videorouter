(function(){
	module.exports = {
		localhost: '172.16.4.113',
		
		source:'/Applications/MAMP/htdocs/CRM/CRM/',
		destination:'/usr/local/mill/crm/frontend/branch/yuki/',
		recursive: true,
		syncDest: false,
		compareMode: "checksum",
		exclude: [
		'grunt',
		//'node',
		'node_modules',
		'.git',
		'.',
		'..',
		'.DS_Store' 
		],

		livereloadPort: '55555',
		sassSourceMap:true
 	};
}).call(this);