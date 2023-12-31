<?php

use myHttp\Actions\Comments\CreateComment;
use myHttp\Actions\Likes\CreateCommentLike;
use myHttp\Actions\Likes\CreatePostLike;
use myHttp\Actions\Likes\GetByUuidCommentLikes;
use myHttp\Actions\Likes\GetByUuidPostLikes;
use myHttp\Actions\Posts\CreatePost;
use myHttp\Actions\Posts\DeletePost;
use myHttp\Actions\Users\FindByUsername;
use myHttp\ErrorResponse;
use myHttp\Request;


ini_set('display_errors', 1);
error_reporting(E_ALL);

$container = require __DIR__ . '/bootstrap.php';

try {
    $request = new Request($_GET, $_POST, $_SERVER);
} catch (Exception $ex) {
    (new ErrorResponse($ex->getMessage()))->send();
    return;
}

try {
    $path = $request->path();
} catch (Exception $ex) {
    (new ErrorResponse($ex->getMessage()))->send();
    return;
}

try {
    $method = $request->method();
} catch (Exception $ex) {
    (new ErrorResponse($ex->getMessage()))->send();
    return;
}

$routs = [
    'GET' => [
        '/users/show' => FindByUsername::class,
        '/likes/comment' => GetByUuidCommentLikes::class,
        '/likes/post' => GetByUuidPostLikes::class,
    ],
    'POST' => [
        '/posts/comment' => CreateComment::class,
        '/posts/' => CreatePost::class,
        '/likes/post/' => CreatePostLike::class,
        '/likes/comment/' => CreateCommentLike::class
    ],
    'DELETE' => [
        '/posts' => DeletePost::class
    ]
];

if (!array_key_exists($method, $routs) || !array_key_exists($path, $routs[$method])) {
    (new ErrorResponse('Not found path'))->send();
    return;
}

$actionClassName = $routs[$method][$path];

$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);
} catch (Exception $ex) {
    (new ErrorResponse($ex->getMessage()))->send();
    return;
}

$response->send();

// lesson 7 commit