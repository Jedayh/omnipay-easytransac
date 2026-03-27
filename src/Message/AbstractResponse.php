<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Message\AbstractResponse as AbstractOmnipayResponse;
use Omnipay\Common\Message\RequestInterface;

abstract class AbstractResponse extends AbstractOmnipayResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
        $this->request = $request;
        $this->data = is_array($data) ? $data : (json_decode((string) $data, true) ?: []);
    }

    public function getCode()
    {
        return $this->data['Code'] ?? null;
    }

    public function isSuccessful(): bool
    {
        return isset($this->data['Code']) && (int) $this->data['Code'] === 0;
    }

    public function getMessage()
    {
        return $this->data['Result']['Message'] ?? $this->data['Message'] ?? null;
    }

    public function getStatus()
    {
        return $this->data['Result']['Status'] ?? $this->data['Status'] ?? null;
    }

    public function getTransactionReference()
    {
        return $this->data['Result']['Tid'] ?? $this->data['Tid'] ?? null;
    }

    public function getTransactionId()
    {
        return $this->data['Result']['OrderId'] ?? $this->data['OrderId'] ?? null;
    }

    public function getUid()
    {
        return $this->data['Result']['Uid'] ?? $this->data['Uid'] ?? null;
    }

    public function getAlias()
    {
        return $this->data['Result']['Alias'] ?? $this->data['Alias'] ?? null;
    }

    public function getOriginalPaymentTid()
    {
        return $this->data['Result']['OriginalPaymentTid'] ?? $this->data['OriginalPaymentTid'] ?? null;
    }

    public function getError()
    {
        return $this->data['Result']['Error'] ?? $this->data['Error'] ?? null;
    }

    public function getAdditionalError()
    {
        return $this->data['Result']['AdditionalError'] ?? $this->data['AdditionalError'] ?? null;
    }
}
