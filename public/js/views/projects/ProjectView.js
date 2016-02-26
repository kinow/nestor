define([
    'jquery',
    'underscore',
    'backbone',
    'models/project/ProjectModel',
    'text!templates/projects/projectTemplate.html'
], function($, _, Backbone, ProjectModel, projectTemplate) {

    var ProjectView = Backbone.View.extend({
        el: $("#page"),

        events: {
            'submit form': 'save'
        },

        initialize: function(options) {
            this.id = options.id;
            this.model = new ProjectModel({
                id: this.id
            });
            _.bindAll(this, 'render', 'save');
        },

        render: function() {
            $('.menu a').removeClass('active');
            $('.menu a[href="#/projects"]').addClass('active');
            var self = this;
            this.model.fetch({
                success: function() {
                    var data = {
                        project: self.model,
                        _: _
                    }
                    var compiledTemplate = _.template(projectTemplate, data);
                    self.$el.html(compiledTemplate);
                },
                error: function() {
                    throw new Error("Failed to fetch project");
                }
            });
        },

        save: function(e) {
            e.preventDefault();
            var arr = this.$('form').serializeArray();
            var data = _(arr).reduce(function(acc, field) {
                acc[field.name] = field.value;
                return acc;
            }, {});
            Backbone.history.navigate('#projects', true);
            // this.model.save();
            return false;
        }

    });

    return ProjectView;

});