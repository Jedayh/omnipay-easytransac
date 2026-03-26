<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    protected CompletePurchaseRequest $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->getHttpRequest()->request->set('Tid', 'X12Gx3E');
        $this->getHttpRequest()->request->set('Status', 'captured');
        $this->getHttpRequest()->request->set('Message', 'Mocked message');
        $this->getHttpRequest()->query->set('OrderId', 'PO_123');
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();
        $this->assertSame('X12Gx3E', $data['Tid']);
        $this->assertSame('PO_123', $data['OrderId']);
        $this->assertCount(4, $data);
    }

    public function testCapturedResponse(): void
    {
        $response = new CompletePurchaseResponse($this->getMockRequest(), [
            'Tid' => 'X12Gx3E',
            'Status' => 'captured',
            'Message' => 'Mocked message',
        ], true);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isPending());
        $this->assertFalse($response->isCancelled());
        $this->assertTrue($response->isSignatureValid());
        $this->assertSame('X12Gx3E', $response->getTransactionReference());
        $this->assertSame('Mocked message', $response->getMessage());
    }

    public function testPendingResponse(): void
    {
        $response = new CompletePurchaseResponse($this->getMockRequest(), [
            'Status' => 'pending',
        ], true);

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isPending());
        $this->assertFalse($response->isCancelled());
    }

    public function testCancelledResponseWhenSignatureIsInvalid(): void
    {
        $response = new CompletePurchaseResponse($this->getMockRequest(), [
            'Status' => 'captured',
        ], false);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isPending());
        $this->assertTrue($response->isCancelled());
        $this->assertFalse($response->isSignatureValid());
    }
}
