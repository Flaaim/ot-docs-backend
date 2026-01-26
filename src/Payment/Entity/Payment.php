<?php

namespace App\Payment\Entity;

use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'payments')]
class Payment
{
    #[ORM\Column(type:'string', length: 255, nullable: true)]
    private ?string $externalId = null;
    #[ORM\Column(type:'status')]
    private Status $status;
    #[ORM\Column(type: 'boolean')]
    private bool $isSend = false;
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type:'id', length: 255)]
        private readonly Id $id,
        #[ORM\Column(type: 'email')]
        private readonly Email $email,
        #[ORM\Column(type:'string', length: 255)]
        private readonly string $sourcePaymentId,
        #[ORM\Column(type:'string', length: 25)]
        private readonly string $type,
        #[ORM\Column(type:'price')]
        private readonly Price $price,
        #[ORM\Column(type:'datetime_immutable')]
        private readonly \DateTimeImmutable $createdAt,
        #[ORM\Embedded(class: Token::class)]
        private readonly Token $returnToken
    )
    {
        $this->status = Status::pending();
    }
    public function getId(): Id
    {
        return $this->id;
    }
    public function getStatus(): Status
    {
        return $this->status;
    }
    public function getEmail(): Email
    {
        return $this->email;
    }
    public function getSourcePaymentId(): string
    {
        return $this->sourcePaymentId;
    }
    public function getType(): string
    {
        return $this->type;
    }
    public function getPrice(): Price
    {
        return $this->price;
    }
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function setExternalId(string $externalId): void
    {
        $this->externalId = $externalId;
    }
    public function getExternalId(): string
    {
        return $this->externalId;
    }
    public function getReturnToken(): Token
    {
        return $this->returnToken;
    }
    public function setStatus(Status $newStatus): void
    {
        if ($this->status->getValue() === $newStatus->getValue()) {
            throw new \DomainException('Status already set');
        }
        $this->status = $newStatus;
    }
    public function validateToken(string $token, \DateTimeImmutable $date): void
    {
        $this->returnToken->validate($token, $date);
    }
    public function isSend(): bool
    {
        return $this->isSend;
    }
    public function setSuccess(Status $newStatus): void
    {
        $this->setStatus($newStatus);
        $this->isSend = true;
    }
}
