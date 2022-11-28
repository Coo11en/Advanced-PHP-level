<?php

namespace App\courseProject\Blog\Repository\Sqlite;

use App\courseProject\Blog\Exceptions\InvalidArgumentException;
use App\courseProject\Blog\Exceptions\PostNotFoundException;
use App\courseProject\Blog\Exceptions\UserNotFoundException;
use App\courseProject\Blog\Post;
use App\courseProject\Blog\Repository\Interface\PostsRepositoryInterface;
use App\courseProject\Blog\UUID;
use PDO;

class SqlitePostsRepository implements PostsRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text)
                   VALUES (:uuid, :author_uuid, :title, :text)'
        );

        $statement->execute([
            ':author_uuid' => $post->getUserUuid()->getUuid(),
            ':title' => $post->getHeader(),
            ':uuid' => (string)$post->getUuid(),
            ':text' => $post->getText()
        ]);
    }

    /**
     * @throws PostNotFoundException
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE uuid = :uuid'
        );

        $statement->execute([':uuid' => (string)$uuid]);

        return $this->getPost($statement, $uuid);
    }

    /**
     * @throws PostNotFoundException
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     */
    private function getPost(\PDOStatement $statement, string $uuid): Post
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            throw new PostNotFoundException(
                "Cannot find post: $uuid"
            );
        }
        $userRepository = new SqliteUsersRepository($this->connection);
        $user = $userRepository->get(new UUID($result['author_uuid']));

        return new Post(
            new UUID($result['uuid']),
            $user,
            $result['title'],
            $result['text']);
    }
}