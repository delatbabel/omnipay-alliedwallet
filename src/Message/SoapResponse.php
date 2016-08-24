<?php

namespace Omnipay\AlliedWallet\Message;

use DOMDocument;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * Allied Wallet SOAP Response
 */
class SoapResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        // TODO: Implement isSuccessful() method.
    }
}
