---
layout: post
title: RequireJS, Backbone.js, JWT and much more
description: RequireJS, Backbone.js, JWT and much more blog post
category: blog
author: Bruno P. Kinoshita
---

The initial design of Nestor-QA had in mind to do something similar to what Bugzilla did: an
API-first application. API-first applications are software that apply a strategy of building
the API first, and then build the rest of the software around, or on top of it.

![API example image](/assets/posts/2016-06-19-requirejs-backbone-jwt-and-much-more/IMG_20160221_164338.jpg)

We also want to [implement plug-ins](https://github.com/nestor-qa/nestor/issues/2) for Nestor-QA in the future.
The plug-ins API must enable users to customize the layout and theme as well. So we decided to build
the web application with JavaScript.

What we have at the moment is a JavaScript client, that connects to a backend PHP API. The API
was created using [Dingo/API](https://github.com/dingo/api/) Laravel library, which also supports
JWT for authentication. The application interface is being built with
[Backbone.js](http://backbonejs.org/).

Building a JavaScript application, specially one that will have so many screens as a test management
software, can generate many screens, require many files, and views, models, collections, etc, in
Backbone.js.

For that, we are building the software with [RequireJS](http://requirejs.org/). This way we are
being able to organise the code in modules. There are already tests in PHP, and now we will start
writing JavaScript tests too. It looks like using RequireJS may also help us writing tests for
JavaScript, since we can inject modules, and isolate the tests per functionality.

That's all for now. There are still many issues open, and a lot to be done before the next release
of Nestor-QA. So keep an eye open for more awesome stuff :-)

