define([
    'jquery',
    'underscore',
    'backbone',
    'models/testsuite/TestSuiteModel'
], function($, _, Backbone, TestSuiteModel) {
    var TestSuitesCollection = Backbone.Collection.extend({
        model: TestSuiteModel,
        models: [],

        initialize: function(options) {
            this.page = 0;
            this.perPage = 0;
            this.currentPage = 0;
            this.lastPage = 0;
            this.nextPageUrl = '';
            this.previousPageUrl = '';
            this.from = 0;
            this.to = 0;
        },

        setPage: function(page) {
            this.page = page;
        },

        url: function() {
            return 'api/testsuites/?page=' + this.page;
        },

        fetchSuccess: function(collection, response) {
            this.models = collection.models;
        },

        fetchError: function(collection, response) {
            throw new Error("Test Suites fetch error");
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

    return TestSuitesCollection;
});