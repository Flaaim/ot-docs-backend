<?php

namespace App\Payment\Test\Entity;

use App\Payment\Entity\Email;
use App\Payment\Entity\Payment;
use App\Payment\Entity\Token;
use App\Product\Entity\Currency;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
use PHPUnit\Framework\TestCase;


class PaymentTest extends TestCase
{
    public function testSuccess(): void
    {
        $payment = new Payment(
            $id = Id::generate(),
            $email = new Email('some@email.ru'),
            $sourcePaymentId = Id::generate(),
            $type = 'cart',
            $price = new Price(350.00, new Currency('RUB')),
            $date = new \DateTimeImmutable('now'),
            $token = new Token(Id::generate(), new \DateTimeImmutable('+ 1 hour')),
        );

        self::assertEquals($id->getValue(), $payment->getId()->getValue());
        self::assertEquals($email->getValue(), $payment->getEmail()->getValue());
        self::assertEquals($sourcePaymentId, $payment->getSourcePaymentId());
        self::assertEquals($price->getValue(), $payment->getPrice()->getValue());
        self::assertEquals($date, $payment->getCreatedAt());
        self::assertEquals($token->getValue(), $payment->getReturnToken()->getValue());
        self::assertEquals($type, $payment->getType());
    }
}