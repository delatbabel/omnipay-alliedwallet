<?php

namespace Omnipay\AlliedWallet;

use Omnipay\Common\AbstractGateway;

/**
 * Allied Wallet SOAP gateway
 *
 * 381808 uses operations of a SOAP service over HTTP/HTTPS to integrate for
 * transactions (including settlement, void, refund, chargeback, etc. capabilities).
 *
 * Before you will be able to submit transactions to 381808, you will need an
 * 381808 merchant account for your website. Once you have a merchant account
 * established, 381808 will supply you with a MerchantID and a SiteID. These IDs
 * uniquely identify your websites, customers, and payments.
 *
 * ### Test Mode
 *
 * There is no test mode for this gateway.  Contact Allied Wallet to enable test mode.
 *
 * Test transactions can be made with these card data:
 *
 * * **Card Number** 4242424242424242
 * * **Expiry Date** Anything in the future
 * * **CVV** CVV 555 will result in a decline, 123 or almost any other will be successful
 *
 * ### Credentials
 *
 * The merchant is identified with a Site ID and a Merchant ID, both of which are 36 character
 * GUIDs in the following format:  xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
 *
 * There appear to be no other credentials such as usernames, passwords, OAuth Tokens, etc.
 *
 * ### Example
 *
 * #### Initialize Gateway
 *
 * <code>
 *   // Create a gateway for the Allied Wallet Soap Gateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create('AlliedWallet_Soap');
 *
 *   // Initialise the gateway
 *   $gateway->initialize(array(
 *       'merchantId'   => 'MyMerchantId',
 *       'siteId'       => 'MySiteId',
 *       'testMode' => true, // Or false when you are ready for live transactions
 *   ));
 * </code>
 *
 * #### Direct Credit Card Payment
 *
 * <code>
 *   // Create a credit card object
 *   $card = new CreditCard(array(
 *               'firstName' => 'Example',
 *               'lastName' => 'User',
 *               'number' => '4242424242424242',
 *               'expiryMonth'           => '01',
 *               'expiryYear'            => '2020',
 *               'cvv'                   => '123',
 *               'billingAddress1'       => '1 Scrubby Creek Road',
 *               'billingCountry'        => 'AU',
 *               'billingCity'           => 'Scrubby Creek',
 *               'billingPostcode'       => '4999',
 *               'billingState'          => 'QLD',
 *   ));
 *
 *   // Do a purchase transaction on the gateway
 *   try {
 *       $transaction = $gateway->purchase(array(
 *           'amount'        => '10.00',
 *           'currency'      => 'AUD',
 *           'description'   => 'This is a test purchase transaction.',
 *           'card'          => $card,
 *       ));
 *       $response = $transaction->send();
 *       $data = $response->getData();
 *       echo "Gateway purchase response data == " . print_r($data, true) . "\n";
 *
 *       if ($response->isSuccessful()) {
 *           echo "Purchase transaction was successful!\n";
 *       }
 *   } catch (\Exception $e) {
 *       echo "Exception caught while attempting authorize.\n";
 *       echo "Exception type == " . get_class($e) . "\n";
 *       echo "Message == " . $e->getMessage() . "\n";
 *   }
 * </code>
 *
 * ### Quirks
 *
 * * Card Tokens are not supported.
 * * Voids of captured transactions are not supported, only voiding authorize transactions is supported.
 */
class SoapGateway extends AbstractGateway
{
    /* Default Abstract Gateway methods that need to be overridden */
    public function getName()
    {
        return 'Allied Wallet - SOAP';
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantId'    => '',
            'siteId'        => '',
            'testMode'      => false,
        );
    }

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
     * @return SoapGateway implements a fluent interface
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
     * @return SoapGateway implements a fluent interface
     */
    public function setSiteId($value)
    {
        return $this->setParameter('siteId', $value);
    }

    /**
     * Create a purchase request
     *
     * @param array $parameters
     * @return \Omnipay\AlliedWallet\Message\SoapPurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AlliedWallet\Message\SoapPurchaseRequest', $parameters);
    }

    /**
     * Create an authorize request
     *
     * @param array $parameters
     * @return \Omnipay\AlliedWallet\Message\SoapAuthorizeRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AlliedWallet\Message\SoapAuthorizeRequest', $parameters);
    }

    /**
     * Create a capture request
     *
     * @param array $parameters
     * @return \Omnipay\AlliedWallet\Message\SoapCaptureRequest
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AlliedWallet\Message\SoapCaptureRequest', $parameters);
    }

    /**
     * Create a refund request
     *
     * @param array $parameters
     * @return \Omnipay\AlliedWallet\Message\SoapRefundRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AlliedWallet\Message\SoapRefundRequest', $parameters);
    }

    /**
     * Create a void request
     *
     * @param array $parameters
     * @return \Omnipay\AlliedWallet\Message\SoapVoidRequest
     */
    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AlliedWallet\Message\SoapVoidRequest', $parameters);
    }
}
