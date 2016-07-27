<?php
/**
 * Allied Wallet Response
 */

namespace Omnipay\AlliedWallet\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Allied Wallet Response
 *
 * This is the response class for all Allied Wallet REST requests.
 *
 * @see \Omnipay\AlliedWallet\Gateway
 */
class Response extends AbstractResponse
{
    public function isSuccessful()
    {
        if (isset($this->data['status']) && ($this->data['status'] == 'Successful')) {
            return true;
        }
        return false;
    }

    public function getTransactionReference()
    {
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }
    }

    public function getCardReference()
    {
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }
    }

    public function getTransactionId()
    {
        if (isset($this->data['trackingid'])) {
            return $this->data['trackingid'];
        }
    }

    public function getMessage()
    {
        if (isset($this->data['message'])) {
            return $this->data['message'];
        }
    }

    public function getCode()
    {
        if (isset($this->data['status'])) {
            return $this->data['status'];
        }
    }
}
