<?php

namespace App\courseProject\Blog\Repository\AuthTokensRepository;

use App\courseProject\Blog\AuthToken;
use PDO;
use PDOException;
use DateTimeImmutable;
use App\courseProject\Blog\UUID;
use App\courseProject\Blog\Exceptions\AuthTokenNotFoundException;
use App\courseProject\Blog\Exceptions\AuthTokensRepositoryException;
use DateTimeInterface;
use App\courseProject\Blog\Repository\AuthTokensRepository\AuthTokensRepositoryInterface;

class SqliteAuthTokensRepository implements AuthTokensRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ){
    }

    public function save(AuthToken $authToken): void
    {
        $query = <<<'SQL'
            INSERT INTO tokens (
                token,
                user_uuid,
                expires_on
            ) VALUES (
                :token,
                :user_uuid,
                :expires_on
            )
            ON CONFLICT (token) DO UPDATE SET 
                expires_on = :expires_on 
SQL;
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute([
                ':token' => (string)$authToken->token(),
                ':user_uuid' => (string)$authToken->userUuid(),
                ':expires_on' => $authToken->expiresOn()
                    ->format(DateTimeInterface::ATOM),
            ]);
        } catch (PDOException $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(), (int)$e->getCode(), $e
            );
        }
    }

    public function get(string $token): AuthToken
    {
        try {
            $statement = $this->connection->prepare(
                'SELECT * FROM tokens WHERE token = ?'
            );
            $statement->execute([$token]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(), (int)$e->getCode(), $e
            );
        }
        if (false === $result) {
            throw new AuthTokenNotFoundException("Cannot find token: $token");
        }
        try {
            return new AuthToken(
                $result['token'],
                new UUID($result['user_uuid']),
                new DateTimeImmutable($result['expires_on'])
            );
        } catch (Exception $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(), $e->getCode(), $e
            );
        }

    }



    //////////////////////////////////////////////////////
    public function getUserUuid(string $uuid): string
    {
        try {
            $statement = $this->connection->prepare(
                'SELECT * FROM tokens WHERE user_uuid = ? ORDER BY expires_on DESC'
            );
            $statement->execute([$uuid]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(), (int)$e->getCode(), $e
            );
        }
        if (false === $result) {
            throw new AuthTokenNotFoundException("Cannot find uuid: $uuid");
        }
        try {
            return $result['token'];
        } catch (Exception $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(), $e->getCode(), $e
            );
        }

    }
}