<?php

namespace App\Shared\Domain\ValueObject;

use App\Payment\Entity\Email;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Recipient
{
    /** @var ArrayCollection<File> $attachments */
    private Collection $attachments;
    public function __construct(
        private readonly Email $email,
        private readonly string $subject
    ){
        $this->attachments = new ArrayCollection();
    }
    public function addAttachment(File $file): void
    {
        $this->attachments->add($file);
    }
    public function getAttachments(): ArrayCollection
    {
        return $this->attachments;
    }
    public function getSubject(): string
    {
        return $this->subject;
    }
    public function getEmail(): Email
    {
        return $this->email;
    }
}