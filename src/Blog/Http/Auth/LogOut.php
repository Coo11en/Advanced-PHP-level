<?php

namespace App\courseProject\Blog\Http\Auth;

use App\courseProject\Blog\AuthToken;
use App\courseProject\Blog\Exceptions\AuthException;
use App\courseProject\Blog\Http\Actions\ActionInterface;
use App\courseProject\Blog\Http\ErrorResponse;
use App\courseProject\Blog\Http\Request;
use App\courseProject\Blog\Http\Response;
use App\courseProject\Blog\Http\SuccessfulResponse;
use App\courseProject\Blog\Repository\AuthTokensRepository\AuthTokensRepositoryInterface;
use App\courseProject\Blog\Repository\AuthTokensRepository\SqliteAuthTokensRepository;
use DateTimeImmutable;

class LogOut implements ActionInterface
{
    public function __construct(
        private TokenAuthenticationInterface $authentication,
        private AuthTokensRepositoryInterface $authTokensRepository,
        private SqliteAuthTokensRepository $tokensRepository,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $author = $this->authentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $authorToken = $this->tokensRepository->getUserUuid($author->getUuid());

        // Генерируем токен
        $authToken = new AuthToken(
        // Случайная строка длиной 40 символов
            $authorToken,
            $author->getUuid(),
            // Срок годности - 1 день
            (new DateTimeImmutable())->modify('now')
        );
        // Сохраняем токен в репозиторий
        $this->authTokensRepository->save($authToken);
        // Возвращаем токен
        return new SuccessfulResponse([
            'token' => (string)$authToken->token(),
        ]);
    }
}