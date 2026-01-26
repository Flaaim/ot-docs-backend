<?php

namespace App\Payment\Service\Delivery;

use App\Payment\Entity\Email;
use Illuminate\Contracts\Mail\Attachable;

interface SenderInterface
{
    public function send(Email $email, Attachable $data): void;
}