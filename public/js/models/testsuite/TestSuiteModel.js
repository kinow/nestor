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
            if (this.get('id') != null) {
                url += '/' + this.get('id');
            } else if (this.id) {
                url += '/' + this.id;
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