<?php

namespace Omnipay\Easytransac;

use Omnipay\Easytransac\Message\CompletePurchaseRequest;
use Omnipay\Easytransac\Message\HostedPurchaseRequest;
use Omnipay\Easytransac\Message\OneClickPurchaseRequest;
use Omnipay\Easytransac\Message\PurchaseRequest;
use Omnipay\Easytransac\Message\RefundRequest;

class Gateway extends AbstractGateway
{
    public function getName(): string
    {
        return 'Easytransac';
    }

    public function purchase(array $parameters = []): PurchaseRequest
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    public function purchaseHosted(array $parameters = []): HostedPurchaseRequest
    {
        return $this->createRequest(HostedPurchaseRequest::class, $parameters);
    }

    public function oneClickPurchase(array $parameters = []): OneClickPurchaseRequest
    {
        return $this->createRequest(OneClickPurchaseRequest::class, $parameters);
    }

    public function completePurchase(array $parameters = []): CompletePurchaseRequest
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    public function refund(array $parameters = []): RefundRequest
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }
}
