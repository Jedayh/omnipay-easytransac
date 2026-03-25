<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return $this->data['Status'] == 'captured';
    }

    public function isPending(): bool
    {
        return $this->data['Status'] == 'pending';
    }

    public function isCancelled(): bool
    {
        return $this->data['Status'] == 'canceled';
    }

    public function getMessage()
    {
        return $this->data['Message'];
    }

    public function getTransactionReference()
    {
        return $this->data['Tid'];
    }
}
