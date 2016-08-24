<?php

namespace Omnipay\AlliedWallet\Message;

use SoapClient;

/**
 * Allied Wallet SOAP Purchase Request
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
 */
class SoapPurchaseRequest extends SoapAbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'card', 'currency');

        // Create the base request
        $this->request = parent::getData();
        $this->request['SiteID']        = $this->getSiteId();

        // Fill the request data
        $card = $this->getCard();
        $this->request['IPAddress']         = $this->getClientIp();
        $this->request['Amount']            = $this->getAmount();
        $this->request['CurrencyID']        = $this->getCurrency();
        $this->request['FirstName']         = $card->getBillingFirstName();
        $this->request['LastName']          = $card->getBillingLastName();
        $this->request['Phone']             = $card->getBillingPhone();
        $this->request['Address']           = $card->getBillingAddress1();
        $this->request['City']              = $card->getBillingCity();
        $this->request['State']             = $card->getBillingState();
        $this->request['Country']           = $card->getBillingCountry();
        $this->request['ZipCode']           = $card->getBillingPostcode();
        $this->request['Email']             = $card->getEmail();
        $this->request['CardNumber']        = $card->getNumber();
        $this->request['CardName']          = $card->getBillingName();
        $this->request['ExpiryMonth']       = $card->getExpiryMonth();
        $this->request['ExpiryYear']        = $card->getExpiryYear();
        $this->request['CardCVV']           = $card->getCvv();

        // Only set MerchantReference if it is not empty
        if ($this->getTransactionId()) {
            $this->request['MerchantReference'] = $this->getTransactionId();
        }

        return $this->request;
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
        // SOAP function for execute transaction on credit card data
        if ($this->getTransactionId()) {
            $this->responseName = 'ExecuteCreditCard2Result';
            return $soapClient->ExecuteCreditCard2($data);
        }
        $this->responseName = 'ExecuteCreditCardResult';
        return $soapClient->ExecuteCreditCard($data);
    }
}
