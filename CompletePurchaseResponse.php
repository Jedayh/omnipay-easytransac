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
        $this->data = json_decode($data, true);
    }

    public function getCode()
    {
        return $this->data['Code'] ?? null;
    }

    public function isSuccessful(): bool
    {
        return (isset($this->data['Code']) && $this->data['Code'] == 0);
    }

    public function getMessage()
    {
        return $this->data['Result']['Message'] ?? null;
    }
}
