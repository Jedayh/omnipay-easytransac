<?php

namespace Omnipay\Easytransac\Message;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData(): array
    {
        return array_merge(
            $this->httpRequest->query->all(),
            $this->httpRequest->request->all()
        );
    }

    public function getEndpoint(): string
    {
        return '';
    }

    public function sendData($data): CompletePurchaseResponse
    {
        return $this->response = new CompletePurchaseResponse($this, $data, $this->isSignatureValid((array) $data));
    }

    protected function createResponse($data)
    {
        return new CompletePurchaseResponse($this, $data, is_array($data) ? $this->isSignatureValid($data) : false);
    }
}
