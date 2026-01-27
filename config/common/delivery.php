<?php

declare(strict_types=1);


use App\Payment\Service\Delivery\DeliveryFactory;
use App\Payment\Service\Delivery\FormDelivery\FormDelivery;
use App\Payment\Service\Delivery\Sender;
use App\Payment\Service\Delivery\SenderInterface;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Template\TemplatePath;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

return [
    DeliveryFactory::class => function (ContainerInterface $container) {
        return new DeliveryFactory(
            [
                $container->get(FormDelivery::class)
            ],
            $container->get(Sender::class));
    },
    FormDelivery::class => function (ContainerInterface $container) {
        $em = $container->get(EntityManagerInterface::class);

        return new FormDelivery(
            new ProductRepository($em),
            $container->get(TemplatePath::class),
        );
    },
    Sender::class => function (ContainerInterface $container) {
        return new Sender(
            $container->get(MailerInterface::class),
            $container->get(Environment::class),
            $container->get(LoggerInterface::class),
        );
    }
];