<?php

namespace Omnipay\Easytransac\Message;

use Omnipay\Common\Message\ResponseInterface;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected string $endpoint = 'https://www.easytransac.com/api';

    abstract public function getEndpoint();

    abstract protected function createResponse($data);

    public function getHttpMethod(): string
    {
        return 'POST';
    }

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey(string $value): self
    {
        return $this->setParameter('apiKey', trim($value));
    }

    public function getAmount()
    {
        return (int) round(((float) $this->getParameter('amount')) * 100);
    }

    public function set3DS(bool $value): self
    {
        return $this->setParameter('3DS', $value ? 'yes' : 'no');
    }

    public function get3DS(): string
    {
        return (string) ($this->getParameter('3DS') ?? 'yes');
    }

    public function getUserAgent(): ?string
    {
        return $this->getParameter('userAgent') ?: ($this->httpRequest->server->get('HTTP_USER_AGENT') ?: null);
    }

    public function getLanguage(): ?string
    {
        return $this->getParameter('language');
    }

    public function getReturnMethod(): ?string
    {
        return $this->getParameter('returnMethod');
    }

    protected function getSignature($params): string
    {
        if (is_object($params)) {
            $params = (array) $params;
        }

        $signature = '';

        if (is_array($params)) {
            $params = array_change_key_case($params, CASE_LOWER);
            ksort($params);

            foreach ($params as $name => $value) {
                if (strcasecmp((string) $name, 'signature') !== 0) {
                    if (is_object($value)) {
                        $value = (array) $value;
                    }

                    if (is_array($value)) {
                        $value = array_change_key_case($value, CASE_LOWER);
                        ksort($value);

                        foreach ($value as $v) {
                            $signature .= (string) $v . '$';
                        }
                    } else {
                        $signature .= (string) $value . '$';
                    }
                }
            }
        } else {
            $signature = (string) $params . '$';
        }

        $signature .= (string) $this->getApiKey();

        return sha1($signature);
    }

    protected function isSignatureValid(array $data): bool
    {
        if (!isset($data['Signature'])) {
            return false;
        }

        return hash_equals((string) $data['Signature'], $this->getSignature($data));
    }

    protected function addOptionalField(array &$data, string $parameterName, string $apiField): void
    {
        $value = $this->getParameter($parameterName);
        if ($value !== null && $value !== '') {
            $data[$apiField] = $value;
        }
    }

    protected function addCommonPaymentOptions(array &$data): void
    {
        $this->addOptionalField($data, 'cancelUrl', 'CancelUrl');
        $this->addOptionalField($data, 'language', 'Language');
        $this->addOptionalField($data, 'returnMethod', 'ReturnMethod');
        $this->addOptionalField($data, 'operationType', 'OperationType');
        $this->addOptionalField($data, 'payToEmail', 'PayToEmail');
        $this->addOptionalField($data, 'payToId', 'PayToId');
        $this->addOptionalField($data, 'downPayment', 'DownPayment');
        $this->addOptionalField($data, 'preAuth', 'PreAuth');
        $this->addOptionalField($data, 'preAuthDuration', 'PreAuthDuration');
        $this->addOptionalField($data, 'saveCard', 'SaveCard');

        $userAgent = $this->getUserAgent();
        if ($userAgent) {
            $data['UserAgent'] = $userAgent;
        }

        $multiplePayments = $this->getParameter('multiplePayments');
        if ($multiplePayments !== null && $multiplePayments !== '') {
            $data['MultiplePayments'] = $multiplePayments;
        }

        $multiplePaymentsRepeat = $this->getParameter('multiplePaymentsRepeat');
        if ($multiplePaymentsRepeat !== null && $multiplePaymentsRepeat !== '') {
            $data['MultiplePaymentsRepeat'] = $multiplePaymentsRepeat;
        }

        $rebill = $this->getParameter('rebill');
        if ($rebill !== null && $rebill !== '') {
            $data['Rebill'] = $rebill;
        }

        $recurrence = $this->getParameter('recurrence');
        if ($recurrence !== null && $recurrence !== '') {
            $data['Recurrence'] = $recurrence;
        }
    }

    protected function buildCustomerDataFromCard(): array
    {
        $card = $this->getCard();
        if ($card === null) {
            return [];
        }

        $customer = [
            'Firstname' => $card->getFirstName(),
            'Lastname' => $card->getLastName(),
            'Email' => $card->getEmail(),
            'Address' => $card->getBillingAddress1(),
            'ZipCode' => $card->getBillingPostcode(),
            'City' => $card->getCity(),
            'Country' => $this->convertCountryToIso3($card->getCountry()),
            'CallingCode' => $card->getBillingPhoneExtension(),
            'Phone' => $card->getBillingPhone(),
            'BirthDate' => $card->getBirthday('Y-m-d'),
        ];

        $uid = $this->getParameter('uid');
        if ($uid !== null && $uid !== '') {
            $customer['Uid'] = $uid;
        }

        return array_filter($customer, static function ($value) {
            return $value !== null && $value !== '';
        });
    }

    public function sendData($data): ResponseInterface
    {
        $headers = [
            'EASYTRANSAC-API-KEY' => (string) $this->getApiKey(),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $httpResponse = $this->httpClient->request(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            $headers,
            http_build_query($data)
        );

        return $this->createResponse($httpResponse->getBody()->getContents());
    }

    protected function convertCountryToIso3(?string $country): string
    {
        $country = strtoupper(trim((string) $country));

        $map = [
            'FR' => 'FRA', 'BE' => 'BEL', 'CH' => 'CHE', 'LU' => 'LUX', 'DE' => 'DEU', 'ES' => 'ESP',
            'IT' => 'ITA', 'NL' => 'NLD', 'PT' => 'PRT', 'GB' => 'GBR', 'IE' => 'IRL', 'US' => 'USA',
            'CA' => 'CAN', 'MA' => 'MAR', 'DZ' => 'DZA', 'TN' => 'TUN', 'SN' => 'SEN', 'CI' => 'CIV',
            'CM' => 'CMR', 'MG' => 'MDG', 'RE' => 'REU', 'YT' => 'MYT', 'GP' => 'GLP', 'MQ' => 'MTQ',
            'GF' => 'GUF', 'MC' => 'MCO',
        ];

        return $map[$country] ?? substr($country, 0, 3);
    }
}
