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

namespace Nestor\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class TestCases extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['project_id', 'test_suite_id'];

    protected $appends = ['version'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'test_cases';

    public $version = null;

    public function getProjectIdAttribute($value)
    {
        return intval($value);
    }

    public function getTestSuiteIdAttribute($value)
    {
        return intval($value);
    }

    public function project()
    {
        return $this->belongsTo('Nestor\\Entities\\Projects', 'project_id');
    }

    public function testSuite()
    {
        return $this->belongsTo('Nestor\\Entities\\TestSuites', 'test_suite_id');
    }

    public function testCaseVersions()
    {
        return $this->hasMany('Nestor\\Entities\\TestCasesVersions', 'test_case_id');
    }

    public function latestVersion()
    {
        return $this->hasMany('Nestor\\Entities\\TestCasesVersions', 'test_case_id')
            ->orderBy('version', 'desc')
            ->take(1)
            ->firstOrFail(); // FIXME: redundant take1?
    }

    public function getVersionAttribute()
    {
        return $this->version;
    }

    // public function steps()
    // {
    //     return $this->hasManyThrough('Nestor\\Model\\TestCaseStepVersion', 'Nestor\\Model\\TestCaseVersion', 'test_case_id', 'test_case_version_id');
    // }
}
