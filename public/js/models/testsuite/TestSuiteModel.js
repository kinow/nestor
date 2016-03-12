define([
    'underscore',
    'backbone',
    'models/core/BaseModel',
], function(_, Backbone, BaseModel) {

    var TestSuiteModel = BaseModel.extend({

        defaults: {
            id: null,
            project_id: 1,
            name: 'No test suite name',
            description: 'No description',
            url: '#/404'
        },

        initialize: function(options) {
            _.bindAll(this, 'url', 'parse');
        },

        url: function() {
            var url = '/api/projects/' + this.get('project_id') + '/testsuites';
            if (this.get('id') != undefined && this.get('id') > 0) {
                url += '/' + this.get('id');
            }
            return url;
        },

        parse: function(obj) {
            if (typeof(obj.test_suite) != 'undefined')
                return obj.test_suite;
            return obj;
        }

    });

    return TestSuiteModel;

});