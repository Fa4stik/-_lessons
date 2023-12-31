<?php

namespace myHttp\Actions\Likes;

use myHttp\Actions\ActionInterface;
use myHttp\ErrorResponse;
use myHttp\Request;
use myHttp\Response;
use myHttp\SuccessfullResponse;
use src\Model\PostLike;
use src\Model\UUID;
use src\Repositories\PostLikeRepository;

class CreatePostLike implements ActionInterface
{
    public function __construct(
        private PostLikeRepository $postLikeRepository
    )
    {

    }

    public function handle(Request $request): Response
    {
        try {
            $data = $request->body(['post_uuid', 'user_uuid']);
            $uuid = UUID::random();
            $postUuid = new UUID($data['post_uuid']);
            $userUuid = new UUID($data['user_uuid']);

            $post = new PostLike($uuid, $postUuid, $userUuid);
            $this->postLikeRepository->save($post);

            return new SuccessfullResponse(['message' => 'Post like created successfully']);
        } catch (\Exception $ex) {
            return new ErrorResponse($ex->getMessage());
        }
    }
}