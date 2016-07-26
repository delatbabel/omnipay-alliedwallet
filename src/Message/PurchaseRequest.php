<?php
/**
 * Allied Wallet Purchase Request
 */

namespace Omnipay\AlliedWallet\Message;

/**
 * Allied Wallet Purchase Request
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
 * @see \Omnipay\AlliedWallet\Gateway
 */
class PurchaseRequest extends AbstractRequest
{
    protected $action    = 'saletransactions';

    public function getData()
    {
        $this->validate('siteId', 'amount', 'currency', 'card', 'clientIp', 'transactionId');

        $data = array();

        // Basic parameters
        $data['siteId']                 = $this->getSiteId();
        $data['amount']                 = $this->getAmount();
        $data['currency']               = strtoupper($this->getCurrency());

        // Cardholder Parameters
        $data['firstName']              = $this->getCard()->getBillingFirstName();
        $data['lastName']               = $this->getCard()->getBillingLastName();
        $data['phone']                  = $this->getCard()->getBillingPhone();
        $data['addressLine1']           = $this->getCard()->getBillingAddress1();
        $data['addressLine2']           = $this->getCard()->getBillingAddress2();
        $data['city']                   = $this->getCard()->getBillingCity();
        $data['state']                  = $this->getCard()->getBillingState();
        $data['countryId']              = $this->getCard()->getBillingCountry();
        $data['postalCode']             = $this->getCard()->getBillingPostcode();

        $data['ShippingFirstName']      = $this->getCard()->getShippingFirstName();
        $data['ShippingLastName']       = $this->getCard()->getShippingLastName();
        $data['ShippingPhone']          = $this->getCard()->getShippingPhone();
        $data['ShippingAddressLine1']   = $this->getCard()->getShippingAddress1();
        $data['ShippingAddressLine2']   = $this->getCard()->getShippingAddress2();
        $data['ShippingCity']           = $this->getCard()->getShippingCity();
        $data['ShippingState']          = $this->getCard()->getShippingState();
        $data['ShippingCountryId']      = $this->getCard()->getShippingCountry();
        $data['ShippingPostalCode']     = $this->getCard()->getShippingPostcode();

        $data['email']                  = $this->getCard()->getEmail();
        $data['cardNumber']             = $this->getCard()->getNumber();
        $data['nameOnCard']             = $this->getCard()->getName();
        $data['expirationMonth']        = $this->getCard()->getExpiryMonth();
        $data['expirationYear']         = $this->getCard()->getExpiryYear();
        $data['cVVCode']                = $this->getCard()->getCvv();

        // Transaction parameters
        $data['iPAddress']              = $this->getClientIp();
        $data['trackingId']             = $this->getTransactionId();
        $data['isInitialForRecurring']  = false;

        return $data;
    }
}
