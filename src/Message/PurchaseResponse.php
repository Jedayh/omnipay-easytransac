<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful(): bool
    {
        return parent::isSuccessful() && in_array($this->getStatus(), ['authorized', 'captured'], true);
    }

    public function isPending(): bool
    {
        return parent::isSuccessful() && $this->getStatus() === 'pending';
    }

    public function isCancelled(): bool
    {
        return $this->getStatus() === 'canceled';
    }

    public function isRedirect(): bool
    {
        return (bool) ($this->getRedirectUrl() ?: $this->getRedirectData());
    }

    public function getRedirectUrl()
    {
        return $this->data['Result']['3DSecureUrl']
            ?? $this->data['Result']['RedirectUrl']
            ?? $this->data['Result']['MandateUrl']
            ?? null;
    }

    public function getRedirectMethod(): string
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }
}
