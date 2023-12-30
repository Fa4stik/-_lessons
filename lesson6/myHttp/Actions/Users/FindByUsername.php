<?php
namespace myHttp\Actions\Users;

use myHttp\Actions\ActionInterface;
use myHttp\ErrorResponse;
use myHttp\SuccessfullResponse;
use myHttp\Request;
use myHttp\Response;
use src\Exceptions\HttpException;
use src\Exceptions\UserNotFoundException;
use src\Repositories\UserRepository;

class FindByUsername implements ActionInterface
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function handle(Request $request): Response
    {
        try {
            $username = $request->query('username');
        } catch (HttpException $ex) {
            return new ErrorResponse($ex->getMessage());
        }

        try {
            $user = $this->userRepository->getByUsername($username);
        } catch (UserNotFoundException $ex) {
            return new ErrorResponse($ex->getMessage());
        }

        return new SuccessfullResponse([
            'username' => $user->getUsername(),
            'name' => (string)$user->getName()
        ]);
    }
}