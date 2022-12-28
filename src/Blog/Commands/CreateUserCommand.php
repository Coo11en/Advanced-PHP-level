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

        // Сохраняем пользователя в репозиторий
        $uuid = UUID::random();
        $this->usersRepository->save(new User(
            $uuid,
            $username,
            $arguments->get('first_name'),
            $arguments->get('last_name')),
        );

        // Логируем информацию о новом пользователе
        $this->logger->info("User created: $uuid");
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