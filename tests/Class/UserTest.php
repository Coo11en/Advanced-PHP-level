<?php

namespace App\courseProject\tests\Class;

use App\courseProject\Blog\UUID;
use App\courseProject\Person\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testAllPayloadUser()
    {
        $user = New User(new UUID('401179c6-c8d8-4892-b795-c25d21f5f7b4'), 'login', 'some_password', 'name', 'secondname');
        $this->assertEquals('401179c6-c8d8-4892-b795-c25d21f5f7b4', $user->getUuid());
        $this->assertEquals('login', $user->getUsername());
        $this->assertEquals('name', $user->getFirstname());
        $this->assertEquals('secondname', $user->getSecondname());

        $this->assertNotEquals('401179c6-c8d8-4992-b795-c25d21f5f7b4', $user->getUuid());
        $this->assertNotEquals('1111', $user->getUsername());
        $this->assertNotEquals('name1', $user->getFirstname());
        $this->assertNotEquals('secondname1', $user->getSecondname());
    }

}