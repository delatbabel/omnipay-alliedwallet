<?php
/**
 * Allied Wallet Refund Request
 */

namespace Omnipay\AlliedWallet\Message;

/**
 * Allied Wallet Refund Request
 *
 * Only sale and capture transactions can be refunded.
 *
 * <code>
 *   // Do a refund transaction on the gateway
 *   $transaction = $gateway->refund(array(
 *       'transactionReference'     => $sale_id,
 *       'amount'                   => '10.00',
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Refund transaction was successful!\n";
 *       $refund_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $refund_id . "\n";
 *   }
 * </code>
 *
 * @see \Omnipay\AlliedWallet\Gateway
 */
class RefundRequest extends AbstractRequest
{
    protected $action    = 'refundtransactions';

    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = array();

        // Basic parameters
        $data['amount']                 = $this->getAmount();
        $data['referencetransactionid'] = $this->getTransactionReference();

        return $data;
    }
}
