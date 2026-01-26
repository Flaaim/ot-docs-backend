<?php

declare(strict_types=1);


use App\Payment\Service\Delivery\DeliveryFactory;
use App\Payment\Service\Delivery\FormDelivery\FormDelivery;
use App\Payment\Service\Delivery\FormDelivery\ProductSender;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Template\TemplatePath;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

return [
    DeliveryFactory::class => function (ContainerInterface $container) {
        return new DeliveryFactory([$container->get(FormDelivery::class)]);
    },
    FormDelivery::class => function (ContainerInterface $container) {
        $em = $container->get(EntityManagerInterface::class);
        return new FormDelivery($container->get(ProductSender::class), new ProductRepository($em));
    },
    ProductSender::class => function (ContainerInterface $container) {
        return new ProductSender(
            $container->get(MailerInterface::class),
            $container->get(TemplatePath::class),
            $container->get(Environment::class),
            $container->get(LoggerInterface::class),
        );
    }
];