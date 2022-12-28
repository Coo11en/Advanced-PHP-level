<?php

namespace App\courseProject\Blog\Repository\Sqlite;

use App\courseProject\Blog\Exceptions\InvalidArgumentException;
use App\courseProject\Blog\Like;
use App\courseProject\Blog\Repository\Interface\LikesRepositoryInterface;
use App\courseProject\Blog\UUID;
use PDO;
use App\courseProject\Blog\Exceptions\LikeNotFoundException;
use Psr\Log\LoggerInterface;

class SqliteLikesRepository implements LikesRepositoryInterface
{
    private PDO $connection;

    public function __construct(
        PDO $connection,
        private LoggerInterface $logger,
    )
    {
        $this->connection = $connection;
    }

    public function save(Like $like): void
{
    $statement = $this->connection->prepare(
        'INSERT INTO likes (uuid, uuid_post, uuid_user)
                   VALUES (:uuid, :uuid_post, :uuid_user)'
    );


    $newLikeUuid = (string)$like->getUuid();
    $this->logger->info("Like It: $newLikeUuid");

    $statement->execute([
        ':uuid' => $newLikeUuid,
        ':uuid_post' => $like->getPost()->getUuid(),
        ':uuid_user' => $like->getUser()->getUuid(),
    ]);
}

public function get(UUID $uuid): Like
{
    $statement = $this->connection->prepare(
        'SELECT * FROM likes WHERE uuid = :uuid_post'
    );
    $statement->execute(
        [':uuid_post' => (string)$uuid]
    );

    return $this->getByPostUuid($statement, $uuid);
}

    /**
     * @throws LikeNotFoundException
     * @throws InvalidArgumentException
     */
    public function getByPostUuid(\PDOStatement $statement, string $uuid): Like
{
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            $this->logger->warning("Like not found: $uuid");
        throw new LikeNotFoundException(
            "Cannot find post: $uuid"
        );
    }

    $postRepository = new SqlitePostsRepository($this->connection, $this->logger);
    $userRepository = new SqliteUsersRepository($this->connection, $this->logger);
    $post = $postRepository->get(new UUID($result['uuid_post']));
    $user = $userRepository->get(new UUID($result['uuid_user']));

    return new Like(
        new UUID($result['uuid']),
        $post,
        $user);
}

}