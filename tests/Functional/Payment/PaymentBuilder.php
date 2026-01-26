<?php

namespace Test\Functional\Payment;

use App\Payment\Entity\Email;
use App\Payment\Entity\Payment;
use App\Payment\Entity\Status;
use App\Payment\Entity\Token;
use App\Product\Entity\Currency;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class PaymentBuilder
{
    private Id $id;
    private ?string $externalId = null;
    private ?Status $status = null;
    private Email $email;
    private string $sourcePaymentId;
    private string $type;
    private Price $price;
    private Token $returnToken;
    private \DateTimeImmutable $createdAt;
    private bool $isSend = false;

    public function __construct()
    {
        $this->id = new Id(Uuid::uuid4()->toString());
        $this->email = new Email('test@app.ru');
        $this->sourcePaymentId = "b38e76c0-ac23-4c48-85fd-975f32c8801f";
        $this->price = new Price(450.00, new Currency('RUB'));
        $this->createdAt = new DateTimeImmutable('now');
        $this->returnToken = new Token('392b1c38-f3e4-4533-a6cb-5b4e7c08d91f', new DateTimeImmutable('+ 1 hour'));
        $this->type = 'form';
    }

    public function withEmail(Email $email): self
    {
        $this->email = $email;
        return $this;
    }
    public function withSourcePaymentId(string $sourcePaymentId): self
    {
        $this->sourcePaymentId = $sourcePaymentId;
        return $this;
    }
    public function withType(string $type): self
    {
        $this->type = $type;
        return $this;
    }
    public function withPrice(Price $price): self
    {
        $this->price = $price;
        return $this;
    }
    public function withToken(Token $token): self
    {
        $this->returnToken = $token;
        return $this;
    }
    public function withExternalId(string $externalId): self
    {
        $this->externalId = $externalId;
        return $this;
    }

    public function withSucceededStatus(): self
    {
        $this->status = Status::succeeded();
        return $this;
    }
    public function withSend(): self
    {
        $this->isSend = true;
        return $this;
    }
    public function withExpiredToken(): self
    {
        $this->returnToken = new Token('a1f10e06-bce8-43ab-9994-f23ce8c5a2b8', new DateTimeImmutable('- 1 hour'));
        return $this;
    }
    public function build(): Payment
    {
        $payment = new Payment(
            $this->id,
            $this->email,
            $this->sourcePaymentId,
            $this->type,
            $this->price,
            $this->createdAt,
            $this->returnToken,
        );

        if ($this->externalId !== null) {
            $payment->setExternalId($this->externalId);
        }

        if ($this->status !== null) {
            $payment->setStatus(Status::succeeded());
        }
        return $payment;
    }
}
