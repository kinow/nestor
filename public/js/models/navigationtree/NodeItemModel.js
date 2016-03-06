define([
    'underscore',
    'backbone'
], function(_, Backbone) {

    var NodeItemModel = Backbone.Model.extend({

        defaults: {
            id: '',
            length: 0,
            node_id: 0,
            node_type_id: 0,
            created_at: '',
            updated_at: ''
        },

        initialize: function(options) {
            // Here ancestor and descendant will always point to the same node ID, with length=0
            this.id = options.node_id;
            // this.length = options.length;
            // this.node_id = options.node_id;
            // this.node_type_id = options.node_type_id;
            // this.created_at = options.created_at;
            // this.updated_at = options.updated_at;
        },

        url: function() {
            var url = '';
            switch (parseInt(this.get('node_type_id'))) {
                case 1:
                    url = '/#/projects/' + this.get('node_id') + '/view';
                    break;
                case 2:
                    url = 'ttttt';
                    break;
                case 3:
                    url = 'ssss';
                    break;
                default:
                    url = '/';
                    break;
            }
            return url;
        }

    });

    return NodeItemModel;

});