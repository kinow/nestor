define([
    'underscore',
    'backbone',
    'models/core/BaseModel',
], function(_, Backbone, BaseModel) {

    var SimpleTestPlanReportModel = BaseModel.extend({

        defaults: {
            test_cases: []
        },

        initialize: function(options) {
            _.bindAll(this, 'url');
            this.testPlanId = options.testPlanId;
        },

        url: function() {
            return '/api/reports/simpletestplanreport/testplans/' + this.testPlanId;
        }

    });

    return SimpleTestPlanReportModel;

});