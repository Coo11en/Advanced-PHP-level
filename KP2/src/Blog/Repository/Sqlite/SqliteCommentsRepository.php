<?php

namespace App\courseProject\Blog\Repository\Sqlite;

use App\courseProject\Blog\Comment;
use App\courseProject\Blog\Exceptions\InvalidArgumentException;
use App\courseProject\Blog\Exceptions\UserNotFoundException;
use App\courseProject\Blog\Repository\Interface\CommentsRepositoryInterface;
use App\courseProject\Blog\UUID;
use PDO;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, post_uuid, author_uuid, text)
                   VALUES (:uuid, :post_uuid, :author_uuid, :text)'
        );

        $statement->execute([
            ':author_uuid' => $comment->getUserUuid(),
            ':post_uuid' => $comment->getPostUuid(),
            ':uuid' => (string)$comment->getUuid(),
            ':text' => $comment->getText()
        ]);
    }

    /**
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE uuid = ?'
        );

        $statement->execute([(string)$uuid]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new UserNotFoundException(
                "Cannot get comment: $uuid"
            );
        }

        return $this->getComment($statement, $uuid);
    }
    /**
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    private function getComment($statement, $uuid): Comment
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new UserNotFoundException(
                "Cannot find comment: $uuid"
            );
        }

        return new Comment(
            new UUID($result['uuid']),
            $result['post_uuid'],
            $result['author_uuid'],
            $result['text']);
    }
}