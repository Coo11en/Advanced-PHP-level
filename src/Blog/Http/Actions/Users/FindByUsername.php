<?php

namespace App\courseProject\Blog\Http\Actions\Users;

use App\courseProject\Blog\Exceptions\HttpException;
use App\courseProject\Blog\Exceptions\UserNotFoundException;
use App\courseProject\Blog\Http\Actions\ActionInterface;
use App\courseProject\Blog\Repository\Interface\UsersRepositoryInterface;
use App\courseProject\Blog\Http\Request;
use App\courseProject\Blog\Http\Response;
use App\courseProject\Blog\Http\ErrorResponse;
use App\courseProject\Blog\Http\SuccessfulResponse;

class FindByUsername implements ActionInterface
{
// Нам понадобится репозиторий пользователей,
// внедряем его контракт в качестве зависимости
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }
// Функция, описанная в контракте
    public function handle(Request $request): Response
    {
        try {
// Пытаемся получить искомое имя пользователя из запроса
            $username = $request->query('username');
        } catch (HttpException $e) {
// Если в запросе нет параметра username -
// возвращаем неуспешный ответ,
// сообщение об ошибке берём из описания исключения
            return new ErrorResponse($e->getMessage());
        }
        try {
// Пытаемся найти пользователя в репозитории
            $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
// Если пользователь не найден -
// возвращаем неуспешный ответ
            return new ErrorResponse($e->getMessage());
        }
// Возвращаем успешный ответ
        return new SuccessfulResponse([
            'username' => $user->getUsername(),
            'name' => $user->getFirstname() . ' ' . $user->getSecondname(),
        ]);
    }
}