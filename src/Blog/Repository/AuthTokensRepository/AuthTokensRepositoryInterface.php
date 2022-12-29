<?php

namespace App\courseProject\Blog\Repository\AuthTokensRepository;

use App\courseProject\Blog\AuthToken;

interface AuthTokensRepositoryInterface
{
        // Метод сохранения токена
    public function save(AuthToken $authToken): void;
        // Метод получения токена
    public function get(string $token): AuthToken;
}