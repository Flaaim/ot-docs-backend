<?php

namespace App\Http\Action\Cart\RemoveItem;

use App\Cart\Command\RemoveItem\Command;
use App\Cart\Command\RemoveItem\Handler;
use App\Http\EmptyResponse;
use App\Http\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private Handler $handler,
        private Validator $validator
    )
    {

    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody() ?? [];

        $command = new Command($data['productId'] ?? '', $data['cartId'] ?? '');

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(200);
    }
}