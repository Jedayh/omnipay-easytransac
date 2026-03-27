<?php

namespace Omnipay\Easytransac\Message;

/**
 * CancelRebillResponse — Réponse à une demande d'annulation d'abonnement.
 */
class CancelRebillResponse extends AbstractResponse
{
    /**
     * L'annulation est confirmée si Code === 0.
     * EasyTransac ne retourne pas de Result supplémentaire dans ce cas.
     */
    public function isSuccessful(): bool
    {
        return isset($this->data['Code']) && (int) $this->data['Code'] === 0;
    }
}
