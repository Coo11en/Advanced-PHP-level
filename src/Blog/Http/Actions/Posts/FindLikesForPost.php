<?php

namespace App\courseProject\Blog\Http\Actions\Posts;

use App\courseProject\Blog\Exceptions\LikeNotFoundException;
use App\courseProject\Blog\Http\Actions\ActionInterface;
use App\courseProject\Blog\Http\Request;
use App\courseProject\Blog\Http\Response;
use App\courseProject\Blog\Http\ErrorResponse;
use App\courseProject\Blog\Http\SuccessfulResponse;
use App\courseProject\Blog\Repository\Interface\LikesRepositoryInterface;
use App\courseProject\Blog\UUID;

class FindLikesForPost implements ActionInterface
{
    public function __construct(
        private LikesRepositoryInterface $likesRepository
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
            $like = $this->likesRepository->get(new UUID($uuid));
        } catch (LikeNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $likeUuid = new UUID($uuid);

        return new SuccessfulResponse([
            'uuid' => (string)$likeUuid
        ]);
    }
}