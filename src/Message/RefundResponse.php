<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Message\RequestInterface;

class RefundResponse extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
        $this->request = $request;
        $this->data = json_decode($data, true);
    }

    public function getTransactionReference()
    {
        return $this->data['Tid'] ?? null;
    }
}
