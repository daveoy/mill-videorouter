// extend jquery function to parse serializedArray(s) in objects
$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};


/**
* Collections
*/
// Projects collection
var ProjectsCollection = Backbone.Collection.extend({
    url : '/projects',
});

/**
* Views
*/
var ProjectsView = Backbone.View.extend({
	el: '#content',
	
	// initialize REST call
	initialize: function() {
		this.projectsCollection = new ProjectsCollection();
	},
	
	// bind events
	events: {
        "change #project_selection": "renderEndpoints",
        "change #endpoint_selection": "renderFields"
    },
    
    // render view with projects selection 
    render: function() {
    	var _this = this;
        // get collection
        this.projectsCollection = new ProjectsCollection();
        // fetch models
        this.projectsCollection.fetch({
            // make template with retrived resources
            success: function(projects) {
                
            	// populate template
                var projectsTemplate = '<select id="project_selection" name="project_id">'
                					 + '<option>Select a project</option>';
            	
                $.each(projects.models, function(index, project) {
            		projectsTemplate = projectsTemplate + "<option value='"+index+"'>"+project.get('url')+"</option>";
            	});
                
                projectsTemplate = projectsTemplate + '</select>';
                $('.project_list').html(projectsTemplate);
            },
        });
    },
    renderEndpoints : function() {
    	var _this = this;
    	var projectId = $('#project_selection').val();
    	var endpoints = _this.projectsCollection.models[projectId].get('data');
    	
    	// populate template
        var endpointsTemplate = '<select id="endpoint_selection" name="endpoint_id">'
        					 + '<option>Select an endpoint</option>';
    	
        $.each(endpoints, function(index, endpoint) {
        	endpointsTemplate = endpointsTemplate + "<option value='"+index+"'>"+endpoint.endpoint+"</option>";
    	});
        
        endpointsTemplate = endpointsTemplate + '</select>';
        
    	$('.endpoints_list').html(endpointsTemplate);

    },
    renderFields : function() {
    	var _this = this;
    	var projectId = $('#project_selection').val();
    	var endpoints = _this.projectsCollection.models[projectId].get('data');
    	var endpointId = $('#endpoint_selection').val();
    	console.log('endpointId', endpointId);
    	var fields = endpoints[endpointId];
    	var requestType = endpoints[endpointId].request;
    	console.log('requestType', requestType);
    	console.log('fields', fields);
    	
    	// populate template
            	
    }
});

/**
* Routers
*/
var projectsView = new ProjectsView();
var Router = Backbone.Router.extend({
    routes : {
        '' : 'main'
    }
});

// routing
var router = new Router();
router.on('route:main', function(){
    // render resourcesView
	projectsView.render();
});

Backbone.history.start();