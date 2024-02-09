<?php

declare(strict_types=1);

namespace Ecommerce121\PartSmartIntegration\Model;

use Ecommerce121\PartSmartIntegration\Constants;
use Exception;
use Magento\Framework\App\Request\Http;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\Webapi\Response;
use Psr\Log\LoggerInterface;

class PartSmart
{
    /**
     * @param Curl $curlClient
     * @param UrlInterface $backendUrl
     * @param Config $config
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected readonly Curl $curlClient,
        private readonly UrlInterface $backendUrl,
        private readonly Config $config,
        private readonly LoggerInterface $logger
    ) {}

    /**
     * @return array
     */
    public function authenticate(): array
    {
        try {
            $apiUrl = $this->getApiUrl() . $this->config->getAuthenticateEndpoint();

            $curl = $this->getCurlClient();
            $this->setupCurlOptions($curl);
            $curl->post($apiUrl, $this->config->getCredentialPayload());

            return $this->checkRequest($curl, 'authenticate');
        } catch (Exception $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    /**
     * @param $customer
     * @return array|void
     */
    public function portalUserAuthenticate($customer)
    {
        try {
            $auth = $this->authenticate();
            if (array_key_exists('error', $auth)) {
                throw new Exception('There was an error during the authentication. Please see the logs');
            }

            $apiUrl = $this->getApiUrl() . $this->config->getPortalUserEndpoint();
            $curl = $this->getCurlClient();
            $this->setupCurlOptions($curl, $auth['access_token']);

            $curl->post($apiUrl, $this->config->getUserPortalCredentialPayload($customer));
            return $this->checkRequest($curl, 'portalUserAuthenticate');

         } catch (Exception $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    /**
     * @param Curl $curl
     * @param string $bearer
     * @return Curl
     */
    public function setupCurlOptions(Curl $curl, string $bearer = ''): Curl
    {
        $bearerAuth = ($bearer) ? 'Authorization: Bearer ' . $bearer : null;
        $curl->setOptions(
            [
                CURLOPT_REFERER => $this->getReferer(),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    $bearerAuth
                ],
            ]
        );

        return $curl;
    }

    /**
     * @param $curl
     * @param $caller
     * @return mixed
     * @throws Exception
     */
    public function checkRequest($curl, $caller)
    {
        $responseCode = $curl->getStatus();
        $responseBody = json_decode($curl->getBody(), true);

        if ($responseCode != Response::HTTP_OK && $responseCode != 201) {
            $message = (array_key_exists('Invalid', $responseBody))
                ? $responseBody['Invalid'][0]
                : 'There was an error during the authentication. Please see the logs';

            $this->logger->critical(
                'PartSmart::' . $caller .' ',
                [$responseCode => $responseBody]
            );

            throw new Exception('['.$responseCode.'] '. print_r($message, true));
        }

        return $responseBody;
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return Constants::API_URL_PREFIX . Constants::API_BASE_URL;
    }

    /**
     * @return Curl
     */
    public function getCurlClient(): Curl
    {
        return $this->curlClient;
    }

    /**
     * @return string
     */
    public function getReferer(): string
    {
        return Http::getUrlNoScript($this->backendUrl->getBaseUrl()) . 'admin/partsmart/system_config/authenticate';
    }
}