---
layout: page
title: Database Basic Entities
description: Basic Entities required in order to have a working system
---

This page describes the basic entities required in order to have a working system. These are created by default during the system installation, and also for running tests. They are not part of the database migrations, but of the database seeding process.

With the current stable version, the default values created are the following.

### Project Statuses

* 1 &mdash; New
* 2 &mdash; Closed

### Execution Types

* 1 &mdash; Manual
* 2 &mdash; Automated

### Navigation Tree node types

* 1 &mdash; Project
* 2 &mdash; Test Case
* 3 &mdash; Test Suite

### Execution Statuses

* 1 &mdash; Not Run
* 2 &mdash; Passed
* 3 &mdash; Failed
* 4 &mdash; Blocked

### Roles

* admin
* guest
* tester
* test_designer
* lead
