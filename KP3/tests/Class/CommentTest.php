<?php

namespace App\courseProject\tests\Class;

use App\courseProject\Blog\Comment;
use App\courseProject\Blog\Post;
use App\courseProject\Blog\UUID;
use App\courseProject\Person\User;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function testClassCommentAllPayload()
    {
        $test = new Comment(
            new UUID('401179c6-c8d8-4892-b795-c25d21f5f7b4'),
            new Post(
                new UUID('401179c6-c8d8-1111-b795-c25d21f5f7b4'),
                new User(
                    new UUID('401179c6-c8d8-2222-b795-c25d21f5f7b4'),
                    'login',
                    'name',
                    'secondname'),
                'header',
                'texttext'),
            new User(
                new UUID('401179c6-c8d8-4892-3333-c25d21f5f7b4'),
                'login',
                'name',
                'secondname'),
            'longText'
        );

        $this->assertEquals('401179c6-c8d8-4892-b795-c25d21f5f7b4',$test->getUuid());
        $this->assertEquals('401179c6-c8d8-4892-3333-c25d21f5f7b4',$test->getUserUuid()->getUuid());
        $this->assertEquals('401179c6-c8d8-1111-b795-c25d21f5f7b4',$test->getPostUuid()->getUuid());
        $this->assertEquals('longText',$test->getText());

        $test->setUuid(new UUID('401179c6-c8d8-1111-b795-c25d21f5f7b4'));
        $this->assertNotEquals('401179c6-c8d8-9999-b795-c25d21f5f7b4', $test->getUuid());
        $test->setPostUuid(
            new Post(
                new UUID('401179c6-c8d8-7777-b795-c25d21f5f7b4'),
                new User(new UUID('401179c6-c8d8-8888-b795-c25d21f5f7b4'),
                    '',
                    '',
                    ''),
                '',
                ''));
        $this->assertEquals('401179c6-c8d8-7777-b795-c25d21f5f7b4', $test->getPostUuid()->getUuid());
        $test->setUser(
            new User(new UUID('401179c6-c8d8-3333-b795-c25d21f5f7b4'),
            '',
            '',
            ''
            ));
        $this->assertEquals('401179c6-c8d8-3333-b795-c25d21f5f7b4', $test->getUserUuid()->getUuid());
        $test->setText(123456);
        $this->assertEquals('123456', $test->getText());
    }


}