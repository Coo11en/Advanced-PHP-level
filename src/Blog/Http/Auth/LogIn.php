<?php

namespace App\courseProject\Blog\Http\Auth;

use App\courseProject\Blog\Http\Auth\PasswordAuthenticationInterface;
use App\courseProject\Blog\Repository\AuthTokensRepository\AuthTokensRepositoryInterface;
use App\courseProject\Blog\Http\Request;
use App\courseProject\Blog\Http\Response;
use App\courseProject\Blog\Exceptions\AuthException;
use App\courseProject\Blog\Http\ErrorResponse;
use App\courseProject\Blog\AuthToken;
use DateTimeImmutable;
use App\courseProject\Blog\Http\SuccessfulResponse;
use App\courseProject\Blog\Http\Actions\ActionInterface;

class LogIn implements ActionInterface

{
    public function __construct(
        // Авторизация по паролю
        private PasswordAuthenticationInterface $passwordAuthentication,
        // Репозиторий токенов
        private AuthTokensRepositoryInterface $authTokensRepository
    ) {
    }
    public function handle(Request $request): Response
    {
        // Аутентифицируем пользователя
        try {
            $user = $this->passwordAuthentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }


        // Генерируем токен
        $authToken = new AuthToken(
            // Случайная строка длиной 40 символов
            bin2hex(random_bytes(40)),
            $user->getUuid(),
            // Срок годности - 1 день
            (new DateTimeImmutable())->modify('+1 day')
        );
        // Сохраняем токен в репозиторий
        $this->authTokensRepository->save($authToken);
        // Возвращаем токен
        return new SuccessfulResponse([
            'token' => (string)$authToken->token(),
        ]);
    }
}