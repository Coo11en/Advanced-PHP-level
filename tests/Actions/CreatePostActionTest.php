<?php

namespace App\courseProject\tests\Actions;

use App\courseProject\Blog\Commands\Arguments;
use App\courseProject\Blog\Exceptions\ArgumentsException;
use App\courseProject\Blog\Exceptions\UserNotFoundException;
use App\courseProject\Blog\Http\Actions\Posts\CreatePost;
use App\courseProject\Blog\Http\Request;
use App\courseProject\Blog\Post;
use App\courseProject\Blog\Repository\Interface\UsersRepositoryInterface;
use App\courseProject\Blog\UUID;
use App\courseProject\Person\User;
use App\courseProject\tests\DummyLogger;
use PHPUnit\Framework\TestCase;
use App\courseProject\Blog\Repository\Interface\PostsRepositoryInterface;
use App\courseProject\Blog\Http\Auth\IdentificationInterface;

class CreatePostActionTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */

    public function testItReturnSuccesfulResponse(): void
    {
        $request = new Request(['header' => 'title'], [], '');
        $postsRepository = new class implements PostsRepositoryInterface {
            public function save(Post $post): void
            {
// Ничего не делаем
            }

            public function get(UUID $uuid): Post
            {
// И здесь ничего не делаем
                throw new UserNotFoundException("Not found");
            }

            public function delete(UUID $uuid): void
            {
                // И здесь ничего не делаем
            }

        };

        $identification = new class implements IdentificationInterface {
            public function user(Request $request): User
            {
// И здесь ничего не делаем
                throw new UserNotFoundException("Not found");
            }
        };

        $logger = new DummyLogger();

        $action = new CreatePost($postsRepository, $identification, $logger);
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('No such argument: first_name');
// Запускаем команду

        $action->handle(new Request($_GET, $_SERVER, '"author_uuid": "81c0222b-7f65-4cf8-a7a6-f08b1dce3aa2",
"title": "Title text",
"text": "Text text"'));
}
}

