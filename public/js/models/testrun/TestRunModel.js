define([
    'underscore',
    'backbone',
    'models/core/BaseModel',
], function(_, Backbone, BaseModel) {

    var TestRunModel = BaseModel.extend({

        defaults: {
            test_plan_id: 0,
            name: 'No test run name set',
            description: 'No description set'
        },

        initialize: function(options) {
            _.bindAll(this, 'parse', 'url', 'execute');
            this.set('test_plan_id', options.test_plan_id);
        },

        parse: function(obj) {
            if (typeof(obj.test_run) != 'undefined')
                return obj.test_run;
            return obj;
        },

        url: function() {
            var url = '/api/testplans/' + this.get('test_plan_id') + '/testruns';
            var id = this.get('id');
            if (id != null) {
                url += '/' + id;
            }
            return url;
        },

        execute: function(payload, testPlanId, testRunId, testSuiteId, testCaseId) {
            var url = 'api/testplans/' + testPlanId + '/testruns/' + testRunId + '/testsuites/' + testSuiteId + '/testcases/' + testCaseId + '/executions';
            return $.ajax({
                url: url,
                data: payload,
                contentType: 'application/json',
                dataType: 'json',
                type: 'POST',
                beforeSend: function(xhr) {
                    // Set the CSRF Token in the header for security
                    var token = $('meta[name="csrf-token"]').attr('content');
                    if (token) xhr.setRequestHeader('X-CSRF-Token', token);

                    // Set the API version
                    // TODO: get api tree and sub application name from config
                    xhr.setRequestHeader('Accept', 'application/vnd.nestorqa.v1+json');
                }
            });
        }

    });

    return TestRunModel;

});