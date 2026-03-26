# Omnipay: Easytransac

Easytransac driver for the Omnipay PHP payment processing library.

## Methods

This version provides:

- `purchase()` for direct card payments
- `purchaseHosted()` for hosted payment page payments
- `oneClickPurchase()` for one-click payments with saved cards
- `completePurchase()` for return/notification handling with signature validation
- `refund()` for refunds

## Newly supported request options

Depending on the request type, the driver now accepts these Easytransac options:

- `saveCard`
- `uid`
- `alias`
- `clientId`
- `cardCvv`
- `language`
- `cancelUrl`
- `returnMethod`
- `operationType`
- `multiplePayments`
- `multiplePaymentsRepeat`
- `downPayment`
- `rebill`
- `recurrence`
- `preAuth`
- `preAuthDuration`
- `payToEmail`
- `payToId`

## Example: hosted payment page

```php
$gateway = Omnipay\Omnipay::create('Easytransac');
$gateway->setApiKey('sk_test_xxx');

$response = $gateway->purchaseHosted([
    'amount' => '49.90',
    'transactionId' => 'ORDER-1001',
    'description' => 'Order #1001',
    'clientIp' => $_SERVER['REMOTE_ADDR'],
    'returnUrl' => 'https://example.com/payment/return',
    'cancelUrl' => 'https://example.com/payment/cancel',
    'language' => 'fr',
    'saveCard' => 'yes',
    'multiplePayments' => 'yes',
    'multiplePaymentsRepeat' => 3,
])->send();

if ($response->isRedirect()) {
    $response->redirect();
}
```

## Example: one-click payment

```php
$response = $gateway->oneClickPurchase([
    'amount' => '9.99',
    'transactionId' => 'ORDER-2001',
    'description' => 'Renewal',
    'clientIp' => $_SERVER['REMOTE_ADDR'],
    'uid' => 'customer-1',
    'alias' => 'card-alias',
    'returnUrl' => 'https://example.com/payment/return',
])->send();
```
