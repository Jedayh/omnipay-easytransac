<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    private PurchaseRequest $request;
    private array $options;

    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->options = [
            'card' => [
                'firstName' => 'Cusfirstname',
                'lastName' => 'Cuslastname',
                'email' => 'noreply@easytransac.com',
                'number' => '4111111111111111',
                'cvv' => '123',
                'expiryMonth' => '12',
                'expiryYear' => '22',
                'billingAddress1' => '204 avenue de Colmar',
                'billingCity' => 'Strasbourg',
                'billingPostcode' => '67000',
                'country' => 'FR',
                'billingPhone' => '0611223344',
                'billingPhoneExtension' => '33',
                'birthday' => '1990-01-02',
            ],
            'apiKey' => 'test_api_key',
            'amount' => 76.10,
            'description' => 'Mini Bugatti',
            'transactionId' => 'PO_2021_05_121',
            'returnUrl' => 'https://www.example.com/return',
            'invoiceNo' => '20191212-123123',
            'clientIp' => '172.0.1.2',
        ];
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetData()
    {
        $this->request->initialize($this->options);
        $this->request->set3DS(false);
        $result = $this->request->getData();
        $expected = [
            'Amount' => 7610,
            'ClientIp' => '172.0.1.2',
            'OrderId' => 'PO_2021_05_121',
            'Description' => 'Mini Bugatti',
            'Firstname' => 'Cusfirstname',
            'Lastname' => 'Cuslastname',
            'Email' => 'noreply@easytransac.com',
            'Address' => '204 avenue de Colmar',
            'ZipCode' => '67000',
            'City' => 'Strasbourg',
            'Country' => 'FRA',
            'CallingCode' => '33',
            'Phone' => '0611223344',
            'BirthDate' => '1990-01-02',
            '3DS' => 'no',
            'CardNumber' => '4111111111111111',
            'CardYear' => 2022,
            'CardMonth' => '12',
            'CardCVV' => '123',
            'ReturnUrl' => 'https://www.example.com/return',
            'Signature' => '35648d4f3efd27980e1907e5b58e7eed8b472a5d',
        ];

        $this->assertEquals($expected, $result);
    }
}
