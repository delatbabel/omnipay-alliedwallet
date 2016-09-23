<?php

namespace Omnipay\AlliedWallet\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Allied Wallet SOAP Response
 */
class SoapResponse extends AbstractResponse
{
    public function __construct(SoapAbstractRequest $request, $data)
    {
        parent::__construct($request, $data);

        // Convert the SOAP Response (stdClass containing a stdClass) to an array.
        $responseName = $request->responseName;
        $this->data   = json_decode(json_encode($data->$responseName), true);
    }

    public function isSuccessful()
    {
        if (! empty($this->data['Status']) && $this->data['Status'] == 1) {
            return true;
        }
        return false;
    }

    /**
     * Response Message
     *
     * @return null|string A response message from the payment gateway
     */
    public function getMessage()
    {
        if (! empty($this->data['Message'])) {
            return $this->data['Message'];
        }
        return null;
    }

    /**
     * Response code
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode()
    {
        // One of the response code from the gateway is Status : 0
        // Therefore using empty() PHP function will fail the condition.
        if (isset($this->data['Status']) && ! is_null($this->data['Status'])) {
            return $this->data['Status'];
        }
        return null;
    }

    /**
     * Gateway Reference
     *
     * @return null|string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference()
    {
        if (! empty($this->data['TransactionID'])) {
            return $this->data['TransactionID'];
        }
        return null;
    }
}
