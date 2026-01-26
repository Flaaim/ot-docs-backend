<?php

namespace App\Command;

use App\Payment\Entity\Email;
use App\Payment\Service\Delivery\FormDelivery\ProductSender;
use App\Product\Entity\Currency;
use App\Product\Entity\Product;
use App\Shared\Domain\Service\Template\TemplatePath;
use App\Shared\Domain\ValueObject\File;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
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

            $productSender = new ProductSender(
                $container->get(MailerInterface::class),
                new TemplatePath(sys_get_temp_dir()),
                $container->get(Environment::class),
                $this->logger
            );
            $tempFile = tempnam(sys_get_temp_dir(), 'template');
            $productSender->send(
                new Email('test@app.ru'),
                new Product(
                    Id::generate(),
                    'Образцы документов СИЗ',
                    new Price(450.00, new Currency('RUB')),
                    new File(basename($tempFile)),
                    '1',
                    '161'
                ),
            );
            return self::SUCCESS;
        } catch (TransportExceptionInterface $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return self::FAILURE;
        }
    }
}
