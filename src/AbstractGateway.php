<?php

namespace Omnipay\Easytransac;

use Omnipay\Easytransac\Message\CompletePurchaseRequest;
use Omnipay\Easytransac\Message\OneClickPurchaseRequest;
use Omnipay\Easytransac\Message\PurchaseRequest;
use Omnipay\Easytransac\Message\RefundRequest;
use Omnipay\Easytransac\Message\HostedPurchaseRequest;
use Omnipay\Easytransac\Message\RebillRequest;
use Omnipay\Easytransac\Message\CancelRebillRequest;

/**
 * Easytransac Gateway Driver for Omnipay
 *
 * Supporte :
 * - Paiement direct (carte + 3DS)
 * - Page de paiement hébergée (hosted)
 * - One-click (cartes enregistrées via Alias/Uid/ClientId)
 * - Paiement en plusieurs fois (MultiplePayments)
 * - Abonnements / Rebill (Rebill + Recurrence)
 * - Remboursement (total ou partiel)
 */
abstract class AbstractGateway extends \Omnipay\Common\AbstractGateway
{
    abstract public function getName(): string;

    public function getDefaultParameters(): array
    {
        return [
            'apiKey'       => '',
            'language'     => null,
            'returnMethod' => null,
        ];
    }

    // ------------------------------------------------------------------ API Key

    public function getApiKey(): ?string
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey(string $value): self
    {
        return $this->setParameter('apiKey', $value);
    }

    // ------------------------------------------------------------------ Options globales

    public function getLanguage(): ?string
    {
        return $this->getParameter('language');
    }

    public function setLanguage(?string $value): self
    {
        return $this->setParameter('language', $value);
    }

    public function getReturnMethod(): ?string
    {
        return $this->getParameter('returnMethod');
    }

    public function setReturnMethod(?string $value): self
    {
        return $this->setParameter('returnMethod', $value);
    }

    // ------------------------------------------------------------------ Factory methods

    /** Paiement direct (carte en clair, nécessite PCI DSS ou iframe JS EasyTransac) */
    abstract public function purchase(array $parameters = []): PurchaseRequest;

    /** Page de paiement hébergée → redirige vers la page EasyTransac */
    abstract public function purchaseHosted(array $parameters = []): HostedPurchaseRequest;

    /** One-click avec Alias/Uid/ClientId d'une carte déjà enregistrée */
    abstract public function oneClickPurchase(array $parameters = []): OneClickPurchaseRequest;

    /** Traitement du retour/callback EasyTransac (GET ou POST) */
    abstract public function completePurchase(array $parameters = []): CompletePurchaseRequest;

    /** Remboursement total ou partiel d'une transaction */
    abstract public function refund(array $parameters = []): RefundRequest;

    /**
     * Déclencher manuellement un rebill (prélèvement d'abonnement).
     *
     * Paramètres requis : transactionReference (Tid original), amount, clientIp
     * Paramètres optionnels : transactionId (OrderId), description
     */
    abstract public function rebill(array $parameters = []): RebillRequest;

    /**
     * Annuler / arrêter un abonnement en cours.
     *
     * Paramètre requis : transactionReference (Tid original de la souscription)
     */
    abstract public function cancelRebill(array $parameters = []): CancelRebillRequest;
}
