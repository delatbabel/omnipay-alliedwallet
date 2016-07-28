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
 * #### Card Payments
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
 *             'expiryMonth'  => '10',
 *             'expiryYear'   => '2016',
 *             'cvv'          => '123',
 *             'email'        => 'customer@example.com',
 *             'billingAddress1'       => '1 Scrubby Creek Road',
 *             'billingCountry'        => 'AU',
 *             'billingCity'           => 'Scrubby Creek',
 *             'billingPostcode'       => '4999',
 *             'billingState'          => 'QLD',
 *             'billingPhone'          => '07 9999 9999',
 * ));
 *
 * // Do a purchase transaction on the gateway
 * $transaction = $gateway->purchase(array(
 *     'description'              => 'Your order for widgets',
 *     'amount'                   => '10.00',
 *     'currency'                 => 'AUD',
 *     'clientIp'                 => $_SERVER['REMOTE_ADDR'],
 *     'card'                     => $card,
 *     'transactionId'            => rand(100000,9999999),
 * ));
 * $response = $transaction->send();
 * if ($response->isSuccessful()) {
 *     echo "Purchase transaction was successful!\n";
 *     $sale_id = $response->getTransactionReference();
 *     echo "Transaction reference = " . $sale_id . "\n";
 * }
 * </code>
 *
 * #### Token Payments
 *
 * See CreateCardRequest for the code to create a token.
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
 * // Do a purchase transaction on the gateway
 * $transaction = $gateway->purchase(array(
 *     'description'              => 'Your order for widgets',
 *     'amount'                   => '10.00',
 *     'currency'                 => 'AUD',
 *     'clientIp'                 => $_SERVER['REMOTE_ADDR'],
 *     'cardReference'            => $card_id,
 *     'transactionId'            => rand(100000,9999999),
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
        $this->validate('siteId', 'amount', 'currency', 'transactionId');

        $data = array();

        // Basic parameters
        $data['SiteId']                 = $this->getSiteId();
        $data['amount']                 = $this->getAmount();
        $data['Currency']               = strtoupper($this->getCurrency());

        // Transaction parameters
        $data['TrackingId']             = $this->getTransactionId();

        $token = $this->getCardReference();
        if (! empty($token)) {
            // Token payments
            $data['tokenId']                = $token;

            // Card token payments use a different endpoint to card payments.
            $this->action                   = 'tokensaletransactions';

        } else {
            // Card payments
            $this->validate('card', 'clientIp');
            $this->getCard()->validate();

            // Cardholder Parameters
            $data['FirstName']              = $this->getCard()->getBillingFirstName();
            $data['LastName']               = $this->getCard()->getBillingLastName();
            $data['Phone']                  = $this->getCard()->getBillingPhone();
            $data['AddressLine1']           = $this->getCard()->getBillingAddress1();
            # $data['AddressLine2']           = $this->getCard()->getBillingAddress2();
            $data['AddressLine2']           = '1';
            $data['City']                   = $this->getCard()->getBillingCity();
            $data['State']                  = $this->getCard()->getBillingState();
            $data['CountryId']              = $this->getCard()->getBillingCountry();
            $data['PostalCode']             = $this->getCard()->getBillingPostcode();

            /*
            $data['ShippingFirstName']      = $this->getCard()->getShippingFirstName();
            $data['ShippingLastName']       = $this->getCard()->getShippingLastName();
            $data['ShippingPhone']          = $this->getCard()->getShippingPhone();
            $data['ShippingAddressLine1']   = $this->getCard()->getShippingAddress1();
            $data['ShippingAddressLine2']   = $this->getCard()->getShippingAddress2();
            $data['ShippingCity']           = $this->getCard()->getShippingCity();
            $data['ShippingState']          = $this->getCard()->getShippingState();
            $data['ShippingCountryId']      = $this->getCard()->getShippingCountry();
            $data['ShippingPostalCode']     = $this->getCard()->getShippingPostcode();
            */

            $data['IpAddress']              = $this->getClientIp();
            $data['email']                  = $this->getCard()->getEmail();

            // Card Parameters
            $data['cardNumber']             = $this->getCard()->getNumber();
            $data['NameOnCard']             = $this->getCard()->getName();
            $data['ExpirationMonth']        = $this->getCard()->getExpiryMonth();
            $data['ExpirationYear']         = $this->getCard()->getExpiryYear();
            $data['CvvCode']                = $this->getCard()->getCvv();
        }

        // Strip all empty values from the data
        $data = array_filter($data);
        $data['IsInitialForRecurring']  = 'false';
        $data['SubscriptionPlanId']     = 0;

        return $data;
    }
}
