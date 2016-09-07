---
layout: post
title: Where are we now?
description: Project status and announcements
category: blog
author: Bruno P. Kinoshita
---

Nestor-QA started in 2013, but haven't reached 1.0 yet. In 2013-2014 a lot was done in brainstorming of ideas and stressing out a few possibilities. From that, by the beginning of 2015 we had a 0.1.x version with an almost complete database model.

However, we still have at least three outstanding issues: make the application API first, implement user authentication and authorization, and implement the plug-in API. Furthermore, the development had to stop for a while as I was relocating to another country.

In December 2015 I had some spare time and started a sprint to upgrade to Laravel 5.1 from 4.x. And also used a different approach. Use the existing database model, but write repositories on top of it, with RESTful services as wrappers.

This way, the user interface would always require data to controllers via the RESTful API, and get the data from repositories.

The UI which was just Twig + HTML, is now a mix of Backbone.JS, Blade and HTML. The whole default interface is a single page app, that relies on the RESTful API. The same API now can be exposed to external users, and documented properly.

Right now the user authentication is almost ready, and the next step is implement authorization. Then we can write a simple API documentation and experiment with it for a while to confirm users could use it to integrate other tools or extend the system.

## So what's happening next?

Hopefully 0.2 will be ready before June. Then we can focus on what is needed for a 0.9 by December, and then plan a 1.0 release in 2017.

## How you can help

If you know a little bit about web programming, in special PHP or any similar language, you can jump in and start sending code to nestorqa/nestor in GitHub. Otherwise you can join the project by sharing ideas, commenting on issues and reviewing what has been done.





