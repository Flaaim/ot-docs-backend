<?php

declare(strict_types=1);

use App\Flusher;
use App\Payment\Command\HookPayment\Handler as HookPaymentHandler;
use App\Payment\Command\HookPayment\SendProduct\Handler;
use App\Payment\Entity\PaymentRepository;
use App\Payment\Service\Delivery\DeliveryFactory;
use App\Shared\Domain\Service\Payment\WebhookParser\YookassaWebhookParser;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Test\Functional\Payment\TestPaymentProvider;

return [
    HookPaymentHandler::class => function (ContainerInterface $c) {
        $yookassaWebhookParser = new YookassaWebhookParser();
        $yookassaProvider = $c->get(TestPaymentProvider::class);
        $em = $c->get(EntityManagerInterface::class);
        $deliveryFactory = $c->get(DeliveryFactory::class);

        return new HookPaymentHandler(
            $yookassaWebhookParser,
            $yookassaProvider,
            new PaymentRepository($em),
            new Flusher($em),
            new Handler(
                $deliveryFactory,
                $c->get(EventDispatcher::class)
            ),
            $c->get(LoggerInterface::class),
        );
    },
];
