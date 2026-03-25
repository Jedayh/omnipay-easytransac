<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    protected CompletePurchaseRequest $request;

    public function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->getHttpRequest()->request->set('Tid', 'X12Gx3E');
        $this->getHttpRequest()->request->set('Status', 'captured');
        $this->getHttpRequest()->request->set('Message', 'Mocked message');
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame('X12Gx3E', $data['Tid']);
        $this->assertCount(3, $data);
    }
}
