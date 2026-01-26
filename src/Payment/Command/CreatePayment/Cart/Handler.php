<?php

namespace App\Payment\Command\CreatePayment\Cart;

use App\Cart\Entity\CartRepository;
use App\Flusher;
use App\Payment\Command\CreatePayment\CreatePaymentResponse;
use App\Payment\Entity\Email;
use App\Payment\Entity\Payment;
use App\Payment\Entity\PaymentRepository;
use App\Payment\Entity\PaymentType;
use App\Payment\Entity\Status;
use App\Payment\Entity\Token;
use App\Product\Entity\Currency;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Payment\DTO\MakePaymentDTO;
use App\Shared\Domain\Service\Payment\PaymentException;
use App\Shared\Domain\Service\Payment\Provider\YookassaProvider;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class Handler
{
    public function __construct(
        private readonly Flusher $flusher,
        private readonly ProductRepository $products,
        private readonly CartRepository $carts,
        private readonly YookassaProvider $yookassaProvider,
        private readonly PaymentRepository $payments,
        private readonly LoggerInterface $logger
    ) {
    }

    public function handle(Command $command): CreatePaymentResponse
    {
        $email = new Email($command->email);
        $cart = $this->carts->find(new Id($command->cartId));
        if(null === $cart) {
            throw new \DomainException('Cart not found.');
        }
        $returnToken = new Token(Id::generate(), new DateTimeImmutable('+ 1 hour'));
        $payment = new Payment(
            new Id(Uuid::uuid4()->toString()),
            $email,
            $command->cartId,
            $cart->getTotalPrice(),
            new DateTimeImmutable(),
            $returnToken
        );
        try{

            $paymentInfo = $this->yookassaProvider->initiatePayment(
                new MakePaymentDTO(
                    $payment->getPrice()->getValue(),
                    $payment->getPrice()->getCurrency()->getValue(),
                    'Оплата картой',
                    $payment->getReturnToken()->getValue(),
                    [
                        'email' => $email->getValue(),
                        'cartId' => $cart->getId()->getValue(),
                        'type' => PaymentType::CART->value
                    ],
                    $email->getValue(),
                )
            );
        } catch (PaymentException $e) {
            $this->logger->error('Failed to create payment: ', ['error' => $e->getMessage()]);
            $payment->setStatus(Status::cancelled());

            $this->payments->create($payment);

            $this->flusher->flush();
            throw $e;
        }
        $this->payments->create($payment);
        $this->flusher->flush();

        return new CreatePaymentResponse(
            $payment->getPrice()->getValue(),
            $payment->getPrice()->getCurrency()->getValue(),
            $payment->getStatus()->getValue(),
            $paymentInfo->redirectUrl,
        );
    }
}