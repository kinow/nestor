define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'models/project/ProjectModel'
], function($, _, Backbone, app, ProjectModel){
    var ProjectsCollection = Backbone.Collection.extend({
        model: ProjectModel,
        
        initialize: function(options){
            _.bindAll(this, 'setPage', 'url', 'fetch', 'parse', 'position');
            this.page = 0;
            this.perPage = 0;
            this.currentPage = 0;
            this.lastPage = 0;
            this.nextPageUrl = '';
            this.previousPageUrl = '';
            this.from = 0;
            this.to = 0;
        },

        // FIXME: remove, and remove URL. Use data {}
        setPage: function(page) {
            this.page = page;
        },

        url: function() {
            return 'api/projects/?page=' + this.page;
        },

        fetchError: function(collection, response) {
            throw new Error("Projects fetch error");
        },

        parse: function(response) {
            this.perPage = response.per_page;
            this.currentPage = response.current_page;
            this.lastPage = response.last_page;
            this.nextPageUrl = response.next_page_url;
            this.previousPageUrl = response.previous_page_url;
            this.from = response.from;
            this.to = response.to;
            return response ? response.data : [];
        },

        position: function(projectId, reload) {
            projectId = parseInt(projectId);
            var url = 'api/projects/' + projectId + '/position';
            $.ajax({
                url: url,
                contentType: 'application/json',
                dataType: 'json',
                type: 'GET',
                beforeSend: function(xhr) {
                    // Set the CSRF Token in the header for security
                    var token = $('meta[name="csrf-token"]').attr('content');
                    if (token) xhr.setRequestHeader('X-CSRF-Token', token);

                    // Set the API version
                    // TODO: get api tree and sub application name from config
                    xhr.setRequestHeader('Accept', 'application/vnd.nestorqa.v1+json');
                },
                success: function(data, textStatus, request) {
                    console.log('Positioned project ' + projectId);
                    if (typeof undefined !== typeof data && typeof undefined !== typeof data['project']) {
                        Backbone.trigger('project:position', [data.project, reload]);
                    } else {
                        // Got here because the data returned is empty, probably un-positioned the project
                        Backbone.trigger('project:position', []);
                        Backbone.history.navigate("#/projects", {
                            trigger: true
                        });
                    }
                },
                error: function(data, textStatus, request) {
                    if (typeof app !== typeof undefined)
                        app.showAlert('Error positioning project', request, 'error');
                    else
                        console.log(request);
                }
            });
        }

    });
 
    return ProjectsCollection;
});
