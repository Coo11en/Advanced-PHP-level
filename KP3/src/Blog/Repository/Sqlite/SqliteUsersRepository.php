<?php


namespace App\courseProject\Blog\Repository\Sqlite;

use App\courseProject\Blog\Exceptions\InvalidArgumentException;
use App\courseProject\Blog\Repository\Interface\UsersRepositoryInterface;
use App\courseProject\Person\User;
use App\courseProject\Blog\Exceptions\UserNotFoundException;
use App\courseProject\Blog\UUID;
use PDO;

class SqliteUsersRepository implements UsersRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (uuid, username, first_name, last_name)
                   VALUES (:uuid, :username, :first_name, :last_name)'
        );

        $statement->execute([
            ':first_name' => $user->getFirstname(),
            ':last_name' => $user->getSecondname(),
            ':uuid' => (string)$user->getUuid(),
            ':username' => $user->getUsername()
        ]);
    }

    /**
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    public function get(UUID $uuid): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = ?'
        );

        $statement->execute([(string)$uuid]);

        return $this->getUser($statement, $uuid);
    }
        /**
         * @throws UserNotFoundException
         * @throws InvalidArgumentException
         */


    private function getUser($statement, $uuid): User
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            throw new UserNotFoundException(
           "Cannot find user: $uuid"
           );
        }

        return new User(
            new UUID($result['uuid']),
                $result['username'],
                $result['first_name'],
                $result['last_name']);
    }
}