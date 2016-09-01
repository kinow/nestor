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

namespace Controllers;

use \TestCase;
use Nestor\Entities\User;
use Nestor\Repositories\UsersRepository;
use Nestor\Http\Controllers\UsersController;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectsControllerTest extends TestCase
{

    use DatabaseTransactions;

    public function testCreateProject()
    {
        $userPayload = [
            'username' => $this->faker->uuid,
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->md5
        ];
        $userPayload['password'] = bcrypt($userPayload['password']);

        $usersRepository = app()->make(\Nestor\Repositories\UsersRepository::class);
        $user = $usersRepository->create($userPayload);

        $payload = [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence(10),
            'project_statuses_id' => $this->faker->numberBetween(1, 10),
            'created_by' => $user['id']
        ];

        $dispatcher = $this->app->make('Dingo\Api\Dispatcher');

        $response = $dispatcher->post('projects', $payload);

        foreach ($payload as $key => $value) {
            $this->assertEquals($payload[$key], $response[$key]);
        }

        $this->assertTrue($response['id'] > 0);
        $this->assertTrue(isset($response['created_at']));
        $this->assertTrue(isset($response['updated_at']));
    }

    public function testCreateUserValidator()
    {
        $payload = [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence(10),
            //'project_statuses_id' => $this->faker->numberBetween(1, 10),
            'created_by' => $this->faker->numberBetween(1, 10)
        ];

        $dispatcher = $this->app->make('Dingo\Api\Dispatcher');

        $this->setExpectedException('\Illuminate\Database\QueryException');
        $dispatcher->post('projects', $payload);
    }
}
