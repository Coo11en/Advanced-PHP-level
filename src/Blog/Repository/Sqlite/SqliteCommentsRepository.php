<?php

namespace App\courseProject\Blog\Repository\Sqlite;

use App\courseProject\Blog\Comment;
use App\courseProject\Blog\Exceptions\CommentNotFoundException;
use App\courseProject\Blog\Exceptions\InvalidArgumentException;
use App\courseProject\Blog\Exceptions\PostNotFoundException;
use App\courseProject\Blog\Exceptions\UserNotFoundException;
use App\courseProject\Blog\Repository\Interface\CommentsRepositoryInterface;
use App\courseProject\Blog\UUID;
use PDO;
use Psr\Log\LoggerInterface;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    private PDO $connection;

    public function __construct(
        PDO $connection,
        private LoggerInterface $logger,
    )
    {
        $this->connection = $connection;
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, post_uuid, author_uuid, text)
                   VALUES (:uuid, :post_uuid, :author_uuid, :text)'
        );

        // Логируем UUID нового коментария
        $newCommentUuid = (string)$comment->getUuid();
        $this->logger->info("Comment created: $newCommentUuid");

        $statement->execute([
            ':author_uuid' => $comment->getUserUuid()->getUuid(),
            ':post_uuid' => $comment->getPostUuid()->getUuid(),
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
            'SELECT * FROM comments WHERE uuid = :uuid'
        );

        $statement->execute([':uuid' => (string)$uuid]);

        return $this->getComment($statement, $uuid);
    }

    /**
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     * @throws PostNotFoundException
     * @throws CommentNotFoundException
     */
    private function getComment($statement, $uuid): Comment
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            $this->logger->warning("Comment requested: $uuid");
            throw new CommentNotFoundException(
                "Cannot find comment: $uuid"
            );
        }

        $userRepository = new SqliteUsersRepository($this->connection, $this->connection);
        $user = $userRepository->get(new UUID($result['author_uuid']));

        $postRepository = new SqlitePostsRepository($this->connection);
        $post = $postRepository->get(new UUID($result['post_uuid']));

        return new Comment(
            new UUID($result['uuid']),
            $post,
            $user,
            $result['text']);
    }
}