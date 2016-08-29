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
use Nestor\Entities\TestRuns;
use Nestor\Repositories\ProjectsRepository;
use Nestor\Repositories\TestPlansRepository;
use Nestor\Repositories\TestRunsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestRunsRepositoryTest extends TestCase
{

    use DatabaseTransactions;

    public function testRepositoryModelClass()
    {
        $repository = $this->app->make(\Nestor\Repositories\TestRunsRepository::class);
        $this->assertEquals(TestRuns::class, $repository->model());
    }

    public function testCreateExecution()
    {
        $projectPayload = [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence(3),
            'created_by' => $this->faker->word,
            'project_statuses_id' => $this->faker->numberBetween(1, 1000)
        ];

        $projectRepository = app()->make(\Nestor\Repositories\ProjectsRepository::class);
        $project = $projectRepository->create($projectPayload);

        $testPlanPayload = [
            'project_id' => $project['id'],
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->sentence(10)
        ];

        $testPlanRepository = app()->make(\Nestor\Repositories\TestPlansRepository::class);
        $testPlan = $testPlanRepository->create($testPlanPayload);

        $testRunPayload = [
            'test_plan_id' => $testPlan['id'],
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->sentence(10)
        ];

        $testRunRepository = app()->make(\Nestor\Repositories\TestRunsRepository::class);
        $testRun = $testRunRepository->create($testRunPayload);

        $this->assertTrue($testRun['id'] > 0);
        foreach ($testRunPayload as $key => $value) {
            $this->assertEquals($testRunPayload[$key], $testRun[$key]);
        }

        $this->assertEquals($testPlan->toArray(), $testRun->testPlan()->first()->toArray());

        $this->assertEquals(0, $testRun->countTestCases());

        $this->assertEquals([], $testRun->executions()->get()->toArray());

        $progress = $testRun->getProgressAttribute();
        $this->assertEquals(0, $progress['percentage']);
    }
}
