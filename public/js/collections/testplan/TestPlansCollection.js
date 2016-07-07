define([
    'jquery',
    'underscore',
    'backbone',
    'models/testplan/TestPlanModel'
], function($, _, Backbone, TestPlanModel){
    var TestPlansCollection = Backbone.Collection.extend({
        model: TestPlanModel,
        models: [],

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

        fetchSuccess: function(collection, response) {
            this.models = collection.models;
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
        }

    });
 
    return TestPlansCollection;
});
