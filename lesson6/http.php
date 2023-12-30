<?php

require 'vendor/autoload.php';

use myHttp\Actions\Comments\CreateComment;
use myHttp\Actions\Posts\CreatePost;
use myHttp\Actions\Posts\DeletePost;
use myHttp\Actions\Users\FindByUsername;
use myHttp\ErrorResponse;
use myHttp\Request;
use src\Repositories\CommentRepository;
use src\Repositories\PostRepository;
use src\Repositories\UserRepository;


ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $request = new Request($_GET, $_POST, $_SERVER);
} catch (Exception $ex) {
    (new ErrorResponse($ex->getMessage()))->send();
}

try {
    $path = $request->path();
} catch (Exception $ex) {
    (new ErrorResponse($ex->getMessage()))->send();
}

$routs = [
    '/users/show' => new FindByUsername(
        new UserRepository(
            new PDO('sqlite:'.__DIR__.'/db/blog.sqlite')
        )
    ),
    '/posts/comment' => new CreateComment(
        new CommentRepository(
            new PDO('sqlite:'.__DIR__.'/db/blog.sqlite')
        )
    ),
    '/posts/create' => new CreatePost(
        new PostRepository(
            new PDO('sqlite:'.__DIR__.'/db/blog.sqlite')
        )
    ),
    '/posts' => new DeletePost(
        new PostRepository(
            new PDO('sqlite:'.__DIR__.'/db/blog.sqlite')
        )
    )
];

if (!array_key_exists($path, $routs)) {
    (new ErrorResponse('Not found path'))->send();
}

$action = $routs[$path];

try {
    $response = $action->handle($request);
} catch (Exception $ex) {
    (new ErrorResponse($ex->getMessage()))->send();
}

$response->send();