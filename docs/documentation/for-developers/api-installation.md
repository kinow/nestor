---
layout: page
title: API Installation
---

We use [Dingo API](https://github.com/dingo/api/) for our API. The following is an example configuration for
the [dotEnv](https://github.com/vlucas/phpdotenv) file.

```
APP_ENV=local
APP_DEBUG=true
APP_KEY=ZB4i7ecAVxHkj3mwq57WvmucsGvXpJfO

DB_CONNECTION=sqlite
DB_LOG=true
DB_DATABASE=nestor
DB_HOST=mysql
DB_USERNAME=root
DB_PASSWORD=nosecret

API_STANDARDS_TREE=vnd
API_PREFIX=api
API_VERSION=v1
API_DEBUG=true
API_VENDOR=nestorqa
API_SUBTYPE=nestorqa
API_CONDITIONAL_REQUEST=false
API_STRICT=false
API_DEFAULT_FORMAT=json
#API_DOMAIN=api.nestor-qa.local
```