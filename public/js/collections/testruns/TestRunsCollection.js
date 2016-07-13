define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'models/execution/TestRunModel'
], function($, _, Backbone, app, TestRunModel){
    var TestRunsCollection = Backbone.Collection.extend({
        model: TestRunModel,

        initialize: function(options) {
            _.bindAll(this, 'url', 'parse');
            this.testPlanId = options.testPlanId;
            this.perPage = 0;
            this.currentPage = 0;
            this.lastPage = 0;
            this.nextPageUrl = '';
            this.previousPageUrl = '';
            this.from = 0;
            this.to = 0;
        },

        url: function() {
            return 'api/testplans/' + this.testPlanId + '/executions';
        },

        fetchError: function(collection, response) {
            throw new Error("Executions fetch error");
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
        }

    });
 
    return TestRunsCollection;
});
