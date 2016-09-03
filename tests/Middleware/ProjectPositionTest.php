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

namespace Entities;

use Illuminate\Http\Request;
use Nestor\Http\Middleware\ProjectPosition;
use \TestCase;

class FakeResponse extends \stdClass
{
    public function __construct()
    {
        $this->name = '';
        $this->value = '';
    }
    public function header($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}

class ProjectPositionTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->createApplication();
    }

    public function testNoProjectId()
    {
        //$this->assertViewHas('X-NESTORQA-PROJECT-ID', null);

        $guard = $this->mock('Illuminate\Contracts\Auth\Guard');
        $mw = new ProjectPosition($guard);

        \Session::setDefaultDriver('array');
        $manager = app('session');

        $request = new Request();
        $request->setSession($manager->driver());

        $response = new FakeResponse();

        $mw->handle($request, function ($request) use ($response) {
            return $response;
        });

        $this->assertEquals('', $response->name);
        $this->assertEquals('', $response->value);
    }

    public function testWithProjectId()
    {
        //$this->assertViewHas('X-NESTORQA-PROJECT-ID', null);

        $guard = $this->mock('Illuminate\Contracts\Auth\Guard');
        $mw = new ProjectPosition($guard);

        \Session::setDefaultDriver('array');
        $manager = app('session');

        $request = new Request();
        $request->setSession($manager->driver());
        $projectId = $this->faker->numberBetween(1, 10);
        $request->session()->set('project_id', $projectId);

        $response = new FakeResponse();

        $mw->handle($request, function ($request) use ($response) {
            return $response;
        });

        $this->assertEquals('X-NESTORQA-PROJECT-ID', $response->name);
        $this->assertEquals($projectId, $response->value);
    }
}
