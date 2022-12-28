<?php

namespace App\courseProject\Blog\Http\Actions\Posts;

use App\courseProject\Blog\Exceptions\HttpException;
use App\courseProject\Blog\Exceptions\InvalidArgumentException;
use App\courseProject\Blog\Exceptions\UserNotFoundException;
use App\courseProject\Blog\Http\Actions\ActionInterface;
use App\courseProject\Blog\Post;
use App\courseProject\Blog\Repository\Interface\PostsRepositoryInterface;
use App\courseProject\Blog\Repository\Interface\UsersRepositoryInterface;
use App\courseProject\Blog\UUID;
use App\courseProject\Blog\Http\Request;
use App\courseProject\Blog\Http\Response;
use App\courseProject\Blog\Http\ErrorResponse;
use App\courseProject\Blog\Http\SuccessfulResponse;
use Psr\Log\LoggerInterface;
use App\courseProject\Blog\Http\Auth\IdentificationInterface;

class CreatePost implements ActionInterface
{
// Внедряем репозитории статей и пользователей
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private IdentificationInterface $identification,
    ) {
    }
    public function handle(Request $request): Response
    {
// Идентифицируем пользователя -
// автора статьи
        $author = $this->identification->user($request);
        $newPostUuid = UUID::random();

        try {
// Пытаемся создать объект статьи
// из данных запроса
            $post = new Post(
                $newPostUuid,
                $author,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
// Сохраняем новую статью в репозитории
        $this->postsRepository->save($post);
// Возвращаем успешный ответ,
// содержащий UUID новой статьи
        return new SuccessfulResponse([
            'uuid' => (string)$newPostUuid,
        ]);
    }
}