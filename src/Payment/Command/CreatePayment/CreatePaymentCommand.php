<?php

namespace App\Payment\Command\CreatePayment;

use Symfony\Component\Validator\Constraints as Assert;
class CreatePaymentCommand
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $sourcePaymentId,
        #[Assert\Choice(choices: ['form', 'cart'])]
        public string $paymentType,
    )
    {}
}