<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class RefundRequest extends AbstractRequest
{
    /**
     * @throws InvalidRequestException
     */
  public function getData(): array
{
    $this->validate('transactionReference');

    $data = [
        'Tid' => $this->getTransactionReference(),
    ];

    if ($this->getParameter('amount') !== null && $this->getParameter('amount') !== '') {
        $data['Amount'] = $this->getAmount();
    }

    $data['Signature'] = $this->getSignature($data);

    return $data;
}
    public function getEndpoint(): string
    {
        return $this->endpoint . '/payment/refund';
    }

    protected function createResponse($data): RefundResponse
    {
        return $this->response = new RefundResponse($this, $data);
    }
}
