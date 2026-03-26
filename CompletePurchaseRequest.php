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

    public function setApiKey(string $value): AbstractRequest
    {
        return $this->setParameter('apiKey', trim($value));
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

    public function getAmount()
    {
        return intval(round($this->getParameter('amount') * 100));
    }

    public function set3DS(bool $value)
    {
        $this->setParameter('3DS', $value ? 'yes' : 'no');
    }

    public function get3DS(): string
    {
        return $this->getParameter('3DS') ?? 'yes';
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
}