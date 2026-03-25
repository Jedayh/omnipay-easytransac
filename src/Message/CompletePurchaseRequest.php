<?php

namespace Omnipay\Easytransac\Message;

/**
 * We don't need to implement AbstractRequest from the Easytransac namespace
 * It's only a redirection with post data, the Omnipay version is covering our need
 **/
class CompletePurchaseRequest extends \Omnipay\Common\Message\AbstractRequest
{
    public function getData(): array
    {
        // we return all the parameters received
        return $this->httpRequest->request->all();
    }

    public function sendData($data): CompletePurchaseResponse
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
