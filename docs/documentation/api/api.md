----
layout: page
title: API Installation
----


FORMAT: 1A

# Nestor-QA RESTful API

# Projects [/projects]
Project resource representation.

## Show all projects. [GET /projects]


+ Request (application/json)
    + Body

            []

+ Response 200 (application/json)
    + Body

            {
                "id": 1,
                "name": "project name",
                "url": "http://<host>:<port>/<path>",
                "description": "project description"
            }

# Test Plans [/testplans]
Test Plan resource representation.

## Show all test plans. [GET /testplans]


+ Request (application/json)
    + Body

            []

+ Response 200 (application/json)
    + Body

            {
                "id": 1,
                "name": "test plan name",
                "description": "test plan description",
                "project_id": "test plan project ID"
            }