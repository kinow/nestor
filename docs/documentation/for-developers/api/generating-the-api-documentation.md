---
layout: page
title: Generating the API documentation
---

We use [Dingo API](https://github.com/dingo/api/) for our API. The following command exemplifies how to generate the Blueprint API documentation.

```
php artisan api:docs --name "Nestor-QA RESTful API" -n -v --output-file docs/documentation/api/api.html
```
