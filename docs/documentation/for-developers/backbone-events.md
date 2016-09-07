---
layout: page
title: Backbone Events
---

The web interface uses several _BackBone.JS javascript events_, documented here for
simplicity.

## List of events

### Event: project:position

#### When it is triggered

Triggered when a new project is positioned in the header. The ProjectsCollection sends
an HTTP request to the back end. Once it is done and successful, the collection triggers
this event.

#### Who is affected

In router.js, there is a global hook. Whenever the event is triggered, and if the
current view is not the view projects view, then the user is redirected to the
specification page.

The ViewProjectView handles that a bit differently. It basically sets the new projectId
in the sub models, collections and views. And then re-renders the page, finally triggering
the method to display the root node of the tree (i.e. the project).

### Event: nestor:navigationtree_changed

#### When it is triggered

Triggered when a node changes in the navigation tree.

#### Who is affected

When, for instance, a new test suite is saved, the NewTestSuiteView.js will trigger
this event. Then the ViewProjectView.js will know that it needs to update its current
navigation tree.
