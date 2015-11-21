// Filename: main.js
require.config({
  paths: {
    jquery: 'libs/jquery/dist/jquery.min',
    underscore: 'libs/underscore-amd/underscore-min',
    backbone: 'libs/backbone-amd/backbone-min',
    templates: '../templates',
    navigation: 'libs/navigation/navigation'
  }
});

require([
  // Load our app module and pass it to our definition function
  'app',
  'router',
  'models/core/SessionModel'
], function(app, AppRouter, SessionModel){

  // Just use GET and POST to support all browsers
  Backbone.emulateHTTP = true;

  // Initialise the application web router
  app.router = new AppRouter();

  // Create a new session model and scope it to the app global
  // This will be a singleton, which other modules can access
  app.session = new SessionModel({});

  app.session.checkAuth({

    complete: function() {

      // HTML5 pushState for URLs without hashbangs
      var hasPushstate = !!(window.history && history.pushState);
      if(hasPushstate) Backbone.history.start({ pushState: true, root: '/' });
      else Backbone.history.start();

    }

  });

  // All navigation that is relative should be passed through the navigate
  // method, to be processed by the router. If the link has a `data-bypass`
  // attribute, bypass the delegation completely.
  // $('#content-app').on("click", "a:not([data-bypass])", function(evt) {
  //     evt.preventDefault();
  //     var href = $(this).attr("href");
  //     app.router.navigate(href, { trigger : true, replace : false });
  //
  // });

});
