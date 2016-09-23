<?php

namespace Omnipay\AlliedWallet\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Tests\TestCase;

class SoapRefundRequestTest extends TestCase
{
    /** @var  AbstractRequest */
    protected $request;

    public function setUp()
    {
        $this->request = new SoapRefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setTransactionReference('123456')
            ->setAmount('400.00');
    }

    public function testData()
    {
        $data = $this->request->getData();

        $this->assertSame('123456', $data['TransactionID']);
    }
}
