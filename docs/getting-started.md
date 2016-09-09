---
layout: page
title: Getting Started
permalink: /getting-started/
---

With Nestor-QA you will be able to

* Manage test projects
* Work on the specification of test suites and test cases
* Plan your tests
* Execute your test plans

## Managing Projects

Your test projects, or simply projects, will hold test suites and test cases.

![projects screenshot]({{ "/assets/images/docs/getting-started/nestor_mainpage.png" | prepend: site.baseurl }} "Test Projects")

## Test Specification

Test specification involves managing test suites and test cases. A project can contain
several test suites. A test suite, by its turn, can contain other test suites, or test cases.
A test case is a leaf, and has no children nodes.

There are two types of test cases, manual and automated test cases. Its icon varies in the
navigation tree.

![specification screenshot]({{ "/assets/images/docs/getting-started/nestor_specification.png" | prepend: site.baseurl }} "Test Specification")

## Planning

Once you have created a project, and worked on the test specification, you will need
to start planning how your tests will be executed.

This step includes choosing resources for your tests, prerequisites, what to test and what
not to test.

![planning screenshot]({{ "/assets/images/docs/getting-started/nestor_planning.png" | prepend: site.baseurl }} "Planning")

## Execution

Test plans can generate one or many test runs. Each test run contains a complete copy of
the test cases assigned to the test plan.

The execution is simply assigning execution status like *PASSED* or *FAILED* to test cases.

![execution screenshot]({{ "/assets/images/docs/getting-started/nestor_execution.png" | prepend: site.baseurl }} "Test Execution")

You can view the overall status of each test run. This way you can easily compare its execution progress.

![test runs screenshot]({{ "/assets/images/docs/getting-started/nestor_executing.png" | prepend: site.baseurl }} "Test Runs")