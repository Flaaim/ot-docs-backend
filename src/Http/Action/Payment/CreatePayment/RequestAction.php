<?php

namespace App\Http\Action\Payment\CreatePayment;

use App\Http\JsonResponse;
use App\Http\Validator\Validator;
use App\Payment\Command\CreatePayment\Factory;
use App\Payment\Command\CreatePayment\Request\Command;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly Factory   $factory,
        private readonly Validator $validator
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody() ?? [];

        $command = new Command(
            $data['email'] ?? '',
            $data['sourcePaymentId'] ?? '',
            $data['paymentType'] ?? '',
        );

        $this->validator->validate($command);

        $response = $this->factory->createPayment($command);

        return new JsonResponse($response, 201);
    }
}
