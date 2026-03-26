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
            'apiKey' => 'test_api_key',
            'transactionReference' => '4E6jD7a8',
        ]);

        $result = $request->getData();
        $expected = [
            'Tid' => '4E6jD7a8',
            'Signature' => 'ef9769dfca17d1cfba20ad025b9289d92fa36879',
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @throws InvalidRequestException
     */
    public function testRefundWithAmount()
    {
        $request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'apiKey' => 'test_api_key',
            'transactionReference' => '4E6jD7a8',
            'amount' => 2.50,
        ]);

        $result = $request->getData();
        $expected = [
            'Tid' => '4E6jD7a8',
            'Amount' => 250,
            'Signature' => '8cce5cde0c8e53a4ff99e45d8e451dcd52f58a48',
        ];

        $this->assertEquals($expected, $result);
    }
}
