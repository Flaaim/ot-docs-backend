<?php

declare(strict_types=1);


use App\Cart\Entity\CartRepository;
use App\Flusher;
use App\Payment\Command\CreatePayment\Cart\CreatePaymentCart;
use App\Payment\Command\CreatePayment\Factory;
use App\Payment\Command\CreatePayment\Form\CreatePaymentForm;
use App\Payment\Command\CreatePayment\Request\Handler;
use App\Payment\Entity\PaymentRepository;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Payment\Provider\YookassaProvider;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    Factory::class => function (ContainerInterface $container) {
        return new Factory($container->get(Handler::class), [
            $container->get(CreatePaymentForm::class),
            $container->get(CreatePaymentCart::class),
        ]);
    },
    Handler::class => function (ContainerInterface $container) {
        $yookassaProvider = $container->get(YookassaProvider::class);
        $em = $container->get(EntityManagerInterface::class);

        return new Handler(
            new Flusher($em),
            $yookassaProvider,
            new PaymentRepository($em),
            $container->get(LoggerInterface::class),
        );
    },
    CreatePaymentForm::class => function (ContainerInterface $container) {
        $em = $container->get(EntityManagerInterface::class);
        return new CreatePaymentForm(new ProductRepository($em));
    },
    CreatePaymentCart::class => function (ContainerInterface $container) {
        $em = $container->get(EntityManagerInterface::class);
        return new CreatePaymentCart(new CartRepository($em));
    }

];