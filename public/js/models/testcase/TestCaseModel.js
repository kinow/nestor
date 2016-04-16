define([
    'underscore',
    'backbone',
    'models/core/BaseModel',
], function(_, Backbone, BaseModel) {

    var TestCaseModel = BaseModel.extend({

        defaults: {
            id: null,
            project_id: 0,
            test_suite_id: 0,
            execution_type_id: 0,
            name: 'No test case name',
            description: 'No description',
            prerequisite: 'No prerequisite',
            url: '#/404'
        },

        initialize: function(options) {
            _.bindAll(this, 'url', 'parse');
        },

        url: function() {
            var url = '/api/projects/' + this.get('project_id') + '/testsuites/' + this.get('test_suite_id') + '/testcases';
            if (this.get('id') != undefined && this.get('id') > 0) {
                url += '/' + this.get('id');
            }
            return url;
        },

        parse: function(obj) {
            if (typeof(obj.test_case) != 'undefined')
                return obj.test_case;
            return obj;
        }

    });

    return TestCaseModel;

});