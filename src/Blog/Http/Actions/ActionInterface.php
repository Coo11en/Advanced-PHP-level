<?php

namespace App\courseProject\Blog\Http\Actions;

use App\courseProject\Blog\Http\Request;
use App\courseProject\Blog\Http\Response;

interface ActionInterface
{
    public function handle(Request $request): Response;
}