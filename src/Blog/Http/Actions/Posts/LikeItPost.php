<?php

namespace App\courseProject\Blog\Http\Actions\Posts;

use App\courseProject\Blog\Exceptions\HttpException;
use App\courseProject\Blog\Exceptions\InvalidArgumentException;
use App\courseProject\Blog\Exceptions\PostNotFoundException;
use App\courseProject\Blog\Exceptions\UserNotFoundException;
use App\courseProject\Blog\Http\Actions\ActionInterface;
use App\courseProject\Blog\Http\Actions\ActionIterface;
use App\courseProject\Blog\Http\ErrorResponse;
use App\courseProject\Blog\Http\Request;
use App\courseProject\Blog\Http\Response;
use App\courseProject\Blog\Http\SuccessfulResponse;
use App\courseProject\Blog\Like;
use App\courseProject\Blog\Repository\Interface\LikesRepositoryInterface;
use App\courseProject\Blog\Repository\Interface\PostsRepositoryInterface;
use App\courseProject\Blog\Repository\Interface\UsersRepositoryInterface;
use App\courseProject\Blog\UUID;

class LikeItPost implements ActionInterface
{
// Внедряем репозитории статей и пользователей
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private UsersRepositoryInterface $usersRepository,
        private LikesRepositoryInterface $likesRepository,
    ) {
    }

    public function handle(Request $request): Response
    {
        // Пытаемся создать UUID пользователя из данных запроса
        try {
            $authorUuid = new UUID($request->jsonBodyField('uuid_user'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }
        // Пытаемся создать UUID статьи из данных запроса
        try {
            $postUuid = new UUID($request->jsonBodyField('uuid_post'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        // Пытаемся найти пользователя в репозитории
        try {
            $author = $this->usersRepository->get($authorUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        // Пытаемся найти статью в репозитории
        try {
            $post = $this->postsRepository->get($postUuid);
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

            // Генерируем UUID для новой статьи
            $newLikeUuid = UUID::random();

            try {
// Пытаемся создать объект статьи
// из данных запроса
                $like = new Like(
                    $newLikeUuid,
                    $post,
                    $author
                );
            } catch (HttpException $e) {
                return new ErrorResponse($e->getMessage());
            }

            $this->likesRepository->save($like);

            // Возвращаем успешный ответ,
            // содержащий UUID нового лайка

            return new SuccessfulResponse([
                'uuid' => (string)$newLikeUuid,
            ]);
    }
}