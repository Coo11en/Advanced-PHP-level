<?php

namespace App\courseProject\Blog\Http\Auth;

use App\courseProject\Blog\Http\Request;
use App\courseProject\Person\User;

interface AuthenticationInterface
{
// Контракт описывает единственный метод,
// получающий пользователя из запроса
    public function user(Request $request): User;
}