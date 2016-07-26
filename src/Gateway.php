<?php
/**
 * Allied Wallet Gateway
 */

namespace Omnipay\AlliedWallet;

use Omnipay\Common\AbstractGateway;

/**
 * Allied Wallet Gateway
 *
 * Allied Wallet offers customized payment solutions to businesses of any size. Allied Wallet
 * provide payment processing services in 164 currencies, 196 countries, and nearly every payment
 * method globally.
 *
 * ### Example
 *
 * <code>
 * // Create a gateway for the AlliedWallet REST Gateway
 * // (routes to GatewayFactory::create)
 * $gateway = Omnipay::create('AlliedWallet');
 *
 * // Initialise the gateway
 * $gateway->initialize(array(
 *     'merchantId' => 'TEST',
 *     'siteId'     => 'TEST',
 *     'token'      => 'TEST',
 *     'testMode'   => true, // Or false when you are ready for live transactions
 * ));
 *
 * // Create a credit card object
 * // This card can be used for testing.
 * $card = new CreditCard(array(
 *             'firstName'    => 'Example',
 *             'lastName'     => 'Customer',
 *             'number'       => '4242424242424242',
 *             'expiryMonth'  => '01',
 *             'expiryYear'   => '2020',
 *             'cvv'          => '123',
 *             'email'        => 'customer@example.com',
 *             'billingAddress1'       => '1 Scrubby Creek Road',
 *             'billingCountry'        => 'AU',
 *             'billingCity'           => 'Scrubby Creek',
 *             'billingPostcode'       => '4999',
 *             'billingState'          => 'QLD',
 * ));
 *
 * // Do a purchase transaction on the gateway
 * $transaction = $gateway->purchase(array(
 *     'description'              => 'Your order for widgets',
 *     'amount'                   => '10.00',
 *     'currency'                 => 'AUD',
 *     'clientIp'                 => $_SERVER['REMOTE_ADDR'],
 *     'card'                     => $card,
 * ));
 * $response = $transaction->send();
 * if ($response->isSuccessful()) {
 *     echo "Purchase transaction was successful!\n";
 *     $sale_id = $response->getTransactionReference();
 *     echo "Transaction reference = " . $sale_id . "\n";
 * }
 * </code>
 *
 * ### Quirks
 *
 * * This gateway does not support token payments.
 * * For card payments, there are a lot of mandatory fields for cardholder information.
 *   First and last names, phone number, address, city, state, postal code, country, are
 *   all listed as mandatory by the gateway documentation.
 * * A transaction Id (sent to the gateway as trackingId) is required for every transaction.
 *   This is alphanumeric with a limit of 100 characters.
 *
 * ### Test modes
 *
 * The API has only one endpoint which is https://api.alliedwallet.com/
 *
 * ### Authentication
 *
 * Calls to the Allied Wallet Payments API must be authenticated using an OAuth
 * Bearer Token.
 *
 * @see \Omnipay\Common\AbstractGateway
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'AlliedWallet';
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantId'    => '',
            'siteId'        => '',
            'oAuthToken'    => '',
            'testMode' => false,
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
     * @return Gateway implements a fluent interface
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
     * @return Gateway implements a fluent interface
     */
    public function setSiteId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * Create a purchase request
     *
     * @param array $parameters
     * @return \Omnipay\AlliedWallet\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AlliedWallet\Message\PurchaseRequest', $parameters);
    }

    /**
     * Create an authorize request
     *
     * @param array $parameters
     * @return \Omnipay\AlliedWallet\Message\AuthorizeRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AlliedWallet\Message\AuthorizeRequest', $parameters);
    }

    /**
     * Create a capture request
     *
     * @param array $parameters
     * @return \Omnipay\AlliedWallet\Message\CaptureRequest
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AlliedWallet\Message\CaptureRequest', $parameters);
    }

    /**
     * Create a refund request
     *
     * @param array $parameters
     * @return \Omnipay\AlliedWallet\Message\RefundRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AlliedWallet\Message\RefundRequest', $parameters);
    }

    /**
     * Create a void request
     *
     * @param array $parameters
     * @return \Omnipay\AlliedWallet\Message\VoidRequest
     */
    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AlliedWallet\Message\VoidRequest', $parameters);
    }
}
