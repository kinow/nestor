---
layout: page
title: Getting Started
permalink: /getting-started/
---

With Nestor QA you will be able to

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

![specification screenshot]({{ "/assets/images/docs/getting-started/nestor_specification.png" | prepend: site.baseurl }} "Test Specification")

There are two types of test cases, manual and automated. Its icon varies in the
navigation tree.

![test cases screenshot]({{ "/assets/images/docs/getting-started/nestor_testcase.png" | prepend: site.baseurl }} "Test Cases")

## Planning

After you created an initial project added test suites and test cases in specification section, you will need
to start planning how your tests will be executed.

![planning screenshot]({{ "/assets/images/docs/getting-started/nestor_planning.png" | prepend: site.baseurl }} "Planning")


This step includes choosing resources for your tests, prerequisites, what to test and what
not to test.

![adding test cases screenshot]({{ "/assets/images/docs/getting-started/nestor_managing_testcases.png" | prepend: site.baseurl }} "Managing Test Cases")


## Execution

Test plans can generate one or many test runs. Each test run contains a complete copy of
the test cases assigned to the test plan.

The execution is simply assigning execution status like *PASSED* or *FAILED* to test cases.

![execution screenshot]({{ "/assets/images/docs/getting-started/nestor_execution.png" | prepend: site.baseurl }} "Test Execution")

You can view the overall status of each test run. This way you can easily compare its execution progress.

![test runs screenshot]({{ "/assets/images/docs/getting-started/nestor_executing.png" | prepend: site.baseurl }} "Test Runs")