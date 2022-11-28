<?php

namespace App\courseProject\tests\Sqlite;

use App\courseProject\Blog\Repository\Sqlite\SqliteUsersRepository;
use App\courseProject\Blog\UUID;
use App\courseProject\Person\User;
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
         ]);
     $connectionStub->method('prepare')->willReturn($statementMock);
     $repository = new SqliteUsersRepository($connectionStub);
     $repository->save(
         new User(
             new UUID('140e8659-c69f-4a7e-a7dc-88b85432ae7b'),
             'moiseev.albina',
             'Ульяна',
             'Шилова')
     );
 }
}