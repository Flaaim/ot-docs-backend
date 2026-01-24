<?php

namespace App\Http\Action\Cart\Get;

use App\Cart\Command\GetCart\Command;
use App\Cart\Command\GetCart\Handler;
use App\Http\JsonResponse;
use App\Http\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly Handler $handler,
        private readonly Validator $validator
    )
    {

    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getQueryParams()['id'] ?? '';

        $command = new Command($id);

        $this->validator->validate($command);

        $response = $this->handler->handle($command);

        return new JsonResponse($response);
    }
}