<?php
/**
 * Allied Wallet Abstract REST Request
 */

namespace Omnipay\AlliedWallet\Message;

use Guzzle\Http\Message\RequestInterface;

/**
 * Allied Wallet Abstract REST Request
 *
 * This is the parent class for all AlliedWallet REST requests.
 *
 * Test modes:
 *
 * The API has two endpoint host names:
 *
 * * api.pin.net.au (live)
 * * test-api.pin.net.au (test)
 *
 * The live host is for processing live transactions, whereas the test
 * host can be used for integration testing and development.
 *
 * Each endpoint requires a different set of API keys, which can be
 * found in your account settings.
 *
 * Currently this class makes the assumption that if the testMode
 * flag is set then the Test Endpoint is being used.
 *
 * @see \Omnipay\AlliedWallet\Gateway
 * @link https://pin.net.au/docs/api
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
        return $this->setParameter('merchantId', $value);
    }

    /**
     * Get the request email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Sets the request email.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
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
     * @param string $action
     * @param array  $data
     * @param string $method
     *
     * @return \Guzzle\Http\Message\Response
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
        return $this->httpClient->createRequest(
            $method,
            $this->getEndpoint() . $action,
            array('Authorization' => 'Bearer ' . $this->getToken(),
                  'Content-type'  => 'application/json'),
            $data
        )->send();
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendRequest($this->action, $data);

        return $this->response = new Response($this, $httpResponse->json());
    }
}
