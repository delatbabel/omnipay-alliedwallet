<?php
/**
 * Allied Wallet Void Request
 */

namespace Omnipay\AlliedWallet\Message;

/**
 * Allied Wallet Void Request
 *
 * Only authorize transactions can be voided.
 *
 * <code>
 *   // Do a void transaction on the gateway
 *   $transaction = $gateway->void(array(
 *       'transactionReference'     => $sale_id,
 *       'amount'                   => '10.00',
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Void transaction was successful!\n";
 *       $void_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $void_id . "\n";
 *   }
 * </code>
 *
 * @see \Omnipay\AlliedWallet\Gateway
 */
class VoidRequest extends AbstractRequest
{
    protected $action    = 'voidtransactions';

    public function getData()
    {
        $this->validate('transactionReference');

        $data = array();

        // Basic parameters
        $data['referencetransactionid'] = $this->getTransactionReference();

        return $data;
    }
}
