<?php

namespace App\Payment\Test\Command\CreatePayment;

use App\Flusher;
use App\Payment\Command\CreatePayment\CreatePaymentHandler;
use App\Payment\Entity\Email;
use App\Payment\Entity\PaymentRepository;
use App\Product\Entity\Currency;
use App\Shared\Domain\Service\Payment\DTO\PaymentInfoDTO;
use App\Shared\Domain\Service\Payment\Provider\YookassaProvider;
use App\Shared\Domain\ValueObject\Price;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Test\Functional\Payment\PaymentBuilder;

class CreatePaymentHandlerTest extends TestCase
{
    public function testSuccess(): void
    {
        $handler = new CreatePaymentHandler(
            $this->createMock(Flusher::class),
            $provider = $this->createMock(YookassaProvider::class),
            $this->createMock(PaymentRepository::class),
            $this->createMock(LoggerInterface::class),
        );
        $payment = (new PaymentBuilder())
            ->withSourcePaymentId('a161ea0e-4c62-4950-a620-3169b0e4b244')
            ->withEmail(new Email('test@email.ru'))
            ->withPrice(new Price(350.00, new Currency('RUB')))
            ->build();

        $provider->expects($this->once())
            ->method('initiatePayment')
            ->willReturn(
                new PaymentInfoDTO(
                    'bfa12722-7fa7-4aef-b266-d4712da86a1c',
                    'pending',
                    '/some-redirect-url'
                )
            );

        $response = $handler->handle($payment);

        self::assertEquals(350.00, $response->amount);
        self::assertEquals('RUB', $response->currency);
        self::assertEquals('pending', $response->status);
        self::assertEquals('/some-redirect-url', $response->returnUrl);
    }
}