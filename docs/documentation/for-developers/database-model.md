---
layout: page
title: Database Model
---

This document lists topics useful for developers intestered in _building_, 
_debugging_, _customizing_, and _writing plug-ins_ for Nestor.

## Database models and repositories

Even though we are using [Laravel](http://laravel.com/) and [Eloquent](http://laravel.com/docs/eloquent) 
our project uses it slightly different than what you find in similar 
projects.

You will find the Eloquent models under _app/models_. However, controllers don't 
models directly. Instead, they access a repository layer. The repositories 
are located under _app/Nestor/Repositories/_. There you will find interfaces 
and its implementations. 

The intention of this approach is avoid Eloquent dependency for tests.