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
use Nestor\Entities\TestSuites;
use Nestor\Repositories\TestSuitesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestSuitesRepositoryTest extends TestCase
{

    use DatabaseTransactions;

    public function testRepositoryModelClass() {
        $testSuiteRepository = $this->app->make(\Nestor\Repositories\TestSuitesRepository::class);
        $this->assertEquals(TestSuites::class, $testSuiteRepository->model());
    }

    public function testCreateShouldNotBeUsed() {
        $testSuiteRepository = $this->app->make(\Nestor\Repositories\TestSuitesRepository::class);
        $this->setExpectedException('\Exception');
        $testSuiteRepository->create([]);
    }

    public function testCreateTestSuiteWithAncestor() {
        $payload = [
            'name' => $this->faker->name, 
            'description' => $this->faker->sentence(3),
            'created_by' => $this->faker->word,
            'project_id' => $this->faker->numberBetween(1, 1000)
        ];

        $testSuiteRepository = app()->make(\Nestor\Repositories\TestSuitesRepository::class);
        $testSuite = $testSuiteRepository->createWithAncestor($payload, '1-1');

        $this->assertTrue($testSuite['id'] > 0);
        foreach ($payload as $key => $value) {
            $this->assertEquals($payload[$key], $testSuite[$key]);
        }
    }

    public function testUpdateTestSuite() {
        $payload = [
            'name' => $this->faker->uuid, 
            'description' => $this->faker->sentence(3),
            'created_by' => $this->faker->word,
            'project_id' => $this->faker->numberBetween(1, 1000)
        ];

        $testSuiteRepository = app()->make(\Nestor\Repositories\TestSuitesRepository::class);
        $testSuite = $testSuiteRepository->createWithAncestor($payload, '1-1');

        $this->assertTrue($testSuite['id'] > 0);
        
        $payload['name'] = 'Updated name';

        $testSuiteUpdated = $testSuiteRepository->update($payload, $testSuite['id']);

        foreach ($payload as $key => $value) {
            if (strcmp("name", $key) !== 0)
                $this->assertEquals($payload[$key], $testSuiteUpdated[$key]);
            else
                $this->assertEquals('Updated name', $testSuiteUpdated['name']);
        }
    }

    public function testDeleteTestSuite() {
        $payload = [
            'name' => $this->faker->uuid, 
            'description' => $this->faker->sentence(3),
            'created_by' => $this->faker->word,
            'project_id' => $this->faker->numberBetween(1, 1000)
        ];

        $testSuiteRepository = app()->make(\Nestor\Repositories\TestSuitesRepository::class);
        $testSuite = $testSuiteRepository->createWithAncestor($payload, '1-1');

        $this->assertTrue($testSuite['id'] > 0);
        
        $r = $testSuiteRepository->delete($testSuite['id']);

        $this->assertTrue($r > 0);
    }

}
