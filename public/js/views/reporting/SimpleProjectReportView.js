define([
    'jquery',
    'underscore',
    'backbone',
    'highcharts',
    'models/reporting/SimpleProjectReportModel',
    'models/core/ExecutionStatusModel',
    'text!templates/reporting/simpleProjectReportTemplate.html'
], function($, _, Backbone, Highcharts, SimpleProjectReportModel, ExecutionStatusModel, simpleProjectReportTemplate) {

    var SimpleProjectReportView = Backbone.View.extend({
        el: $("#page"),

        initialize: function(options) {
            _.bindAll(this, 'render', 'render2', 'setProjectId');
            this.projectId = options.projectId;
            this.simpleProjectReportModel = new SimpleProjectReportModel({
                projectId: this.projectId
            });
            this.executionStatusModel = new ExecutionStatusModel();
            this.subviews = new Object();
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/reporting"]').parent().addClass('active');
            var self = this;
            $.when(this.simpleProjectReportModel.fetch(), this.executionStatusModel.fetch())
                .done(function() {
                    self.render2();
                })
            ;
        },

        render2: function() {
            var report = this.simpleProjectReportModel.attributes;
            var data = {
                project_id: this.projectId,
                report: report
            };
            var compiledTemplate = _.template(simpleProjectReportTemplate, data);
            this.$el.html(compiledTemplate);

            var executionsSummary = report['executions_summary'];

            var arrayKeys = new Array();
            var arrayValues = new Array();

            var executionStatuses = this.executionStatusModel.attributes.execution_statuses;
            var temp = {};
            _.each(executionStatuses, function(es) {
                temp[es['id']] = es['name'];
            });

            for (var key in executionsSummary) {
                var keyName = temp[key];
                arrayKeys.push(keyName);
                arrayValues.push(executionsSummary[key]);
            }

            var dataset = {
                name: 'Total',
                data: arrayValues
            };

            var max = _.max(arrayValues);

            this.$el.find('#simple-project-report-chart').highcharts({
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Executions Summary',
                    align: 'center',
                    x: 0
                },
                credits: {
                    enabled: false
                },
                xAxis: {
                    categories: arrayKeys,
                    labels: {
                        enabled: true
                    }
                },
                yAxis: {
                    allowDecimals: false,
                    title: {
                        text: 'Execution Status Count'
                    },
                    min: 0,
                    max: max
                },
                legend: {
                    enabled: true
                },
                series: [dataset]
            });
        },

        setProjectId: function(projectId) {
            this.projectId = projectId;
            this.simpleProjectReportModel.projectId = projectId;
        }

    });

    return SimpleProjectReportView;

});
