<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class PurchaseRequest extends AbstractRequest
{
    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('amount', 'clientIp', 'card');

        $data = [
            'Amount' => $this->getAmount(),
            'ClientIp' => $this->getClientIp(),
            'OrderId' => $this->getTransactionId(),
            'Description' => $this->getDescription(),
            'Firstname' => $this->getCard()->getFirstName(),
            'Lastname' => $this->getCard()->getLastName(),
            'Email' => $this->getCard()->getEmail(),
            'Address' => $this->getCard()->getBillingAddress1(),
            'ZipCode' => $this->getCard()->getBillingPostcode(),
            'City' => $this->getCard()->getCity(),
            'Country' => $this->convertCountryToIso3($this->getCard()->getCountry()),
            'CallingCode' => $this->getCard()->getBillingPhoneExtension(),
            'Phone' => $this->getCard()->getBillingPhone(),
            'BirthDate' => $this->getCard()->getBirthday('Y-m-d'),
            '3DS' => $this->get3DS(),
            'CardNumber' => $this->getCard()->getNumber(),
            'CardYear' => $this->getCard()->getExpiryYear(),
            'CardMonth' => sprintf('%02d', $this->getCard()->getExpiryMonth()),
            'CardCVV' => $this->getCard()->getCvv(),
            'ReturnUrl' => $this->getReturnUrl(),
        ];

        $uid = $this->getParameter('uid');
        if ($uid !== null && $uid !== '') {
            $data['Uid'] = $uid;
        }

        $saveCard = $this->getParameter('saveCard');
        if ($saveCard !== null && $saveCard !== '') {
            $data['SaveCard'] = $saveCard;
        }

        $this->addCommonPaymentOptions($data);
        $data = array_filter($data, static function ($value) {
            return $value !== null && $value !== '';
        });
        $data['Signature'] = $this->getSignature($data);

        return $data;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint . '/payment/direct';
    }

    protected function createResponse($data): PurchaseResponse
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
