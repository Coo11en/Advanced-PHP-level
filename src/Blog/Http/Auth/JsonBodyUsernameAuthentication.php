<?php

namespace App\courseProject\Blog\Http\Auth;

use App\courseProject\Blog\Http\Request;
use App\courseProject\Person\User;
use App\courseProject\Blog\Repository\Interface\UsersRepositoryInterface;
use App\courseProject\Blog\Exceptions\HttpException;
use App\courseProject\Blog\Exceptions\AuthException;
use App\courseProject\Blog\Exceptions\UserNotFoundException;

class JsonBodyUsernameAuthentication implements AuthenticationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }
    public function user(Request $request): User
    {
        try {
// Получаем имя пользователя из JSON-тела запроса;
// ожидаем, что имя пользователя находится в поле username
            $username = $request->jsonBodyField('username');
        } catch (HttpException $e) {
// Если невозможно получить имя пользователя из запроса -
// бросаем исключение
            throw new AuthException($e->getMessage());
        }
        try {
// Ищем пользователя в репозитории и возвращаем его
            return $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
// Если пользователь не найден -
// бросаем исключение
            throw new AuthException($e->getMessage());
        }
    }
}