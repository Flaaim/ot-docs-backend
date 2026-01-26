<?php

namespace App\Payment\Command\CreatePayment\Request;

use App\Flusher;
use App\Payment\Command\CreatePayment\Response;
use App\Payment\Entity\Payment;
use App\Payment\Entity\PaymentRepository;
use App\Payment\Entity\Status;
use App\Shared\Domain\Service\Payment\DTO\MakePaymentDTO;
use App\Shared\Domain\Service\Payment\PaymentException;
use App\Shared\Domain\Service\Payment\Provider\YookassaProvider;
use Psr\Log\LoggerInterface;


class Handler
{
    public function __construct(
        protected readonly Flusher $flusher,
        protected readonly YookassaProvider $yookassaProvider,
        protected readonly PaymentRepository $payments,
        protected readonly LoggerInterface $logger
    ){
    }

    public function handle(Payment $payment): Response
    {
        try {
            $paymentInfo = $this->yookassaProvider->initiatePayment(
                new MakePaymentDTO(
                    $payment->getPrice()->getValue(),
                    $payment->getPrice()->getCurrency()->getValue(),
                    'Платеж',
                    $payment->getReturnToken()->getValue(),
                    [
                        'email' => $payment->getEmail()->getValue(),
                        'sourcePaymentId' => $payment->getSourcePaymentId(),
                        'type' => $payment->getType()
                    ],
                    $payment->getEmail()->getValue(),
                )
            );
            $payment->setExternalId($paymentInfo->paymentId);
        } catch (PaymentException $e) {
            $this->logger->error('Failed to create payment: ', ['error' => $e->getMessage()]);
            $payment->setStatus(Status::cancelled());

            $this->payments->create($payment);

            $this->flusher->flush();
            throw $e;
        }

        $this->payments->create($payment);
        $this->flusher->flush();

        return new Response(
            $payment->getPrice()->getValue(),
            $payment->getPrice()->getCurrency()->getValue(),
            $payment->getStatus()->getValue(),
            $paymentInfo->redirectUrl,
        );
    }
}