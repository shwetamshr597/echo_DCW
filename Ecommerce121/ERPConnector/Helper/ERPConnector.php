<?php

declare(strict_types=1);

namespace Ecommerce121\ERPConnector\Helper;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\ResponseFactory;
use Psr\Log\LoggerInterface;

class ERPConnector
{

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var ClientFactory
     */
    private ClientFactory $__clientFactory;

    /**
     * @param ClientFactory $clientFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ClientFactory $clientFactory,
        LoggerInterface $logger
    ) {
        $this->__clientFactory = $clientFactory;
        $this->_logger = $logger;
    }

    /**
     * @param $params
     * @param $baseUrl
     * @param $endpoint
     * @param $user
     * @param $pass
     * @param $httpMethod
     * @return array|\Psr\Http\Message\ResponseInterface
     */
    public function makePostCall($params, $baseUrl, $endpoint, $user, $pass, $httpMethod)
    {
        try {
            $client = $this->getClient($baseUrl);
            $body['headers'] = $this->getRequestContent($user, $pass);
            $body['body'] = json_encode($params);
            $this->logRequest($params, $endpoint, $httpMethod, $baseUrl);
            $response = $client->request(
                $httpMethod,
                $endpoint,
                $body
            );
        } catch (GuzzleException $exception) {
            $this->_logger->error(__METHOD__ . ":" . __("CSI_ERP API: Exception is:" . $exception->getMessage()));
            $response = [
                'status' => $exception->getCode(),
                'reason' => $exception->getMessage()
            ];
        }

        return $response;
    }

    /**
     * @param array $params
     * @param string $endpoint
     * @param string $httpMethod
     * @param string $base
     * @return void
     */
    private function logRequest(array $params, string $endpoint, string $httpMethod, string $base): void
    {
        $this->_logger->info(
            sprintf(
                __METHOD__ . ":: " . "TIBCO API: Preparing Request. Request content:
                 \n >>>HTTP-Request-Content: %s \n >>>HTTP-Request-Endpoint: %s \n >>>HTTP-Request-Method: %s",
                json_encode($params),
                $base . $endpoint,
                $httpMethod
            )
        );
    }

    /**
     * @param $urlBase
     * @return Client
     */
    private function getClient($urlBase): Client
    {
        return $this->__clientFactory->create([
            'config' => [
                'base_uri' => $urlBase
            ]
        ]);
    }

    /**
     * @param $userName
     * @param $password
     * @return array[]
     */
    public function getRequestContent($userName, $password): array
    {
        return [
            "Content-Type" => 'application/json',
            "Authorization" => "Basic " . base64_encode($userName . ":" . $password),
            "Accept" => "*/*"
        ];
    }
}
