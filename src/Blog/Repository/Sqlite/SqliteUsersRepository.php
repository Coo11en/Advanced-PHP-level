<?php


namespace App\courseProject\Blog\Repository\Sqlite;

use App\courseProject\Blog\Repository\Interface\UsersRepositoryInterface;
use App\courseProject\Person\User;
use App\courseProject\Blog\Exceptions\UserNotFoundException;
use App\courseProject\Blog\UUID;
use PDO;
use Psr\Log\LoggerInterface;

class SqliteUsersRepository implements UsersRepositoryInterface
{
    private PDO $connection;

    public function __construct(
        PDO $connection,
        private LoggerInterface $logger,
    )
    {
        $this->connection = $connection;
    }

    public function save(User $user): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (uuid, username, password, first_name, last_name)
                   VALUES (:uuid, :username, :password, :first_name, :last_name)'
        );

        $newUserUuid = (string)$user->getUuid();
        $statement->execute([
            ':first_name' => $user->getFirstname(),
            ':last_name' => $user->getSecondname(),
            ':uuid' => $newUserUuid,
            ':username' => $user->getUsername(),
            ':password' => $user->hashedPassword(),
        ]);
    }

    /**
     * @throws UserNotFoundException
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
         */

    public function getByUsername(string $username): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = :username'
        );

        $statement->execute([
            ':username' => $username,
        ]);
        return $this->getUser($statement, $username);
    }

    private function getUser($statement, $uuid): User
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            $this->logger->warning("User not found: $uuid");
            throw new UserNotFoundException(
           "Cannot find user: $uuid"
           );
        }

        return new User(
            new UUID($result['uuid']),
                $result['username'],
                $result['password'],
                $result['first_name'],
            (string)$result['last_name']
        );
    }
}