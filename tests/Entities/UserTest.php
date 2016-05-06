<?php

namespace Entities;

use Nestor\Entities\User;
use \TestCase;

class UserTest extends TestCase
{

    public function setUp() {
        parent::setUp();
    }

    public function testClassAttributes() {
        $fillable = ['username', 'name', 'email', 'password'];
        $hidden = ['password', 'remember_token'];

        $user = new User();
        $this->assertEquals($fillable, $user->getFillable());

        $this->assertEquals($hidden, $user->getHidden());
    }
}
