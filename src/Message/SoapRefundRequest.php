<?php

namespace Omnipay\AlliedWallet\Message;

use SoapClient;

/**
 * Allied Wallet SOAP Refund Request
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
 * #### Direct Credit Card Purchase and Refund
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
 *
 *       // Find the transaction reference
 *       $auth_id = $response->getTransactionReference();
 *
 *       // Refund the transaction
 *      $transaction = $gateway->refund(array(
 *          'transactionReference'  => $auth_id,
 *      ));
 *      $response = $transaction->send();
 *      if ($response->isSuccessful()) {
 *          echo "Refund was successful\n";
 *      } else {
 *          echo "Refund failed.\n";
 *      }
 *   } catch (\Exception $e) {
 *       echo "Exception caught while attempting authorize.\n";
 *       echo "Exception type == " . get_class($e) . "\n";
 *       echo "Message == " . $e->getMessage() . "\n";
 *   }
 * </code>
 */
class SoapRefundRequest extends SoapCaptureRequest
{
    public function getData()
    {
        // Create the base request
        $this->request = parent::getData();

        // Fill the amount if it is present
        if ($this->getAmount()) {
            $this->request['RefundAmount'] = $this->getAmount();
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
        // SOAP function for refund transaction
        if ($this->getAmount()) {
            return $soapClient->PartialRefund($data);
        }
        return $soapClient->Refund($data);
    }
}
