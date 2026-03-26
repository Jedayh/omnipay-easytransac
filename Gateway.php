<?php

namespace Omnipay\Easytransac;

use Omnipay\Common\AbstractGateway as AbstractOmnipayGateway;
use Omnipay\Easytransac\Message\CompletePurchaseRequest;
use Omnipay\Easytransac\Message\PurchaseRequest;
use Omnipay\Easytransac\Message\RefundRequest;

/**
 * Easytransac Gateway Driver for Omnipay
 *
 * This driver is based on Easytransac API documentation
 * @link https://www.easytransac.com/en/documentation
 */
abstract class AbstractGateway extends AbstractOmnipayGateway
{
    /**
     * @inheritdoc
     */
    abstract public function getName(): string;

    /**
     * Get the gateway parameters.
     * @return array
     */
    public function getDefaultParameters(): array
    {
        return array(
            'apiKey' => '',
        );
    }

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }
    /**
     * Set the gateway API Key.
     * Setting the testMode flag on this gateway has no effect.  To
     * use test mode just use your test mode API key.
     *
     * @param string $value
     * @return Gateway provides a fluent interface.
     */
    public function setApiKey(string $value): self
    {
        return $this->setParameter('apiKey', $value);
    }

    abstract public function purchase(array $parameters = array()): PurchaseRequest;

    abstract public function completePurchase(array $parameters = array()): CompletePurchaseRequest;

    abstract public function refund(array $parameters = array()): RefundRequest;
}
