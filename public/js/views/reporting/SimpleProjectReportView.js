define([
    'jquery',
    'underscore',
    'backbone',
    'models/reporting/SimpleProjectReportModel',
    'text!templates/reporting/simpleProjectReportTemplate.html'
], function($, _, Backbone, SimpleProjectReportModel, simpleProjectReportTemplate) {

    var SimpleProjectReportView = Backbone.View.extend({
        el: $("#page"),

        initialize: function(options) {
            _.bindAll(this, 'render', 'render2', 'setProjectId');
            this.projectId = options.projectId;
            this.simpleProjectReportModel = new SimpleProjectReportModel({
                projectId: this.projectId
            });
            this.subviews = new Object();
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/reporting"]').parent().addClass('active');
            var self = this;
            $.when(this.simpleProjectReportModel.fetch())
                .done(function() {
                    self.render2();
                })
            ;
        },

        render2: function() {
            var data = {
                project_id: this.projectId,
                report: this.simpleProjectReportModel.attributes
            };
            var compiledTemplate = _.template(simpleProjectReportTemplate, data);
            this.$el.html(compiledTemplate);
        },

        setProjectId: function(projectId) {
            this.projectId = projectId;
            this.simpleProjectReportModel.projectId = projectId;
        }

    });

    return SimpleProjectReportView;

});
