<?php

namespace App\Command;

use App\Payment\Entity\Email;
use App\Payment\Service\Delivery\Sender;
use App\Product\Entity\Currency;
use App\Product\Entity\Product;
use App\Shared\Domain\Service\Template\TemplatePath;
use App\Shared\Domain\ValueObject\File;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
use App\Shared\Domain\ValueObject\Recipient;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class ProductSendCommand extends Command
{
    public function __construct(private LoggerInterface $logger)
    {
        parent::__construct();
    }
    public function configure(): void
    {
        $this->setName('product:send');
        $this->setDescription('Send product message');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $container = require __DIR__ . '/../../config/container.php';

            $productSender = new Sender(
                $container->get(MailerInterface::class),
                $container->get(Environment::class),
                $this->logger
            );
            $productSender->send(new Recipient(new Email('some@email.ru'), 'Проверка письма'));
            return self::SUCCESS;
        } catch (TransportExceptionInterface $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return self::FAILURE;
        }
    }
}
