<?php
/**
 * Allied Wallet Capture Request
 */

namespace Omnipay\AlliedWallet\Message;

/**
 * Allied Wallet Capture Request
 */
class CaptureRequest extends AbstractRequest
{
    protected $action    = 'capturetransactions';

    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = array();

        // Basic parameters
        $data['amount']                 = $this->getAmount();
        $data['authorizetransactionid'] = $this->getTransactionReference();

        return $data;
    }
}
