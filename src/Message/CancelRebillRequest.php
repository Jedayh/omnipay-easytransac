<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * CancelRebillRequest — Annule / stoppe un abonnement EasyTransac.
 *
 * Endpoint EasyTransac : POST /api/payment/cancel
 *
 * Paramètre requis :
 *   - transactionReference : Tid de la transaction initiale (souscription)
 *
 * Après l'appel, EasyTransac ne génère plus de prélèvements automatiques
 * pour cet abonnement. L'opération est irréversible.
 *
 * Référence module PrestaShop : AdminEasyTransacCancel.php
 */
class CancelRebillRequest extends AbstractRequest
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

        $data['Signature'] = $this->getSignature($data);

        return $data;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint . '/payment/cancel';
    }

    protected function createResponse($data): CancelRebillResponse
    {
        return $this->response = new CancelRebillResponse($this, $data);
    }
}
