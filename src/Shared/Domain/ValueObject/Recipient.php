<?php

namespace App\Shared\Domain\ValueObject;

use App\Payment\Entity\Email;
use App\Shared\Domain\Service\Template\TemplateManager;
use Doctrine\Common\Collections\ArrayCollection;

class Recipient
{
    /** @var ArrayCollection<TemplateManager> $attachments */
    private ArrayCollection $attachments;
    private Email $email;
    public function __construct(Email $email, ArrayCollection $attachments)
    {
        $this->email = $email;
        $this->attachments = $attachments;
    }
    public function getAttachments(): ArrayCollection
    {
        return $this->attachments;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }
}