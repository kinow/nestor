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
use \Mockery;
use \Hash;
use Nestor\Entities\User;
use Nestor\Repositories\UsersRepository;

class UserRepositoryTest extends TestCase
{

    public function testRepositoryModelClass() {
        $repository = $this->app->make('Nestor\Repositories\UsersRepository');
        $this->assertEquals(User::class, $repository->model());
    }

    public function testCreateUser() {
        $payload = [
            'username' => 'mariah',
            'name' => 'Mariah', 
            'email' => 'hsifuh#@llsad.ii.com',
            'password' => '123abc'
        ];
        $payload['password'] = bcrypt($payload['password']);

        $usersRepository = $this->mock(Nestor\Repositories\UsersRepository::class);
        $usersRepository
            ->shouldReceive('create')
            ->with(Mockery::any())
            ->once()
            ->andReturn(factory(User::class)->make($payload));
        $user = $usersRepository->create($payload);

        $this->assertEquals('mariah', $user['username']);
        $this->assertEquals('Mariah', $user['name']);
        $this->assertEquals('hsifuh#@llsad.ii.com', $user['email']);
        $this->assertTrue(Hash::check('123abc', $user['password']));
        $this->assertTrue($user['id'] > 0);
    }

}
