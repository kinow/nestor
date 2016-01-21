// Filename: main.js
require.config({
  paths: {
    jquery: 'libs/jquery/dist/jquery.min',
    underscore: 'libs/underscore-amd/underscore-min',
    backbone: 'libs/backbone-amd/backbone-min',
    templates: '../templates',
    navigation: 'libs/navigation/navigation',
    parsley: 'libs/parsleyjs/dist/parsley.min',
    semanticui: 'libs/semantic/dist/semantic.min'
  },

  shim: {
    'parsley': {
      "deps" : ["jquery"],
      exports: 'parsley'
    },
    'semanticui': {
      "deps" : ["jquery"],
      exports: 'semanticui'
    }
  }
});

require([
  'jquery',
  'semanticui',
  // Load our app module and pass it to our definition function
  'app',
  'router'
], function($, SemanticUI, app, appRouter, SessionModel){
  // Just use GET and POST to support all browsers
  Backbone.emulateHTTP = true;

  // Initialise the application web router
  appRouter.initialize();
  app.router = appRouter;

  //app.session.checkAuth({

    // complete: function() {
    //
    //   // HTML5 pushState for URLs without hashbangs
    //   var hasPushstate = !!(window.history && history.pushState);
    //   if(hasPushstate) {
    //     Backbone.history.start({ pushState: true, root: '/' });
    //   } else {
    //     Backbone.history.start();
    //   }
    //
    // }

  //});

  // All navigation that is relative should be passed through the navigate
  // method, to be processed by the router. If the link has a `data-bypass`
  // attribute, bypass the delegation completely.
  // $('#nestor-app').on("click", "a:not([data-bypass])", function(evt) {
  //     evt.preventDefault();
  //     var href = $(this).attr("href");
  //     console.log(Backbone.history.getFragment());
  //     console.log(href);
  //     console.log(app.router.navigation.routers);
  //     app.router.navigate(href, { trigger : true, replace : false });
  // });

  $('.message .close')
    .on('click', function() {
      $(this)
        .closest('.message')
        .transition('fade')
      ;
    })
  ;

});
