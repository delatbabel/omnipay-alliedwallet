<?php

namespace Omnipay\AlliedWallet\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Tests\TestCase;

class VoidRequestTest extends TestCase
{
    /** @var  AbstractRequest */
    protected $request;

    public function setUp()
    {
        $this->request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setTransactionReference('123456')
            ->setAmount('400.00');
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('VoidSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('123456', $response->getTransactionReference());
        $this->assertSame('Success', $response->getMessage());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('VoidFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Failure', $response->getMessage());
    }
}
