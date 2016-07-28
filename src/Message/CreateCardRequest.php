<?php
/**
 * Allied Wallet Create Card Request
 */

namespace Omnipay\AlliedWallet\Message;

/**
 * Allied Wallet Create Card Request
 *
 * ### API Documentation
 *
 * This was supplied to me in a skype chat from one of the Allied Wallet techs.  It is
 * not contained in their 1.0.3 API documentation PDF.
 *
 * Caveat: At the time of coding, this API was not finalised.  Things may change.  Consider
 * this dev quality only.
 *
 * #### To Create a Token
 *
 * First API request to create a Token,  merchants/{merchantId}/creditcardtokens
 *
 * This is the JSON request,
 *
 * <code>
 * {
 *   "number": 1,
 *   "expirationMonth": 2,
 *   "expirationYear": 3,
 *   "nameOnCard": "sample string 4",
 *   "cvvCode": "sample string 5"
 *   "firstName": "sample string 4",
 *   "lastName": "sample string 5",
 *   "phone": "sample string 6",
 *   "addressLine1": "sample string 7",
 *   "addressLine2": "sample string 8",
 *   "city": "sample string 9",
 *   "state": "sample string 10",
 *   "countryId": "sample string 11",
 *   "postalCode": "sample string 12",
 *   "email": "sample string 13",
 *   "ipAddress": "sample string 14",
 * }
 * </code>
 *
 * The JSON response from this call,
 *
 * <code>
 * {
 *   "creationDate": “”,
 *   "active": true,
 *   "id": “”
 * }
 * </code>
 *
 * The id  parameter contains the actual token.
 *
 * #### To Use a Token
 *
 * The next step is to call sale transaction with the above Token.
 *
 * API call,  merchants/{merchantId}/tokensaletransactions
 *
 * {
 *   "siteId": "sample string 1",
 *   "amount": 2.0,
 *   "currency": "sample string 3",
 *   "trackingId": "sample string 15",
 *   "isInitialForRecurring": true,
 *   "tokenId": "sample string 17"
 * }
 *
 * Now you should send TokenId instead of Credit card data.
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
 *             'expiryMonth'  => '10',
 *             'expiryYear'   => '2016',
 *             'cvv'          => '123',
 *             'email'        => 'customer@example.com',
 *             'billingAddress1'       => '1 Scrubby Creek Road',
 *             'billingCountry'        => 'AU',
 *             'billingCity'           => 'Scrubby Creek',
 *             'billingPostcode'       => '4999',
 *             'billingState'          => 'QLD',
 * ));
 *
 * // Do a createCard transaction on the gateway
 * $transaction = $gateway->createCard(array(
 *     'clientIp'                 => $_SERVER['REMOTE_ADDR'],
 *     'card'                     => $card,
 * ));
 * $response = $transaction->send();
 * if ($response->isSuccessful()) {
 *     echo "CreateCard transaction was successful!\n";
 *     $card_id = $response->getCardReference();
 *     echo "Card reference = " . $card_id . "\n";
 * }
 * </code>
 *
 * @see \Omnipay\AlliedWallet\Gateway
 */
class CreateCardRequest extends AbstractRequest
{
    protected $action    = 'creditcardtokens';

    public function getData()
    {
        $this->validate('card');

        $data = array();

        $data['FirstName']              = $this->getCard()->getBillingFirstName();
        $data['LastName']               = $this->getCard()->getBillingLastName();
        $data['Phone']                  = $this->getCard()->getBillingPhone();
        $data['AddressLine1']           = $this->getCard()->getBillingAddress1();
        $data['AddressLine2']           = $this->getCard()->getBillingAddress2();
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

        $data['email']                  = $this->getCard()->getEmail();

        // Card Parameters
        $data['number']                 = $this->getCard()->getNumber();
        $data['NameOnCard']             = $this->getCard()->getName();
        $data['ExpirationMonth']        = $this->getCard()->getExpiryMonth();
        $data['ExpirationYear']         = $this->getCard()->getExpiryYear();
        $data['CvvCode']                = $this->getCard()->getCvv();

        return $data;
    }
}
