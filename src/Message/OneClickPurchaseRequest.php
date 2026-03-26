<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class OneClickPurchaseRequest extends AbstractRequest
{
    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('amount', 'clientIp', 'transactionId');

        $data = [
            'Amount' => $this->getAmount(),
            'ClientIp' => $this->getClientIp(),
            'OrderId' => $this->getTransactionId(),
            'Description' => $this->getDescription(),
        ];

        $this->addOptionalField($data, 'uid', 'Uid');
        $this->addOptionalField($data, 'alias', 'Alias');
        $this->addOptionalField($data, 'clientId', 'ClientId');
        $this->addOptionalField($data, 'cardCvv', 'CardCVV');
        $this->addOptionalField($data, 'returnUrl', 'ReturnUrl');

        if ($this->getParameter('3DS') !== null) {
            $data['3DS'] = $this->get3DS();
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
        return $this->endpoint . '/payment/oneclick';
    }

    protected function createResponse($data): PurchaseResponse
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
