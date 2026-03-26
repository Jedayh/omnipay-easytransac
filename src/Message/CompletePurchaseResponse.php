<?php

namespace Omnipay\Easytransac\Message;

class CompletePurchaseResponse extends \Omnipay\Common\Message\AbstractResponse
{
    protected bool $signatureValid = false;

    public function __construct($request, $data, bool $signatureValid = false)
    {
        parent::__construct($request, $data);
        $this->data = is_array($data) ? $data : [];
        $this->signatureValid = $signatureValid;
    }

    public function isSuccessful(): bool
    {
        return $this->signatureValid && in_array(($this->data['Status'] ?? null), ['authorized', 'captured'], true);
    }

    public function isPending(): bool
    {
        return $this->signatureValid && (($this->data['Status'] ?? null) === 'pending');
    }

    public function isCancelled(): bool
    {
        return !$this->signatureValid || in_array(($this->data['Status'] ?? null), ['canceled', 'failed', 'error', 'refused'], true);
    }

    public function isSignatureValid(): bool
    {
        return $this->signatureValid;
    }

    public function getStatus()
    {
        return $this->data['Status'] ?? null;
    }

    public function getMessage()
    {
        return $this->data['Message'] ?? null;
    }

    public function getTransactionReference()
    {
        return $this->data['Tid'] ?? null;
    }

    public function getTransactionId()
    {
        return $this->data['OrderId'] ?? null;
    }

    public function getUid()
    {
        return $this->data['Uid'] ?? null;
    }

    public function getAlias()
    {
        return $this->data['Alias'] ?? null;
    }
}
