<?php

namespace App\Http\Action\Cart\AddItem;

use App\Cart\Command\AddItem\Command;
use App\Http\EmptyResponse;
use App\Http\Validator\Validator;
use App\Cart\Command\AddItem\Handler;
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
        $data = $request->getParsedBody() ?? [];

        $command = new Command(
            $data['productId'] ?? '',
                $data['cartId'] ?? ''
        );

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(201);
    }

}