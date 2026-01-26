<?php

namespace App\Payment\Command\CreatePayment\Form;

use App\Payment\Command\CreatePayment\CreatePaymentInterface;
use App\Payment\Command\CreatePayment\Request\Command;
use App\Payment\Entity\Email;
use App\Payment\Entity\Payment;
use App\Payment\Entity\PaymentType;
use App\Payment\Entity\Token;
use App\Product\Entity\Currency;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
use Ramsey\Uuid\Uuid;

class CreatePaymentForm implements CreatePaymentInterface
{
    public function __construct(
        private readonly ProductRepository $products
    ){
    }
    public function preparePayment(Command $command): Payment
    {
        $product = $this->products->get(new Id($command->sourcePaymentId));
        $price = new Price($product->getPrice()->getValue(), new Currency('RUB'));
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
        return PaymentType::FORM->value === $paymentType;
    }


}