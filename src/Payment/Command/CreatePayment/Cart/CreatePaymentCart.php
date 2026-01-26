<?php

namespace App\Payment\Command\CreatePayment\Cart;

use App\Cart\Entity\CartRepository;
use App\Payment\Command\CreatePayment\CreatePaymentInterface;
use App\Payment\Command\CreatePayment\Request\Command;
use App\Payment\Entity\Email;
use App\Payment\Entity\Payment;
use App\Payment\Entity\PaymentType;
use App\Payment\Entity\Token;
use App\Product\Entity\Currency;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
use Ramsey\Uuid\Uuid;

class CreatePaymentCart implements CreatePaymentInterface
{
    public function __construct(
        private readonly CartRepository $carts
    ){
    }
    public function preparePayment(Command $command): Payment
    {
       $cart = $this->carts->find(new Id($command->sourcePaymentId));
       if(null === $cart){
           throw new \DomainException('Cart to create payment not found.');
       }
       $price = new Price($cart->getTotalPrice()->getValue(), new Currency('RUB'));
       $returnToken = new Token(Id::generate(), new \DateTimeImmutable('+ 1 hour'));

       return new Payment(
           new Id(Uuid::uuid4()->toString()),
           new Email($command->email),
           $command->sourcePaymentId,
           $command->paymentType,
           $price,
           new \DateTimeImmutable(),
           $returnToken
       );
    }

    public function supports(string $paymentType): bool
    {
        return PaymentType::CART->value === $paymentType;
    }
}