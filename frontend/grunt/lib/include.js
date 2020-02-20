'use strict';

module.exports = function (grunt) {
    


    var _ = require('../node_modules/grunt/node_modules/lodash/lodash.js');
    var path = require('path');

    function gcroot(a, b) {
    	var partsA = a.split(path.sep);
    	var partsB = b.split(path.sep);
    	var root = '';
    	var possibleRoot;

    	while ((possibleRoot = partsA.shift()) === partsB.shift()) {
    		root = path.join(possibleRoot, path.sep);
    	}

    	return root;
    }


    grunt.registerMultiTask('include', 'Include script files individually for debugging.', function () {
        grunt.config.requires('include');

        var options = this.options({
            separator: grunt.util.linefeed,
            attributes: ''
        });

        var output = [];

        this.data.custom.forEach(function (_str) {
            output.push(_.compact([_str]).join(' '));

        });
        

        this.data.css.forEach(function (file) {
        	file.src.forEach(function (path){
        		output.push(_.compact(['<link href="' + path + '" rel="stylesheet"/>']).join(' '));
			});
    	});
    	
        this.data.js.forEach(function (file) {
        	file.src.forEach(function (path){
        		output.push(_.compact(['<script', options.attributes, 'src="' + path + '"></script>']).join(' '));
			});
    	});

    	
        output.push(this.data.html);
        


        if (output.length < 1) {
            grunt.log.warn('Destination not written because compiled files were empty.');
        } else {
            grunt.file.write(options.include, output.join(grunt.util.normalizelf(options.separator)));
            grunt.log.writeln('File "' + options.include + '" created.');
        }

    });
}