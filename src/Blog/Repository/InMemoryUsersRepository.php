<?php

namespace App\courseProject\Blog\Repository;



use App\courseProject\Blog\Exceptions\UserNotFoundException;
use App\courseProject\Blog\Repository\Interface\UsersRepositoryInterface;
use App\courseProject\Blog\UUID;
use App\courseProject\Person\User;

class InMemoryUsersRepository implements UsersRepositoryInterface
{

    private array $users = [];


    public function save(User $user): void
    {
        $this->users[] = $user;
    }

    /**
     * @param UUID $id
     * @return User
     * @throws UserNotFoundException
     */
    public function get(UUID $id): User
    {
        foreach ($this->users as $user) {
            if ($user->id() === $id) {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found: $id");
    }
}