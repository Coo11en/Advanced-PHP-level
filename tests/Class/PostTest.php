<?php

namespace App\courseProject\tests\Class;

use App\courseProject\Blog\Post;
use App\courseProject\Person\User;
use App\courseProject\Blog\UUID;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{

    public function testNumberUuid()
    {
        $post = new Post(
            new UUID('a2d66c0a-a1d1-8c56-a4d4-b68075312b15'),
            new User(new UUID('a2d66c0a-a7d1-8c56-a4d4-b68085313b15'),
                '111111',
                'some_password',
                '2222222',
                '33333333'),
            'headerheader',
            'texttexttext');

        $this->assertEquals('a2d66c0a-a1d1-8c56-a4d4-b68075312b15',
            $post->getUuid());
        $this->assertEquals('a2d66c0a-a7d1-8c56-a4d4-b68085313b15',
            $post->getUserUuid()->getUuid());
        $this->assertEquals('headerheader',
            $post->getHeader());
        $this->assertEquals('texttexttext',
            $post->getText());

        $post->setHeader('hhhhhhhhh');
        $this->assertEquals('hhhhhhhhh', $post->getHeader());
        $post->setText('tttttttttt');
        $this->assertEquals('tttttttttt', $post->getText());
        $post->setUserUuid(new User(new UUID('a2d66c0a-a1d1-8c56-a4d4-b68075312b15'),
            '4444444',
            'some_password',
            '5555555',
            '66666666'));
        $this->assertEquals('a2d66c0a-a1d1-8c56-a4d4-b68075312b15', $post->getUserUuid()->getUuid());
        $post->setUuid(new UUID('11111111-2222-3333-4444-555555555555'));
        $this->assertEquals('11111111-2222-3333-4444-555555555555', $post->getUuid());
        $this->assertEquals('hhhhhhhhh >>> tttttttttt', $post);
    }



}