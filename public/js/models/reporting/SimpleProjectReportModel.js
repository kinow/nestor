define([
    'underscore',
    'backbone',
    'models/core/BaseModel',
], function(_, Backbone, BaseModel) {

    var SimpleProjectReportModel = BaseModel.extend({

        defaults: {
            test_plans_count: 0,
            test_suites_count: 0,
            test_cases_count: 0,
            test_runs_count: 0,
            executions_count: 0,
            executions_summary: []
        },

        initialize: function(options) {
            _.bindAll(this, 'url');
            this.projectId = options.projectId;
        },

        url: function() {
            return '/api/reports/simpleprojectreport/projects/' + this.projectId;
        }

    });

    return SimpleProjectReportModel;

});