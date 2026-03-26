<?php

namespace Omnipay\Easytransac;

use Omnipay\Easytransac\Message\CompletePurchaseRequest;
use Omnipay\Easytransac\Message\OneClickPurchaseRequest;
use Omnipay\Easytransac\Message\PurchaseRequest;
use Omnipay\Easytransac\Message\RefundRequest;
use Omnipay\Easytransac\Message\HostedPurchaseRequest;

/**
 * Easytransac Gateway Driver for Omnipay
 */
abstract class AbstractGateway extends \Omnipay\Common\AbstractGateway
{
    abstract public function getName(): string;

    public function getDefaultParameters(): array
    {
        return [
            'apiKey' => '',
            'language' => null,
            'returnMethod' => null,
        ];
    }

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey(string $value): self
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getLanguage(): ?string
    {
        return $this->getParameter('language');
    }

    public function setLanguage(?string $value): self
    {
        return $this->setParameter('language', $value);
    }

    public function getReturnMethod(): ?string
    {
        return $this->getParameter('returnMethod');
    }

    public function setReturnMethod(?string $value): self
    {
        return $this->setParameter('returnMethod', $value);
    }

    abstract public function purchase(array $parameters = []): PurchaseRequest;

    abstract public function purchaseHosted(array $parameters = []): HostedPurchaseRequest;

    abstract public function oneClickPurchase(array $parameters = []): OneClickPurchaseRequest;

    abstract public function completePurchase(array $parameters = []): CompletePurchaseRequest;

    abstract public function refund(array $parameters = []): RefundRequest;
}
