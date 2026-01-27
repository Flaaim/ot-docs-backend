<?php

namespace App\Payment\Service\Delivery;

use App\Shared\Domain\ValueObject\Recipient;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use Twig\Environment;

class Sender implements SenderInterface
{
    private MailerInterface $mailer;
    private Environment $twig;
    private LoggerInterface $logger;
    public function __construct(MailerInterface $mailer, Environment $twig, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->logger = $logger;
    }
    public function send(Recipient $recipient): void
    {
        $message = new Email();
        $message->subject($recipient->getSubject());
        $message->to($recipient->getEmail()->getValue());
        $message->html(
            $this->twig->render('mail/template.html.twig')
        );
        foreach ($recipient->getAttachments() as $attachment) {
            $message->addPart(
                new DataPart(
                    new File($attachment->getFile())
                )
            );
        }
        try {
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('Failed to send mail: ', ['error' => $e->getMessage()]);
            throw new TransportException($e->getMessage());
        }
    }
}
