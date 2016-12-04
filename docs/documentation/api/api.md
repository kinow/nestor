---
layout: page
title: Nestor-QA RESTful API
---


FORMAT: 1A

# Nestor-QA RESTful API

# Projects [/projects]
Project resource representation.

## Show all projects. [GET /projects{?page}]


+ Request (application/json)
    + Body

            ""

+ Response 200 (application/json)
    + Body

            {
                "total": 2,
                "per_page": 15,
                "current_page": 1,
                "last_page": 1,
                "next_page_url": null,
                "prev_page_url": null,
                "from": 1,
                "to": 15,
                "data": [
                    {
                        "id": 1,
                        "project_statuses_id": "1",
                        "name": "Project A",
                        "description": "# Project A\n\nThis is the **project A**",
                        "created_by": "1",
                        "created_at": "2016-11-12 12:00:56",
                        "updated_at": "2016-11-12 12:00:56"
                    },
                    {
                        "id": 2,
                        "project_statuses_id": "1",
                        "name": "Project B",
                        "description": "# Project B\n\nThis is the **project B**",
                        "created_by": "1",
                        "created_at": "2016-11-12 12:00:56",
                        "updated_at": "2016-11-12 12:00:56"
                    }
                ]
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