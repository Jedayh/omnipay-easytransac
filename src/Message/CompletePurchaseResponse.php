<?php

namespace Omnipay\Easytransac\Message;

/**
 * CompletePurchaseResponse — Traitement du retour/callback EasyTransac.
 *
 * Gère les cas :
 *  - Paiement standard (direct ou hosted)
 *  - Paiement en plusieurs fois (MultiplePayments)
 *  - Callback de rebill automatique (IsRebill=1)
 *  - One-click (retourne Uid, Alias, ClientId)
 */
class CompletePurchaseResponse extends \Omnipay\Common\Message\AbstractResponse
{
    protected bool $signatureValid = false;

    public function __construct($request, $data, bool $signatureValid = false)
    {
        parent::__construct($request, $data);
        $this->data          = is_array($data) ? $data : [];
        $this->signatureValid = $signatureValid;
    }

    // ------------------------------------------------------------------ Statuts principaux

    public function isSuccessful(): bool
    {
        return $this->signatureValid
            && in_array($this->data['Status'] ?? null, ['authorized', 'captured'], true);
    }

    public function isPending(): bool
    {
        return $this->signatureValid
            && ($this->data['Status'] ?? null) === 'pending';
    }

    public function isCancelled(): bool
    {
        return !$this->signatureValid
            || in_array($this->data['Status'] ?? null, ['canceled', 'failed', 'error', 'refused'], true);
    }

    public function isSignatureValid(): bool
    {
        return $this->signatureValid;
    }

    // ------------------------------------------------------------------ Champs standards

    public function getStatus(): ?string
    {
        return $this->data['Status'] ?? null;
    }

    public function getMessage(): ?string
    {
        return $this->data['Message'] ?? null;
    }

    public function getTransactionReference(): ?string
    {
        return $this->data['Tid'] ?? null;
    }

    public function getTransactionId(): ?string
    {
        return $this->data['OrderId'] ?? null;
    }

    /** Uid EasyTransac pour le one-click (lié à l'email client) */
    public function getUid(): ?string
    {
        return $this->data['Uid'] ?? null;
    }

    /** Alias EasyTransac pour le one-click (lié à la carte) */
    public function getAlias(): ?string
    {
        return $this->data['Alias'] ?? null;
    }

    /**
     * ClientId EasyTransac — identifiant unique client côté ET.
     * Obligatoire pour les one-click ultérieurs.
     * Peut se trouver à plusieurs emplacements selon la version de l'API.
     */
    public function getClientId(): ?string
    {
        $candidates = [
            $this->data['ClientId']            ?? null,
            $this->data['clientId']            ?? null,
            $this->data['Client']['Id']        ?? null,
            $this->data['Client']['ClientId']  ?? null,
            $this->data['client']['id']        ?? null,
            $this->data['client']['clientId']  ?? null,
        ];

        foreach ($candidates as $candidate) {
            if ($candidate !== null && trim((string) $candidate) !== '') {
                return trim((string) $candidate);
            }
        }

        return null;
    }

    // ------------------------------------------------------------------ Paiements multiples

    /**
     * Indique si ce callback correspond à une échéance d'un paiement en plusieurs fois.
     */
    public function isInstalment(): bool
    {
        return isset($this->data['MultiplePayments'])
            && (string) $this->data['MultiplePayments'] === 'yes';
    }

    /**
     * Numéro de l'échéance actuelle (1, 2, 3…).
     */
    public function getInstalmentCount(): ?int
    {
        $val = $this->data['MultiplePaymentsCount'] ?? null;
        return $val !== null ? (int) $val : null;
    }

    /**
     * Nombre total d'échéances prévu (ex: 3 pour un paiement en 3 fois).
     */
    public function getInstalmentRepeat(): ?int
    {
        $val = $this->data['MultiplePaymentsRepeat'] ?? null;
        return $val !== null ? (int) $val : null;
    }

    /**
     * Vrai si toutes les échéances ont été encaissées.
     */
    public function isInstalmentComplete(): bool
    {
        $count  = $this->getInstalmentCount();
        $repeat = $this->getInstalmentRepeat();

        return $count !== null && $repeat !== null && $count >= $repeat;
    }

    // ------------------------------------------------------------------ Rebill / Abonnement

    /**
     * Indique si ce callback est un prélèvement automatique de rebill.
     * EasyTransac envoie IsRebill=1 dans les notifications de renouvellement.
     */
    public function isRebill(): bool
    {
        return isset($this->data['IsRebill'])
            && (string) $this->data['IsRebill'] === '1';
    }

    /**
     * Numéro du prélèvement de rebill (1-based : 1 = premier renouvellement).
     */
    public function getRebillCount(): ?int
    {
        $val = $this->data['RebillCount'] ?? null;
        return $val !== null ? (int) $val : null;
    }

    /**
     * Tid de la transaction initiale (souscription) ayant déclenché cet abonnement.
     */
    public function getOriginalTid(): ?string
    {
        return $this->data['OriginalPaymentTid'] ?? $this->data['OriginalTid'] ?? null;
    }

    /**
     * Périodicité de l'abonnement (daily, weekly, monthly…).
     */
    public function getRecurrence(): ?string
    {
        return $this->data['Recurrence'] ?? null;
    }

    // ------------------------------------------------------------------ Type d'opération

    /**
     * OperationType renvoyé par EasyTransac (payment | debit | paybybank).
     */
    public function getOperationType(): ?string
    {
        return $this->data['OperationType'] ?? null;
    }
}
