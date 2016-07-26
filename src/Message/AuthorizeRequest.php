<?php
/**
 * Allied Wallet Authorize Request
 */

namespace Omnipay\AlliedWallet\Message;

/**
 * Allied Wallet Authorize Request
 *
 * Authorize transactions can be initiated using the same JSON string value pairs as the Sale
 * Transaction API.  The only difference is the transaction type passed in the URL.
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
 * $transaction = $gateway->authorize(array(
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
class AuthorizeRequest extends PurchaseRequest
{
    protected $action    = 'authorizetransactions';
}
