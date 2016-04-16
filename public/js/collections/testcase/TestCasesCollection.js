define([
    'jquery',
    'underscore',
    'backbone',
    'models/testcase/TestCaseModel'
], function($, _, Backbone, TestCaseModel) {
    var TestCasesCollection = Backbone.Collection.extend({
        model: TestCaseModel,
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

            _.bindAll(this, 'setPage', 'url', 'fetchSuccess', 'fetchError', 'parse');
        },

        setPage: function(page) {
            this.page = page;
        },

        url: function() {
            var url = 'api/testcases';
            if (this.page != null && this.page > 0) {
                url = url + '/?page=' + this.page;
            }
            return url;
        },

        fetchSuccess: function(collection, response) {
            this.models = collection.models;
        },

        fetchError: function(collection, response) {
            throw new Error("Test Cases fetch error");
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

    return TestCasesCollection;
});