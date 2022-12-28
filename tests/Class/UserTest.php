<?php

namespace App\courseProject\tests\Class;

use App\courseProject\Blog\UUID;
use App\courseProject\Person\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testAllPayloadUser()
    {
        $user = New User(new UUID('401179c6-c8d8-4892-b795-c25d21f5f7b4'), 'login', 'name', 'secondname');
        $this->assertEquals('401179c6-c8d8-4892-b795-c25d21f5f7b4', $user->getUuid());
        $this->assertEquals('login', $user->getUsername());
        $this->assertEquals('name', $user->getFirstname());
        $this->assertEquals('secondname', $user->getSecondname());

        $user->setUuid(new UUID('111111d1-c8d8-4892-b795-c25d21f5f7b4'));
        $this->assertNotEquals('401179c6-c8d8-4892-b795-c25d21f5f7b4', $user->getUuid());
        $user->setUsername( 11111);
        $this->assertNotEquals('1111', $user->getUsername());
        $user->setFirstname('name222');
        $this->assertNotEquals('name', $user->getFirstname());
        $user->setSecondname('secondname333');
        $this->assertNotEquals('secondname', $user->getSecondname());
    }

}