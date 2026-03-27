<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class HostedPurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful(): bool
    {
        return false;
    }

    public function isRedirect(): bool
    {
        return !empty($this->getRedirectUrl());
    }

    public function getRedirectUrl()
    {
        return $this->data['Result']['PageUrl'] ?? null;
    }

    public function getRedirectMethod(): string
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }

    public function getRequestId()
    {
        return $this->data['Result']['RequestId'] ?? null;
    }
}
