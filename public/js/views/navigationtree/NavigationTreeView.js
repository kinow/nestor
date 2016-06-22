define([
    'jquery',
    'fancytree',
    'underscore',
    'backbone',
    'app',
    'collections/navigationtree/NavigationTreeCollection',
    'views/navigationtree/NavigationTreeView'
], function($, fancytree, _, Backbone, app, NavigationTreeCollection, NavigationTreeView) {

    var NavigationTreeView = Backbone.View.extend({

        initialize: function(options) {
            _.bindAll(this, 'render', 'convertToTree', 'getNodeHref');

            this.projectId = 0;

            if (typeof options !== typeof undefined && typeof options.draggable !== typeof undefined) {
                this.draggable = options.draggable;
            } else {
                this.draggable = false;
            }

            this.collection = new NavigationTreeCollection();
        },

        events: {

        },

        getNodeHref: function(node, parent) {
            var url = '#/projects/' + this.projectId + '/testsuites/';
            if (parseInt(node.node_type_id) === 2) {
                url += node.node_id + '/view';
            } else {
                url += parent.node_id + '/testcases/' + node.node_id + '/view';
            }
            return url;
        },

        getNodeIcon: function(node) {
            if (node != null) {
                if (typeof node.attributes !== typeof undefined && node.attributes !== null) {
                    var attributes = JSON.parse(node.attributes);
                    if (typeof attributes.execution_type_id !== typeof undefined) {
                        if (attributes.execution_type_id === 2) {
                            return '/icons/robot-icon.png';
                        }
                    }
                }
            }
            return true;
        },

        convertToTree: function(parent, children) {
            for (idx in children) {
                var child = children[idx];
                var folder = parseInt(child.node_type_id) === 3 ? false : true;
                var node = {
                    title: child.display_name,
                    key: child.descendant,
                    folder: folder,
                    expanded: true,
                    children: [],
                    node_id: child.node_id,
                    node_type_id: child.node_type_id,
                    href: this.getNodeHref(child, parent),
                    icon: this.getNodeIcon(child)
                };
                parent.children.push(node);
                this.convertToTree(node, child.children);
            }
        },

        /**
         * Used by FancyTree, after a node is moved. This is the sort comparator to organise
         * the nodes within the tree.
         */
        sortCmp: function(a, b) {
            var x = a.data.node_type_id + a.title.toLowerCase(),
                y = b.data.node_type_id + b.title.toLowerCase();
            return x === y ? 0 : x > y ? 1 : -1;
        },

        render: function(options) {
            console.log('Rendering navigation tree for project ID [' + this.projectId + ']');
            var el = options.el;
            var self = this;
            this.collection.setProjectId(this.projectId);
            el.unbind();
            el.empty();
            this.collection.fetch({
                reset: true,
                success: function(results) {
                    var models = self.collection.models;
                    var model = null;
                    if (models.length > 0) {
                        model = models[0].toJSON();
                    }

                    tree = [];
                    var node = {
                        title: model.display_name,
                        key: model.descendant,
                        folder: true,
                        expanded: true,
                        children: [],
                        node_id: model.node_id,
                        node_type_id: model.node_type_id,
                        href: '#/specification'
                    };
                    self.convertToTree(node, model.children);
                    tree.push(node);

                    // enable drag and drop
                    if (self.draggable) {
                        el.fancytree('destroy');
                        el.fancytree({
                            source: tree,
                            extensions: ["dnd"],
                            activeVisible: true, // Make sure, active nodes are visible (expanded).
                            aria: false, // Enable WAI-ARIA support.
                            autoActivate: true, // Automatically activate a node when it is focused (using keys).
                            autoCollapse: false, // Automatically collapse all siblings, when a node is expanded.
                            autoScroll: false, // Automatically scroll nodes into visible area.
                            clickFolderMode: 1, // 1:activate, 2:expand, 3:activate and expand, 4:activate (dblclick expands)
                            checkbox: false, // Show checkboxes.
                            debugLevel: 1, // 0:quiet, 1:normal, 2:debug
                            disabled: false, // Disable control
                            generateIds: false, // Generate id attributes like <span id='fancytree-id-KEY'>
                            idPrefix: "ft_", // Used to generate node id´s like <span id='fancytree-id-<key>'>.
                            icon: true, // Display node icons.
                            keyboard: true, // Support keyboard navigation.
                            keyPathSeparator: "/", // Used by node.getKeyPath() and tree.loadKeyPath().
                            minExpandLevel: 2, // 1: root node is not collapsible
                            selectMode: 1, // 1:single, 2:multi, 3:multi-hier
                            tabindex: 0, // Whole tree behaves as one single control
                            childcounter: {
                                deep: true,
                                hideZeros: true,
                                hideExpanded: true
                            },
                            focus: function(e, data) {
                                var node = data.node;
                                // Auto-activate focused node after 1 second
                                if(node.data.href){
                                    node.scheduleAction("activate", 100000);
                                }
                            },
                            blur: function(e, data) {
                                data.node.scheduleAction("cancel");
                            },
                            activate: function(e, data) {
                                if (data.draggable)
                                    return; // prevent false hits
                                var node = data.node;
                                if(node.data.href){
                                    Backbone.history.navigate(node.data.href, { trigger: true });
                                }
                            },
                            click: function(e, data) { // allow re-loads
                                var node = data.node;
                                if(node.isActive() && node.data.href){
                                    // TODO: data.tree.reactivate();
                                }
                            },
                            dnd: {
                                preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
                                preventRecursiveMoves: true, // Prevent dropping nodes on own descendants
                                autoExpandMS: 400,
                                dragStart: function(node, data) {
                                    // Defines whether this node is draggable or not
                                    // The top level project should have dnd disabled
                                    if (node.data.node_type_id == 1) { 
                                        return false;
                                    }
                                    return true;
                                },
                                dragStop: function(node, data) {
                                    
                                },
                                dragOver: function(node, data) {
                                },
                                dragEnter: function(node, data) {
                                    // Defines whether another node can be dragged on this node
                                    // Return ['before', 'after'] to restrict available hitModes.
                                    //  Any other return value will calc the hitMode from the cursor position.
                                    // Prevent dropping a parent below another parent (only sort
                                    // nodes under the same parent)
                                    // if(node.parent !== data.otherNode.parent){
                                    //   return false;
                                    // }
                                    // Don't allow dropping *over* a node (would create a child)
                                    // return ["before", "after"];

                                    var selectedNode = data.otherNode; // the selected node
                                    if (selectedNode.data.node_type_id == 3) {
                                        // Test cases should not be dnd onto projects or test cases, only test suites
                                        if (node.data.node_type_id != 2) {
                                            return false;
                                        }
                                        // Test cases should not be dnd onto its own parent
                                        if (node == selectedNode.parent) {
                                            return false;
                                        }
                                    }
                                    if (selectedNode.data.node_type_id == 2) {
                                        // Test suites should not be dnd onto test cases
                                        if (node.data.node_type_id == 3) {
                                            return false;
                                        }
                                        // Test suites should not be dnd onto its own parent
                                        if (node == selectedNode.parent) {
                                            return false;
                                        }
                                    }
                                    return "over";
                                },
                                dragDrop: function(node, data) {
                                    var selectedNode = data.otherNode; // the selected node
                                    var descendant = '' + selectedNode.data.node_type_id + '-' + selectedNode.data.node_id;
                                    var ancestor = '' + node.data.node_type_id + '-' + node.data.node_id;
                                    var url = "/api/navigationtree/move";
                                    $.ajax({
                                      type: "POST",
                                      url: url,
                                      data: {descendant: descendant, ancestor: ancestor},
                                      success: function(jqXHR, textStatus, data) {
                                        selectedNode.moveTo(node, jqXHR.hitMode);
                                        var rootNode = node.tree.rootNode;
                                        rootNode.sortChildren(self.sortCmp, true);
                                        app.showAlert('Success!', 'Moved successfully!', 'success');
                                      },
                                      error: function(jqXHR, textStatus, data) {
                                        var responseText = jQuery.parseJSON(jqXHR.responseText);
                                        var message = responseText.error.message;
                                        app.showAlert('Error moving!', message, 'error');
                                      }
                                    });
                                },
                                dragLeave: function(node, data) {
                                }
                            }
                        });
                        // end
                        el.fancytree('getRootNode').sortChildren(self.sortCmp, true);
                    }
                },
                error: function(collection, response, options) {
                    throw new Error("Failed to fetch projects");
                }
            });
        }

    });

    return NavigationTreeView;

});