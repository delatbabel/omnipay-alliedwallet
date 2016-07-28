<?php
/**
 * Allied Wallet Abstract REST Request
 */

namespace Omnipay\AlliedWallet\Message;

use Guzzle\Http\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Allied Wallet Abstract REST Request
 *
 * This is the parent class for all Allied Wallet REST requests.
 *
 * ### Test modes
 *
 * The API has only one endpoint which is https://api.alliedwallet.com/
 *
 * @see \Omnipay\AlliedWallet\Gateway
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $action    = '';

    /**
     * Test Endpoint URL
     *
     * @var string URL
     */
    protected $testEndpoint = 'https://api.alliedwallet.com/merchants/';

    /**
     * Live Endpoint URL
     *
     * @var string URL
     */
    protected $liveEndpoint = 'https://api.alliedwallet.com/merchants/';

    /**
     * Get merchant id
     *
     * Use the Merchant ID assigned by Allied wallet.
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * Set merchant id
     *
     * Use the Merchant ID assigned by Allied wallet.
     *
     * @param string $value
     * @return AbstractRequest implements a fluent interface
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * Get site id
     *
     * Use the Site ID assigned by Allied wallet.
     *
     * @return string
     */
    public function getSiteId()
    {
        return $this->getParameter('siteId');
    }

    /**
     * Set site id
     *
     * Use the Site ID assigned by Allied wallet.
     *
     * @param string $value
     * @return AbstractRequest implements a fluent interface
     */
    public function setSiteId($value)
    {
        return $this->setParameter('siteId', $value);
    }

    /**
     * Get API endpoint URL
     *
     * @return string
     */
    protected function getEndpoint()
    {
        $base = $this->liveEndpoint;
        return $base . $this->getMerchantId() . '/';
    }

    /**
     * Send a request to the gateway.
     *
     * The request should contain the following header settings:
     *
     * Content-type: application/json
     * Authorization: Bearer <OAuth Bearer Token>
     *
     * @param string $action
     * @param array  $data
     * @param string $method
     *
     * @return \Guzzle\Http\Message\Response
     * @throws InvalidResponseException
     */
    public function sendRequest($action, $data = null, $method = RequestInterface::POST)
    {
        // don't throw exceptions for 4xx errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );

        // Return the response we get back from AlliedWallet Payments

        // Create the HTTP request
        $httpRequest = $this->httpClient->createRequest(
            $method,
            $this->getEndpoint() . $action,
            array(
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $this->getToken(),
                'Content-type'  => 'application/json'
            ),
            json_encode($data)
        );

        // Set the HTTP request options to TLS 1.2 and send the request,
        // returning the response.
        try {
            // Set TLS version to 1.2
            $httpRequest->getCurlOptions()->set(CURLOPT_SSLVERSION, 6);
            return $httpRequest->send();
        } catch (\Exception $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendRequest($this->action, $data);

        return $this->response = new Response($this, $httpResponse->json());
    }
}
