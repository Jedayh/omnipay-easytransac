<?php

namespace Omnipay\Easytransac\Message;

/**
 * RebillResponse — Réponse à un prélèvement de rebill manuel.
 *
 * Champs pertinents de la réponse EasyTransac :
 *   - Code    : 0 = succès, autre = erreur
 *   - Result.Tid    : TID de la nouvelle transaction de rebill
 *   - Result.Status : authorized | captured | pending | failed | canceled
 *   - Result.Message
 */
class RebillResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return parent::isSuccessful()
            && in_array($this->getStatus(), ['authorized', 'captured'], true);
    }

    public function isPending(): bool
    {
        return parent::isSuccessful() && $this->getStatus() === 'pending';
    }

    /**
     * Tid de la nouvelle transaction rebill (différent du Tid original).
     */
    public function getRebillTid(): ?string
    {
        return $this->data['Result']['Tid'] ?? $this->data['Tid'] ?? null;
    }
}
