<?php

namespace App\courseProject\Blog\Repository\Sqlite;

use App\courseProject\Blog\Exceptions\InvalidArgumentException;
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
            ':author_uuid' => $post->getUserUuid(),
            ':title' => $post->getHeader(),
            ':uuid' => (string)$post->getUuid(),
            ':text' => $post->getText()
        ]);
    }

    /**
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE uuid = ?'
        );

        $statement->execute([(string)$uuid]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new UserNotFoundException(
                "Cannot get post: $uuid"
            );
        }

        return $this->getPost($statement, $uuid);
    }
    /**
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    private function getPost($statement, $uuid): Post
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new UserNotFoundException(
                "Cannot find post: $uuid"
            );
        }

        return new Post(
            new UUID($result['uuid']),
            $result['author_uuid'],
            $result['title'],
            $result['text']);
    }
}