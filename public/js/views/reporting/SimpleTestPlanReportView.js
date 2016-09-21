define([
    'jquery',
    'underscore',
    'backbone',
    'highcharts',
    'models/reporting/SimpleTestPlanReportModel',
    'text!templates/reporting/simpleTestPlanReportTemplate.html'
], function($, _, Backbone, Highcharts, SimpleTestPlanReportModel, simpleTestPlanReportTemplate) {

    var SimpleTestPlanReportView = Backbone.View.extend({
        el: $("#page"),

        initialize: function(options) {
            _.bindAll(this, 'render', 'render2', 'setTestPlanId');
            this.testPlanId = options.testPlanId;
            this.simpleTestPlanReportModel = new SimpleTestPlanReportModel({
                testPlanId: this.testPlanId
            });
            this.subviews = new Object();
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/reporting"]').parent().addClass('active');
            var self = this;
            $.when(this.simpleTestPlanReportModel.fetch())
                .done(function() {
                    self.render2();
                })
            ;
        },

        render2: function() {
            var report = this.simpleTestPlanReportModel.attributes;

            var data = {
                test_plan_id: this.testPlanId,
                report: report
            };
            var compiledTemplate = _.template(simpleTestPlanReportTemplate, data);
            this.$el.html(compiledTemplate);
        },

        setTestPlanId: function(testPlanId) {
            this.testPlanId = testPlanId;
            this.simpleTestPlanReportModel.testPlanId = testPlanId;
        }

    });

    return SimpleTestPlanReportView;

});
