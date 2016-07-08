define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'models/testplan/TestPlanModel'
], function($, _, Backbone, app, TestPlanModel){
    var TestPlansCollection = Backbone.Collection.extend({
        model: TestPlanModel,

        url: 'api/testplans',
        
        initialize: function(options){
            this.perPage = 0;
            this.currentPage = 0;
            this.lastPage = 0;
            this.nextPageUrl = '';
            this.previousPageUrl = '';
            this.from = 0;
            this.to = 0;
        },

        fetchError: function(collection, response) {
            throw new Error("Test plans fetch error");
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

        store: function(testPlanId, data) {
            testPlanId = parseInt(testPlanId);
            var url = 'api/testplans/' + testPlanId + '/store';

            var formData = new FormData();
            for (d in data) {
                formData.append(data[d]['name'], data[d]['value']);
            }

            $.ajax({
                url: url,
                contentType: 'application/json',
                dataType: 'json',
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                beforeSend: function(xhr) {
                    // Set the CSRF Token in the header for security
                    var token = $('meta[name="csrf-token"]').attr('content');
                    if (token) xhr.setRequestHeader('X-CSRF-Token', token);

                    // Set the API version
                    // TODO: get api tree and sub application name from config
                    xhr.setRequestHeader('Accept', 'application/vnd.nestorqa.v1+json');
                },
                success: function(d, textStatus, request) {
                    var added = d.attach;
                    var removed = d.detach;
                    var testplan = d.testplan;

                    var message = '' + added.length + ' test cases added and ' + removed.length +  ' test cases removed';
                    app.showAlert('Success!', message, 'success')
                },
                error: function(d, textStatus, request) {
                    app.showAlert('Error adding test cases to test plan', request, 'error');
                }
            });
        }

    });
 
    return TestPlansCollection;
});
