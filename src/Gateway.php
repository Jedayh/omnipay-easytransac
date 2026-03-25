<?php

namespace Omnipay\Easytransac;

use Omnipay\Easytransac\Message\PurchaseRequest;
use Omnipay\Easytransac\Message\CompletePurchaseRequest;
use Omnipay\Easytransac\Message\RefundRequest;

/**
 * Easytransac Gateway Driver for Omnipay
 * @see \Omnipay\Easytransac\AbstractGateway
 * This driver is based on Easytransac API documentation
 * @link https://www.easytransac.com/en/documentation
 */
class Gateway extends AbstractGateway
{
    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'Easytransac';
    }

    /**
     * @inheritdoc
     */
    public function purchase(array $parameters = array()): PurchaseRequest
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @inheritdoc
     * @return CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array()): CompletePurchaseRequest
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    /**
     * @inheritdoc
     * @return RefundRequest
     */
    public function refund(array $parameters = array()): RefundRequest
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }
}
