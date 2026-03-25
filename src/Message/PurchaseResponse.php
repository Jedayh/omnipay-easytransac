<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
        $this->request = $request;
        $this->data = json_decode($data, true);
    }

    public function isSuccessful(): bool
    {
        if (parent::isSuccessful() && isset($this->data['Result']['Status'])) {
            return $this->data['Code'] == 0 && in_array($this->data['Result']['Status'], ['authorized', 'captured']);
        }

        return false;
    }

    public function isPending(): bool
    {
        if (isset($this->data['Code'])) {
            return $this->data['Code'] == 0 && $this->data['Result']['Status'] == 'pending';
        }

        return false;
    }

    public function isRedirect(): bool
    {
        return isset($this->data['Result']['3DSecureUrl']);
    }

    public function getTransactionId()
    {
        return $this->data['Result']['OrderId'] ?? null;
    }

    public function getTransactionReference()
    {
        return $this->data['Result']['Tid'] ?? null;
    }

    public function getRedirectUrl()
    {
        return $this->data['Result']['3DSecureUrl'] ?? null;
    }

    public function getRedirectMethod(): string
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return $this->getData();
    }
}
