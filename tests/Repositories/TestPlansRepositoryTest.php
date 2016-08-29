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
use Nestor\Entities\TestPlans;
use Nestor\Repositories\TestPlansRepository;
use Nestor\Repositories\ProjectsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestPlansRepositoryTest extends TestCase
{

    use DatabaseTransactions;

    public function testRepositoryModelClass()
    {
        $repository = $this->app->make(\Nestor\Repositories\TestPlansRepository::class);
        $this->assertEquals(TestPlans::class, $repository->model());
    }

    public function testCreateTestPlan()
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

        $repository = app()->make(\Nestor\Repositories\TestPlansRepository::class);
        $testPlan = $repository->create($testPlanPayload);

        $this->assertTrue($testPlan['id'] > 0);
        foreach ($testPlanPayload as $key => $value) {
            $this->assertEquals($testPlanPayload[$key], $testPlan[$key]);
        }

        $this->assertEquals($project->toArray(), $testPlan->project()->first()->toArray());

        $this->assertEquals([], $testPlan->testcases()->get()->toArray());
    }
}
