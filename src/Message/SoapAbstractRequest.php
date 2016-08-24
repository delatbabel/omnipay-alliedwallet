<?php

namespace Omnipay\AlliedWallet\Message;

use Guzzle\Http\ClientInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use SoapClient;
use SoapFault;

/**
 * Allied Wallet SOAP gateway Abstract Request
 *
 * The merchant web service is accessible at the following URL:
 *
 * https://service.381808.com/Merchant.asmx
 *
 * The WSDL description is accessible with the following URL:
 *
 * https://service.381808.com/Merchant.asmx?WSDL
 *
 * SOAP requires a namespace for all operations. The namespace is as follows:
 *
 * http://service.381808.com/
 */
abstract class SoapAbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $namespace = 'http://service.381808.com/';

    /**
     * Test Endpoint URL
     *
     * @var string URL
     */
    protected $testEndpoint = 'https://service.381808.com/Merchant.asmx?WSDL';

    /**
     * Live Endpoint URL
     *
     * @var string URL
     */
    protected $liveEndpoint = 'https://service.381808.com/Merchant.asmx?WSDL';

    /** @var  SoapClient */
    protected $soapClient;

    /**
     * The generated SOAP request data, saved immediately before a transaction is run.
     *
     * @var array
     */
    protected $request;

    /**
     * The retrieved SOAP response, saved immediately after a transaction is run.
     *
     * @var SoapResponse
     */
    protected $response;

    /**
     * The amount of time in seconds to wait for both a connection and a response. Total potential wait time is this value times 2 (connection + response).
     *
     * @var float
     */
    public $timeout = 10;

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
     * @return SoapAbstractRequest implements a fluent interface
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
     * @return SoapAbstractRequest implements a fluent interface
     */
    public function setSiteId($value)
    {
        return $this->setParameter('siteId', $value);
    }

    /**
     * Build the request object
     *
     * @return array
     */
    public function getData()
    {
        $this->request = array();
        $this->request['MerchantID']    = $this->getMerchantId();

        return $this->request;
    }

    /**
     * Build the SOAP Client and the internal request object
     *
     * @return SoapClient
     * @throws \Exception
     */
    public function buildSoapClient()
    {
        if (! empty($this->soapClient)) {
            return $this->soapClient;
        }

        $context_options = array(
            'http' => array(
                'timeout' => $this->timeout,
            ),
        );

        $context = stream_context_create($context_options);

        // options we pass into the soap client
        // turn on HTTP compression
        // set the internal character encoding to avoid random conversions
        // throw SoapFault exceptions when there is an error
        $soap_options = array(
            'compression'           => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
            'encoding'              => 'utf-8',
            'exceptions'            => true,
            'connection_timeout'    => $this->timeout,
            'stream_context'        => $context,
        );

        // if we're in test mode, don't cache the wsdl
        if ($this->getTestMode()) {
            $soap_options['cache_wsdl'] = WSDL_CACHE_NONE;
        } else {
            $soap_options['cache_wsdl'] = WSDL_CACHE_BOTH;
        }

        try {
            // create the soap client
            $this->soapClient = new \SoapClient($this->getEndpoint(), $soap_options);
            return $this->soapClient;
        } catch (SoapFault $sf) {
            throw new \Exception($sf->getMessage(), $sf->getCode());
        }
    }

    /**
     * Run the SOAP transaction
     *
     * Over-ride this in sub classes.
     *
     * @param SoapClient $soapClient
     * @param array $data
     * @return array
     * @throws \Exception
     */
    protected function runTransaction($soapClient, $data)
    {
        // Dummy, over-ride this.
        return $data;
    }

    /**
     * Send Data to the Gateway
     *
     * @param array $data
     * @return SoapResponse
     * @throws \Exception
     */
    public function sendData($data)
    {
        // Build the SOAP client
        $soapClient = $this->buildSoapClient();

        // Replace this line with the correct function.
        $response = $this->runTransaction($soapClient, $data);

        return $this->response = new SoapResponse($this, $response);
    }

    /**
     * Get the SOAP endpoint
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
