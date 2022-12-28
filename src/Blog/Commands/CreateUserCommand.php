<?php

namespace App\courseProject\Blog\Commands;

use App\courseProject\Blog\Exceptions\ArgumentsException;
use App\courseProject\Blog\Exceptions\InvalidArgumentException;
use App\courseProject\Blog\Exceptions\UserNotFoundException;
use App\courseProject\Blog\Repository\Interface\UsersRepositoryInterface;
use App\courseProject\Blog\UUID;
use App\courseProject\Person\User;
use App\courseProject\Blog\Exceptions\CommandException;
use Psr\Log\LoggerInterface;

class CreateUserCommand
{
// php cli.php username=test2 password=test2 first_name=Nik last_name=Nikita
// Команда зависит от контракта репозитория пользователей,
// а не от конкретной реализации
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger
    )
    {
    }

    /**
     * @throws CommandException
     * @throws InvalidArgumentException|ArgumentsException
     */
    public function handle(Arguments $arguments): void
    {
        $this->logger->info("Create user command started");
        $username = $arguments->get('username');

        // Проверяем, существует ли пользователь в репозитории
        if ($this->userExists($username)) {
        // Логируем сообщение с уровнем WARNING
            $this->logger->warning("User already exists: $username");
        // Вместо выбрасывания исключения просто выходим из функции
            return;
        }

        // Создаём объект пользователя
        // Функция createFrom сама создаст UUID
        // и захеширует пароль
        $user = User::createFrom(
            $username,
            $arguments->get('password'),
            $arguments->get('first_name'),
            $arguments->get('last_name')
        );

        $this->usersRepository->save($user);
        // Получаем UUID созданного пользователя
        $this->logger->info('User created: ' . $user->getUuid());
    }

    private function userExists(string $username): bool
    {
        try {
            // Пытаемся получить пользователя из репозитория
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }
}