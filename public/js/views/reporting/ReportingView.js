define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/reporting/reportingTemplate.html'
], function($, _, Backbone, reportingTemplate) {

    var ReportingView = Backbone.View.extend({
        el: $("#page"),

        initialize: function(options) {
            _.bindAll(this, 'render');
            this.subviews = new Object();
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/reporting"]').parent().addClass('active');
            var self = this;

            this.$el.html(reportingTemplate);
        }

    });

    return ReportingView;

});
