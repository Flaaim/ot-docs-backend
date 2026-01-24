<?php

namespace App\Payment\Entity;

enum PaymentType: string
{
    case FORM = 'form';
    case CART = 'cart';
}
