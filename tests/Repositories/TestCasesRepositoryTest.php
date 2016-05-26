<?php
/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Bruno P. Kinoshita, Peter Florijn
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Repositories;

use \TestCase;
use Nestor\Entities\TestCases;
use Nestor\Repositories\TestCasesRepository;
use Nestor\Repositories\ProjectsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestCasesRepositoryTest extends TestCase
{

    use DatabaseTransactions;

    public function testRepositoryModelClass() {
        $testCaseRepository = $this->app->make(\Nestor\Repositories\TestCasesRepository::class);
        $this->assertEquals(TestCases::class, $testCaseRepository->model());
    }

    public function testCreateShouldNotBeUsed() {
        $testCaseRepository = $this->app->make(\Nestor\Repositories\TestCasesRepository::class);
        $this->setExpectedException('\Exception');
        $testCaseRepository->create([]);
    }

    public function testCreateTestCaseWithAncestor() {
        $testCasePayload = [
            'project_id' => $this->faker->numberBetween(1, 1000),
            'test_suite_id' => $this->faker->numberBetween(1, 1000)
        ];

        $testCaseVersionPayload = [
            'name' => $this->faker->name, 
            'description' => $this->faker->sentence(3),
            'prerequisite' => $this->faker->sentence(5),
            'version' => $this->faker->numberBetween(1, 10),
            'execution_type_id' => $this->faker->numberBetween(1, 5),
            'test_case_id' => $this->faker->numberBetween(1, 5)
        ];

        $testCaseRepository = app()->make(\Nestor\Repositories\TestCasesRepository::class);
        $testCase = $testCaseRepository->createWithAncestor($testCasePayload, $testCaseVersionPayload, '1-1');

        $this->assertTrue($testCase['id'] > 0);
        foreach ($testCasePayload as $key => $value) {
            $this->assertEquals($testCasePayload[$key], $testCase[$key]);
        }
    }

    public function testUpdateTestCase() {
        $testCasePayload = [
            'project_id' => $this->faker->numberBetween(1, 1000),
            'test_suite_id' => $this->faker->numberBetween(1, 1000)
        ];

        $testCaseVersionPayload = [
            'name' => $this->faker->name, 
            'description' => $this->faker->sentence(3),
            'prerequisite' => $this->faker->sentence(5),
            'version' => $this->faker->numberBetween(1, 10),
            'execution_type_id' => $this->faker->numberBetween(1, 2)
        ];

        $testCaseRepository = app()->make(\Nestor\Repositories\TestCasesRepository::class);
        $testCase = $testCaseRepository->createWithAncestor($testCasePayload, $testCaseVersionPayload, '1-1');

        $this->assertTrue($testCase['id'] > 0);
        
        $testCaseVersionPayload['name'] = 'Updated name';
        $testCaseVersionPayload['test_case_id'] = $testCase['id'];
        $testCaseUpdated = $testCaseRepository->updateWithAncestor($testCaseVersionPayload, $testCase['id']);

        foreach ($testCasePayload as $key => $value) {
            $this->assertEquals($testCasePayload[$key], $testCaseUpdated[$key]);
        }

        foreach ($testCaseVersionPayload as $key => $value) {
            if (strcmp("name", $key) !== 0 && strcmp("version", $key) !== 0)
                $this->assertEquals($testCaseVersionPayload[$key], $testCaseUpdated->version[$key]);
            else if (strcmp("name", $key) === 0)
                $this->assertEquals('Updated name', $testCaseUpdated->version['name']);
            else if (strcmp("version", $key) === 0)
                $this->assertEquals($testCaseVersionPayload[$key]+1, $testCaseUpdated->version['version']);
        }
    }

    public function testDeleteTestCase() {
        $testCasePayload = [
            'project_id' => $this->faker->numberBetween(1, 1000),
            'test_suite_id' => $this->faker->numberBetween(1, 1000)
        ];

        $testCaseVersionPayload = [
            'name' => $this->faker->name, 
            'description' => $this->faker->sentence(3),
            'prerequisite' => $this->faker->sentence(5),
            'version' => $this->faker->numberBetween(1, 10),
            'execution_type_id' => $this->faker->numberBetween(1, 2),
            'test_case_id' => $this->faker->numberBetween(1, 5)
        ];

        $testCaseRepository = app()->make(\Nestor\Repositories\TestCasesRepository::class);
        $testCase = $testCaseRepository->createWithAncestor($testCasePayload, $testCaseVersionPayload, '1-1');

        $this->assertTrue($testCase['id'] > 0);
        
        $r = $testCaseRepository->delete($testCase['id']);

        $this->assertTrue($r > 0);
    }

    public function testRelationshipProject() {
        $payload = [
            'name' => $this->faker->name, 
            'description' => $this->faker->sentence(3),
            'created_by' => $this->faker->word,
            'project_statuses_id' => $this->faker->numberBetween(1, 1000)
        ];

        $projectRepository = app()->make(\Nestor\Repositories\ProjectsRepository::class);
        $project = $projectRepository->create($payload);

        $testCasePayload = [
            'project_id' => $project['id'],
            'test_suite_id' => $this->faker->numberBetween(1, 1000)
        ];

        $testCaseVersionPayload = [
            'name' => $this->faker->name, 
            'description' => $this->faker->sentence(3),
            'prerequisite' => $this->faker->sentence(5),
            'version' => $this->faker->numberBetween(1, 10),
            'execution_type_id' => $this->faker->numberBetween(1, 2),
            'test_case_id' => $this->faker->numberBetween(1, 5)
        ];

        $testCaseRepository = app()->make(\Nestor\Repositories\TestCasesRepository::class);
        $testCase = $testCaseRepository->createWithAncestor($testCasePayload, $testCaseVersionPayload, '1-1');

        $this->assertTrue($testCase['id'] > 0);
        
        $testCaseProject = $testCase->project()->first();

        $this->assertEquals($project->toArray(), $testCaseProject->toArray());
    }

    public function testRelationshipTestSuite() {
        $payload = [
            'name' => $this->faker->name, 
            'description' => $this->faker->sentence(3),
            'created_by' => $this->faker->word,
            'project_id' => $this->faker->numberBetween(1, 1000)
        ];

        $testSuiteRepository = app()->make(\Nestor\Repositories\TestSuitesRepository::class);
        $testSuite = $testSuiteRepository->createWithAncestor($payload, '1-1');

        $testCasePayload = [
            'project_id' => $this->faker->numberBetween(1, 1000),
            'test_suite_id' => $testSuite['id']
        ];

        $testCaseVersionPayload = [
            'name' => $this->faker->name, 
            'description' => $this->faker->sentence(3),
            'prerequisite' => $this->faker->sentence(5),
            'version' => $this->faker->numberBetween(1, 10),
            'execution_type_id' => $this->faker->numberBetween(1, 2),
            'test_case_id' => $this->faker->numberBetween(1, 5)
        ];

        $testCaseRepository = app()->make(\Nestor\Repositories\TestCasesRepository::class);
        $testCase = $testCaseRepository->createWithAncestor($testCasePayload, $testCaseVersionPayload, '1-1');

        $this->assertTrue($testCase['id'] > 0);
        
        $testCaseTestSuite = $testCase->testsuite()->first();

        $this->assertEquals($testSuite->toArray(), $testCaseTestSuite->toArray());
    }

    public function testRelationshipTestCaseVersions() {
        $testCasePayload = [
            'project_id' => $this->faker->numberBetween(1, 1000),
            'test_suite_id' => $this->faker->numberBetween(1, 1000)
        ];

        $testCaseVersionPayload = [
            'name' => $this->faker->uuid, 
            'description' => $this->faker->sentence(3),
            'prerequisite' => $this->faker->sentence(5),
            'version' => $this->faker->numberBetween(1, 10),
            'execution_type_id' => $this->faker->numberBetween(1, 2),
            'test_case_id' => $this->faker->numberBetween(1, 5)
        ];

        $testCaseRepository = app()->make(\Nestor\Repositories\TestCasesRepository::class);
        $testCase = $testCaseRepository->createWithAncestor($testCasePayload, $testCaseVersionPayload, '1-1');

        $this->assertTrue($testCase['id'] > 0);
        
        $testCaseTestCaseVersionsCount = $testCase->testcaseVersions()->count();

        $this->assertTrue($testCaseTestCaseVersionsCount > 0);
    }

    public function testRelationshipTestCase() {
        $testCasePayload = [
            'project_id' => $this->faker->numberBetween(1, 1000),
            'test_suite_id' => $this->faker->numberBetween(1, 1000)
        ];

        $testCaseVersionPayload = [
            'name' => $this->faker->uuid, 
            'description' => $this->faker->sentence(3),
            'prerequisite' => $this->faker->sentence(5),
            'version' => $this->faker->numberBetween(1, 10),
            'execution_type_id' => $this->faker->numberBetween(1, 2),
            'test_case_id' => $this->faker->numberBetween(1, 5)
        ];

        $testCaseRepository = app()->make(\Nestor\Repositories\TestCasesRepository::class);
        $testCase = $testCaseRepository->createWithAncestor($testCasePayload, $testCaseVersionPayload, '1-1');

        $this->assertTrue($testCase['id'] > 0);
        
        $testCaseVersion = $testCase->latestVersion();

        $testCaseVersionTestCase = $testCaseVersion->testcase()->first();
        $this->assertEquals($testCaseVersionTestCase->toArray(), $testCase->toArray());
    }

}
