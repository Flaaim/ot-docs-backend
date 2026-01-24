<?php

namespace App\Http\Action\Payment\CreatePayment;

use App\Http\JsonResponse;
use App\Http\Validator\Validator;
use App\Payment\Command\CreatePayment\CreatorFromCart;
use App\Payment\Command\CreatePayment\CreatePaymentCommand;
use App\Payment\Command\CreatePayment\CreatePaymentFactory;
use App\Payment\Command\CreatePayment\Form\Handler as FormHandler;
use App\Payment\Command\CreatePayment\Cart\Handler as CartHandler;
use App\Payment\Command\CreatePayment\CreatorFromForm;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly FormHandler $formHandler,
        private readonly CartHandler $cartHandler,
        private readonly Validator $validator
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody() ?? [];

        $command = new CreatePaymentCommand(
            $data['email'] ?? '',
            $data['sourcePaymentId'] ?? '',
            $data['paymentType'] ?? '',
        );

        $this->validator->validate($command);

        $factory = new CreatePaymentFactory([
            new CreatorFromForm($this->formHandler),
            new CreatorFromCart($this->cartHandler)
        ]);

        $response = $factory->createPayment($command);

        return new JsonResponse($response, 201);
    }
}
