<?php

namespace App\courseProject\tests\Actions;

use PHPUnit\Framework\TestCase;
use App\courseProject\tests\DummyLogger;
use App\courseProject\Blog\Commands\Arguments;
use App\courseProject\Blog\Commands\CreateUserCommand;
use App\courseProject\Blog\Exceptions\ArgumentsException;
use App\courseProject\Blog\Exceptions\CommandException;
use App\courseProject\Blog\Exceptions\UserNotFoundException;
use App\courseProject\Blog\Repository\Sqlite\DummyPostsRepository;
use App\courseProject\Blog\Repository\Interface\UsersRepositoryInterface;
use App\courseProject\Blog\UUID;
use App\courseProject\Person\User;
use App\courseProject\Blog\Commands\Users\CreateUser;
use RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class CreateUserCommandTest extends TestCase
{
    public function testItRequiresLastName(): void
    {
        // Тестируем новую команду
        $command = new CreateUser(
            $this->makeUsersRepository(),
        );
        // Меняем тип ожидаемого исключения ..
        $this->expectException(RuntimeException::class);
        // .. и его сообщение
        $this->expectExceptionMessage(
            'Not enough arguments (missing: "last_name").'
        );
        // Запускаем команду методом run вместо handle
        $command->run(
        // Передаём аргументы как ArrayInput,
        // а не Arguments
        // Сами аргументы не меняются
            new ArrayInput([
                'username' => 'Ivan',
                'password' => 'some_password',
                'first_name' => 'Ivan',
            ]),
            // Передаём также объект,
            // реализующий контракт OutputInterface
            // Нам подойдёт реализация,
            // которая ничего не делает
            new NullOutput()
        );
    }
    // Тем же образом обновляем остальные тесты
    public function testItRequiresPassword(): void
    {
        $command = new CreateUser(
            $this->makeUsersRepository()
        );
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Not enough arguments (missing: "first_name, last_name, password"'
        );
        $command->run(
            new ArrayInput([
                'username' => 'Ivan',
            ]),
            new NullOutput()
        );
    }

    public function testItRequiresFirstName(): void
    {
        $command = new CreateUser(
            $this->makeUsersRepository()
        );
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Not enough arguments (missing: "first_name, last_name").'
        );
        $command->run(
            new ArrayInput([
                'username' => 'Ivan',
                'password' => 'some_password',
            ]),
            new NullOutput()
        );
    }

    public function testItSavesUserToRepository(): void
    {
        $usersRepository = new class implements UsersRepositoryInterface {
// В этом свойстве мы храним информацию о том,
// был ли вызван метод save
            private bool $called = false;

            public function save(User $user): void
            {
// Запоминаем, что метод save был вызван
                $this->called = true;
            }

            public function get(UUID $uuid): User
            {

                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException("Not found");
            }
// Этого метода нет в контракте UsersRepositoryInterface,
// но ничто не мешает его добавить.
// С помощью этого метода мы можем узнать,
// был ли вызван метод save
            public function wasCalled(): bool
            {
                return $this->called;
            }
        };
        $command = new CreateUser(
            $usersRepository
        );
        $command->run(
            new ArrayInput([
                'username' => 'Ivan',
                'password' => 'some_password',
                'first_name' => 'Ivan',
                'last_name' => 'Nikitin',
            ]),
            new NullOutput()
        );
        $this->assertTrue($usersRepository->wasCalled());
    }

//    public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
//    {
//        $command = new CreateUserCommand(new DummyUsersRepository());
//        // Описываем тип ожидаемого исключения
//        $this->expectException(CommandException::class);
//
//        // и его сообщение
//        $this->expectExceptionMessage('User already exists: Ivan');
//
//        // Запускаем команду с аргументами
//        $command->handle(new Arguments(['username' => 'Ivan']));
//    }

    // Функция возвращает объект типа UsersRepositoryInterface
    private function makeUsersRepository(): UsersRepositoryInterface
    {
        return new class implements UsersRepositoryInterface {
            public function save(User $user): void
            {
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User

            {
                throw new UserNotFoundException("Not found");
            }
        };
    }
}