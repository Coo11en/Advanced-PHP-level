<?php

namespace App\courseProject\tests\Sqlite;

use App\courseProject\Blog\Repository\Sqlite\SqliteUsersRepository;
use App\courseProject\Blog\UUID;
use App\courseProject\Person\User;
use App\courseProject\tests\DummyLogger;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqliteUsersRepositoryTest extends TestCase
{
 public function testItReturnsValuesAsString(): void
 {
     $connectionStub = $this->createStub(PDO::class);
     $statementMock = $this->createMock(PDOStatement::class);
     $statementMock
         ->expects($this->once())
         ->method('execute')
         ->with([
             ':first_name' => 'Ульяна',
             ':last_name' => 'Шилова',
             ':uuid' => '140e8659-c69f-4a7e-a7dc-88b85432ae7b',
             ':username' => 'moiseev.albina',
             ':password' => 'some_password',
         ]);
     $connectionStub->method('prepare')->willReturn($statementMock);
     $logger = new DummyLogger();
     $repository = new SqliteUsersRepository($connectionStub, $logger);
     $repository->save(
         new User(
             new UUID('140e8659-c69f-4a7e-a7dc-88b85432ae7b'),
             'moiseev.albina',
             'some_password',
             'Ульяна',
             'Шилова')
     );
 }

    public function testItSavesUserToDatabase(): void
    {
        $logger = new DummyLogger();
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
                ':username' => 'ivan123',
                // добавили пароль
                ':password' => 'some_password',
                ':first_name' => 'Ivan',
                ':last_name' => 'Nikitin',
            ]);
        $connectionStub->method('prepare')->willReturn($statementMock);
        $repository = new SqliteUsersRepository($connectionStub, $logger);
        $repository->save(
            new User(
                new UUID('123e4567-e89b-12d3-a456-426614174000'),
                'ivan123',
                // добавили пароль
                'some_password',
                'Ivan',
                'Nikitin'
            )
        );
    }
}