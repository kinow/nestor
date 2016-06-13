define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'text!templates/header/positionProjectComboboxViewTemplate.html'
], function($, _, Backbone, app, positionProjectComboboxViewTemplate) {

    var PositionProjectComboboxView = Backbone.View.extend({

        tagName: "div",

        className: "ui simple dropdown item",

        initialize: function() {
            _.bindAll(this, 'render');
        },

        events: {
            //"click #logout-link": "onLogoutClick"
        },

        render: function() {
            // data to be passed to UI
            var data = {
                
            };
            // render the template
            var compiledTemplate = _.template(positionProjectComboboxViewTemplate, data);

            // update the HTML element of this view
            this.$el.html(compiledTemplate);

            return this;
        }
    });

    return PositionProjectComboboxView;

});