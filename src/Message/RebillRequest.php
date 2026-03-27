<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * RebillRequest — Déclenche un prélèvement d'abonnement (rebill manuel).
 *
 * Endpoint EasyTransac : POST /api/payment/rebill
 *
 * Paramètres requis :
 *   - transactionReference : Tid de la transaction initiale qui a créé l'abonnement
 *   - amount               : montant en euros (ex: 19.90)
 *   - clientIp             : IP du client
 *
 * Paramètres optionnels :
 *   - transactionId  : OrderId pour cette opération de rebill
 *   - description    : libellé de l'opération
 *
 * Flux typique :
 *   1. Premier paiement avec rebill=yes + recurrence=monthly → EasyTransac crée l'abonnement
 *   2. EasyTransac envoie les prélèvements suivants en notification automatique
 *   3. Si prélèvement manuel nécessaire → appeler rebill() avec le Tid original
 *
 * Référence module PrestaShop : notification.php gère les callbacks rebill entrants
 * (OperationType=payment + IsRebill=1 dans le POST de notification)
 */
class RebillRequest extends AbstractRequest
{
    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('transactionReference', 'amount', 'clientIp');

        $data = [
            'Tid'      => $this->getTransactionReference(),
            'Amount'   => $this->getAmount(),
            'ClientIp' => $this->getClientIp(),
        ];

        // OrderId optionnel pour tracer le rebill côté marchand
        $orderId = $this->getTransactionId();
        if ($orderId !== null && $orderId !== '') {
            $data['OrderId'] = $orderId;
        }

        // Description optionnelle
        $description = $this->getDescription();
        if ($description !== null && $description !== '') {
            $data['Description'] = $description;
        }

        $data = array_filter($data, static fn($v) => $v !== null && $v !== '');
        $data['Signature'] = $this->getSignature($data);

        return $data;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint . '/payment/rebill';
    }

    protected function createResponse($data): RebillResponse
    {
        return $this->response = new RebillResponse($this, $data);
    }
}
