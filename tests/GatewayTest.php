<?php

namespace Omnipay\Easytransac;

use Omnipay\Easytransac\Message\CompletePurchaseRequest;
use Omnipay\Easytransac\Message\HostedPurchaseRequest;
use Omnipay\Easytransac\Message\OneClickPurchaseRequest;
use Omnipay\Easytransac\Message\PurchaseRequest;
use Omnipay\Easytransac\Message\RefundRequest;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    protected $gateway;

    public function setUp(): void
    {
        parent::setUp();
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase(): void
    {
        $request = $this->gateway->purchase();
        $this->assertInstanceOf(PurchaseRequest::class, $request);
    }

    public function testHostedPurchase(): void
    {
        $request = $this->gateway->purchaseHosted();
        $this->assertInstanceOf(HostedPurchaseRequest::class, $request);
    }

    public function testOneClickPurchase(): void
    {
        $request = $this->gateway->oneClickPurchase();
        $this->assertInstanceOf(OneClickPurchaseRequest::class, $request);
    }

    public function testCompletePurchase(): void
    {
        $request = $this->gateway->completePurchase();
        $this->assertInstanceOf(CompletePurchaseRequest::class, $request);
    }

    public function testRefund(): void
    {
        $request = $this->gateway->refund();
        $this->assertInstanceOf(RefundRequest::class, $request);
    }
}
