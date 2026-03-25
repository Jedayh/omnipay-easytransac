<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    /**
     * @throws InvalidRequestException
     */
    public function testRefund()
    {
        $request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'transactionReference' => '4E6jD7a8',
        ]);

        $result = $request->getData();
        $expected = ['Tid' => '4E6jD7a8'];

        $this->assertEquals($expected, $result);
        $this->assertInstanceOf(RefundResponse::class, $request->sendData($request->getData()));
    }
}
