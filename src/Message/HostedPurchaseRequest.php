<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class HostedPurchaseRequest extends AbstractRequest
{
    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('amount', 'clientIp', 'returnUrl');

        $data = [
            'Amount' => $this->getAmount(),
            'ClientIp' => $this->getClientIp(),
            'OrderId' => $this->getTransactionId(),
            'Description' => $this->getDescription(),
            '3DS' => $this->get3DS(),
            'ReturnUrl' => $this->getReturnUrl(),
        ];

        $this->addCommonPaymentOptions($data);

        $customer = $this->buildCustomerDataFromCard();
        foreach ($customer as $field => $value) {
            $data[$field] = $value;
        }

        $data = array_filter($data, static function ($value) {
            return $value !== null && $value !== '';
        });
        $data['Signature'] = $this->getSignature($data);

        return $data;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint . '/payment/page';
    }

    protected function createResponse($data): HostedPurchaseResponse
    {
        return $this->response = new HostedPurchaseResponse($this, $data);
    }
}
