<?php

use App\Flusher;
use App\Payment\Command\HookPayment\Handler as HookPaymentHandler;
use App\Payment\Command\HookPayment\SendProduct\Handler as SendProductHandler;
use App\Payment\Entity\PaymentRepository;
use App\Payment\Service\Delivery\DeliveryFactory;
use App\Payment\Service\Delivery\DeliveryService;
use App\Payment\Service\Delivery\FormDelivery\ProductSender;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Payment\Provider\YookassaProvider;
use App\Shared\Domain\Service\Payment\WebhookParser\YookassaWebhookParser;
use App\Shared\Domain\Service\Template\TemplatePath;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

return [
    HookPaymentHandler::class => function (ContainerInterface $c) {
        $yookassaWebhookParser = new YookassaWebhookParser();
        $yookassaProvider = $c->get(YookassaProvider::class);

        $logger = $c->get(LoggerInterface::class);
        $deliveryFactory = $c->get(DeliveryFactory::class);

        $em = $c->get(EntityManagerInterface::class);

        $sendProductHandler = new SendProductHandler(
            $deliveryFactory,
            $c->get(EventDispatcher::class)
        );

        return new HookPaymentHandler(
            $yookassaWebhookParser,
            $yookassaProvider,
            new PaymentRepository($em),
            new Flusher($em),
            $sendProductHandler,
            $logger,
        );
    },
];
