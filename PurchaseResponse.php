<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
{
    return isset($this->data['Status']) && $this->data['Status'] === 'captured';
}

public function isPending(): bool
{
    return isset($this->data['Status']) && $this->data['Status'] === 'pending';
}

public function isCancelled(): bool
{
    return isset($this->data['Status']) && $this->data['Status'] === 'canceled';
}

public function getMessage()
{
    return $this->data['Message'] ?? null;
}

public function getTransactionReference()
{
    return $this->data['Tid'] ?? null;
}
}
