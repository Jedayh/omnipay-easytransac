<?php

namespace Omnipay\Easytransac\Message;

class RefundResponse extends AbstractResponse
{
    public function getDateRefund()
    {
        return $this->data['Result']['DateRefund'] ?? $this->data['DateRefund'] ?? null;
    }

    public function getAmountRefund()
    {
        return $this->data['Result']['AmountRefund'] ?? $this->data['AmountRefund'] ?? null;
    }
}
