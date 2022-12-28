<?php

namespace App\courseProject\tests\Actions;

use App\courseProject\Blog\Http\Actions\Posts\CreatePost;
use App\courseProject\Blog\Http\Request;
use App\courseProject\Blog\Post;
use App\courseProject\Blog\UUID;
use App\courseProject\Person\User;
use PHPUnit\Framework\TestCase;

class CreatePostActionTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */

    public function testItReturnSuccesfulResponse(): void
    {
        $request = new Request(['header' => 'title'], [], '');

        $postsReposytory = $this->postsReposytory([
           new Post(
               UUID::random(),
               new User(UUID::random(), '1111', '2222' ,'3333'),
               'title',
               'text'
           )
        ]);

        $action = new CreatePost($postsReposytory, $usersReposytory);
}