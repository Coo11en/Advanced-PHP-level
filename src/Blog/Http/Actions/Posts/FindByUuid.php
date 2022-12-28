<?php

namespace App\courseProject\Blog\Http\Actions\Posts;

use App\courseProject\Blog\Exceptions\HttpException;
use App\courseProject\Blog\Exceptions\PostNotFoundException;
use App\courseProject\Blog\Http\Actions\ActionInterface;
use App\courseProject\Blog\Http\ErrorResponse;
use App\courseProject\Blog\Http\Request;
use App\courseProject\Blog\Http\Response;
use App\courseProject\Blog\Http\SuccessfulResponse;
use App\courseProject\Blog\Repository\Interface\PostsRepositoryInterface;
use App\courseProject\Blog\UUID;

class FindByUuid implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    )
    {

    }
        public function handle(Request $request): Response
    {
        try {
            $uuid = $request->query('uuid');

        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $post = $this->postsRepository->get(new UUID($uuid));
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
           'title' => $post->getHeader(),
           'text' => $post->getText()
        ]);
    }
}